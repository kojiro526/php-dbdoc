# DB定義書ジェネレータ

DB定義書を既存のスキーマから生成するツールです。

## 特徴

- 既存のデータベース上のスキーマからDB定義書をMarkdown形式で出力します。
- カラム、インデックス、外部キー等の情報を出力します。
- ファイル内でツールが更新するべき場所としない場所が分離されており、ツールが更新しない場所には任意の記述を加えることができます。
- ファイル内にコメント形式で記述されたタグを元にテーブルとファイルの紐付けを行っているため、ファイル名は自由に変更することができます。

## 必要要件

- php >= 7.1.0
- composerが予め利用可能である必要があります。 （ [インストール方法](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) ）

## 事前の設定

このコマンドはcomposerでグローバルインストールして使うことを想定しているため、以下のディレクトリにパスを通して下さい。

### Linux, OSX等

`.bash_profile`等に以下のパスを設定して下さい。

```
export PATH=$HOME/.composer/vendor/bin:$PATH
```

### Windows

Windowsでは以下のフォルダを環境変数のPATHに設定して下さい。

```
%USERPROFILE%\AppData\Roaming\Composer\vendor\bin
```

## インストール

```
$ composer global require kojiro526/php-dbdoc
```

本リポジトリに含まれるDockerfileを元にDockerイメージをビルドすると、環境構築の手間を省く事が出来ます。

```
$ docker build -t kojiro526/php-dbdoc .
$ docker run -it --rm -v $(pwd):/work kojiro526/php-dbdoc dbdoc-php --help
```

## 使い方

```
$ dbdoc-php --help
Database docments generator

Usage:
  dbdoc-php [options]

Options:
  -c config, --config=config        Indicate config file
  -o output, --output=output        Indicate output file or directory
  -s, --split                       Separate files one by one
  -h host, --host=host              Indicate database host
  -d dbname, --dbname=dbname        Indicate schema name
  -u user, --user=user              Indicate database user name
  -p password, --password=password  Indicate database password
  -P port, --port=port              Indicate database port
  --help                            show this help message and exit
```

以下のようにしてDBに接続し、指定したディレクトリ配下にDB定義書を出力します。

※localhostにMySQL（MariaDB）のデータベースが立ち上がっている想定です。

```
$ dbdoc-php --host=localhost --dbname=sampledb --user=root --password=hogehoge123 --port=3306 -o ./output_dir
$ ls ./output_dir
departments.md   devisions.md        profiles.md     users.md
$ cat ./output_dir/users.md
## users

### カラム定義

<!-- dbdoc-users-column-info Start -->

| No | 名前 | 型 | 主キー | 必須 | 初期値 | AI | US |
|:---|:---|:---|:---:|:---:|:---|:---:|:---:|
| 1 | id | INT | ○ | ○ |  | ○ | ○ |
| 2 | email | VARCHAR(255) |  | ○ |  |  |  |
| 3 | password | VARCHAR(255) |  | ○ |  |  |  |
| 4 | remember_token | VARCHAR(100) |  |  |  |  |  |
| 5 | deleted | DATETIME |  |  |  |  |  |
| 6 | created | DATETIME |  |  |  |  |  |
| 7 | modified | DATETIME |  |  |  |  |  |

__AI__ =AutoIncrement / __US__ =Unsigned

<!-- dbdoc-column-ordered-list-template
1. id
2. email
3. password
4. remember_token
5. deleted
6. created
7. modified
dbdoc-column-ordered-list-template -->


<!-- dbdoc-users-column-info End -->

### インデックス

<!-- dbdoc-users-index-info Start -->

| No | インデックス | 主キー | ユニーク |
|:---|:---|:---:|:---:|
| 1 | PRIMARY ( id ) | ○ | ○ |
| 2 | email_UNIQUE ( email ) |  | ○ |


<!-- dbdoc-users-index-info End -->

### 外部キー

<!-- dbdoc-users-fkey-info Start -->



<!-- dbdoc-users-fkey-info End -->

<!-- Don't remove the following comments. -->
<!-- dbdoc-users-marker-label -->
```

ファイル中のコメントタグは、本ツールが更新するブロックを認識するための目印です。

本ツールを再度実行してDB定義書を更新すると、上記のブロック部分のみが更新されるため、それ以外の場所をどのように編集しても影響はありません。

## Dockerを使った実行方法

本ツールはPHP7.1以上を要件としており、環境によっては利用が難しい場合もあり得るため、その場合はDockerを使って実行することもできます。

本リポジトリに含まれるDockerfileをビルドしてDockerイメージを作成して下さい。

```
$ docker build -t kojiro526/php-dbdoc .
```

Dockerイメージは以下のようになっています。

- 本ツールが最初からインストール済みです。
- コンテナ内の`/work`をWORKDIRとして設定しているため、カレントディレクトリを`/work`にマウントするなどして使って下さい。

```
$ docker run -it --rm -v $(pwd):/work kojiro526/php-dbdoc dbdoc-php --host=docker.for.mac.localhost --dbname=sampledb --user=root --password=hogehoge123 --port=3306 -o ./output_dir
```

※コンテナ内からローカルPC上の`localhost`にアクセスするためには`、docker.for.mac.localhost`（Windowsの場合は`docker.for.win.localhost`）と指定する必要があります。（そうしなければ、コンテナ内の`localhost`を参照してしまいます）


## サンプル

本リポジトリに含まれるdocker-compose.ymlはコマンドの実行環境とサンプルDBを含んでおり、気軽に動作を試すことが出来ます。

サンプルの環境は以下のように実行して下さい。

```
$ docker-compose up -d
```

### サンプルDB

上記のコマンド実行後、以下の場所にあるSQLが自動的に実行されてサンプルDBが作成されます。

```
./example/db/init/schema.sql
```

### サンプルDBのドキュメント生成

以下のようにサンプルDBのドキュメントを`./tmp`配下に出力できます。

```
$ docker-compose exec php dbdoc-php --host=db --dbname=sampledb --user=root --password=hogehoge123 --port=3306 -o ./tmp/
$ ls ./tmp/
devisions.md        profiles.md     users.md    departments.md
```


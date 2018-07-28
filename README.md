# DB定義書ジェネレータ

DB定義書を既存のスキーマから生成するツールです。

## 特徴

- 既存のデータベース上のスキーマからDB定義書をMarkdown形式で出力します。
- カラム、インデックス、外部キー等の情報を出力します。

## 使い方

```
$ docker-compose exec php php -q ./bin/dbdoc-php --help
Database docments generator

Usage:
  ./bin/dbdoc-php [options]

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

```
$ dbdoc-php --host=db --dbname=sampledb --user=root --password=hogehoge123 --port=3306 -o ./output_dir
$ ls ./output_dir
departments.md   devisions.md        profiles.md     users.md
$ cat ./output_dir/users.md
<!-- php-dbdoc_users-table-info Start -->

- users

<!-- php-dbdoc_users-table-info End -->

<!-- php-dbdoc_users-column-info Start -->

| No | 名前 | 型 | 主キー | 必須 | 初期値 | AI | US |
|:---|:---|:---|:---:|:---|:---:|:---:|
| 1 | id | integer | ○ | ○ |  | ○ | ○ |
| 2 | email | string(255) |  | ○ |  |  |  |
| 3 | password | string(255) |  | ○ |  |  |  |
| 4 | remember_token | string(100) |  |  |  |  |  |
| 5 | deleted | datetime |  |  |  |  |  |
| 6 | created | datetime |  |  |  |  |  |
| 7 | modified | datetime |  |  |  |  |  |

<!-- php-dbdoc_users-column-info End -->

<!-- php-dbdoc_users-index-info Start -->

| No | インデックス | 主キー | ユニーク |
|:---|:---|:---:|:---:|
| 1 | PRIMARY ( id ) | ○ | ○ |
| 2 | email_UNIQUE ( email ) |  | ○ |

<!-- php-dbdoc_users-index-info End -->

<!-- php-dbdoc_users-fkey-info Start -->



<!-- php-dbdoc_users-fkey-info End -->
```

ファイル中のコメントタグは、本ツールが更新するブロックを認識するための目印です。

本ツールを再度実行してDB定義書を更新すると、上記のブロック部分のみが更新されるため、それ以外の場所をどのように編集しても影響はありません。

逆に言うと、上記のコメントで挟まれたブロックは本ツールによって上書きされる可能性があるため、手動で編集するべきではありません。

はい、承知いたしました。不要なファイルを削除した後の構成に合わせて `README.md` を修正します。

---

# シンプルユーザー登録・管理システム

## 1. 概要

このプロジェクトは、PHP、MySQL、HTML、JavaScript (Fetch API) を使用した基本的なCRUD（作成・読み取り・更新・削除）操作が可能なユーザー登録・管理システムです。
ユーザーは名前とメールアドレスを登録でき、登録されたユーザーの一覧表示、情報の編集、削除が行えます。学習目的や小規模なアプリケーションの雛形として利用できます。

## 2. 使用技術

-   **バックエンド**: PHP (PDOによるMySQL操作)
-   **データベース**: MySQL
-   **フロントエンド**: HTML, CSS (インラインスタイル), JavaScript (Fetch API)
-   **Webサーバー**: ApacheやNginxなど (MAMP/XAMPPなどのローカル開発環境での利用を想定)

## 3. 主な機能

-   新規ユーザー登録（名前、メールアドレス）
-   登録済みユーザーの一覧表示
-   ユーザー情報のインライン編集
-   ユーザー情報の削除 (確認ダイアログ付き)
-   フォーム送信時の非同期処理と結果表示

## 4. ファイル構成

プロジェクトは主に以下のファイルで構成されています（実行時はWebサーバのドキュメントルート直下に配置することを推奨します）。

```
.(ドキュメントルート)/
├── form.html               # 新規ユーザー登録フォーム
├── list_with_edit.html     # ユーザー一覧表示・編集・削除ページ
├── add_user.php            # ユーザー登録処理API
├── users.php               # ユーザー一覧取得API
├── update_user.php         # ユーザー更新処理API
├── delete_user.php         # ユーザー削除処理API
└── db_config.php           # データベース接続設定
```

-   `form.html`: ユーザーが名前とメールアドレスを入力し、送信するためのインターフェースです。
-   `list_with_edit.html`: データベースに登録されているユーザーの一覧を表示し、編集・削除操作を行うインターフェースです。
-   `add_user.php`: `form.html` から送信されたデータを受け取り、データベースに新しいユーザーを登録するPHPスクリプトです。
-   `users.php`: データベースから全ユーザーの情報を取得し、JSON形式で返すPHPスクリプトです。`list_with_edit.html` で使用されます。
-   `update_user.php`: 特定のユーザー情報を更新するためのPHPスクリプトです。`list_with_edit.html` から利用されます。
-   `delete_user.php`: 特定のユーザーを削除するためのPHPスクリプトです。`list_with_edit.html` から利用されます。
-   `db_config.php`: MySQLデータベースへの接続情報（ホスト名、データベース名、ユーザー名、パスワード）を設定するファイルです。

## 5. セットアップ手順

### 5.1. 前提条件

-   PHPが動作するWebサーバー環境 (例: MAMP, XAMPP, WAMP, Apache + PHP, Nginx + PHP-FPM)
-   MySQLデータベースサーバー (バージョン 5.7 以上推奨)

### 5.2. データベース設定

1.  **データベースの作成**:
    MySQLサーバーに `test_db` という名前のデータベースを作成してください。文字コードは `utf8mb4` を推奨します。
    ```sql
    CREATE DATABASE test_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    ```

2.  **テーブルの作成**:
    作成した `test_db` データベース内に、以下のSQLを実行して `users` テーブルを作成してください。
    ```sql
    USE test_db;

    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE
    );
    ```
    このテーブルには、ユーザーID (`id`)、名前 (`name`)、メールアドレス (`email`) が格納されます。メールアドレスはユニーク制約が設定されています。

3.  **データベース接続情報の編集**:
    `db_config.php` ファイルを開き、お使いのMySQL環境に合わせてデータベース接続情報を編集します。
    ```php
    <?php
    // db_config.php
    $host = 'localhost';        // MySQLサーバーのホスト名 (通常は 'localhost' または '127.0.0.1')
    $dbname = 'test_db';        // 上記で作成したデータベース名
    $user = 'root';             // MySQLユーザー名
    $pass = 'root';             // MySQLパスワード (MAMPのデフォルトは 'root')

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        // エラーモードを例外に設定
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // エラー発生時はJSONでエラーメッセージを返し、処理を終了
        header('Content-Type: application/json'); // これがないとクライアント側でJSONとしてパースできない場合がある
        die(json_encode(['error' => 'DB接続失敗: ' . $e->getMessage()]));
    }
    ```
    特に `$user` と `$pass` をご自身のMySQL環境の認証情報に合わせてください。

### 5.3. ファイルの配置

提供されたHTMLファイル内のJavaScript (`form.html`, `list_with_edit.html`) は、PHP APIエンドポイント（例: `add_user.php`）を `http://localhost:8888/add_user.php` のような絶対URLで参照しています。これは、PHPファイルがWebサーバーのルートディレクトリ (`http://localhost:8888/`) 直下に配置されていることを前提としています。

**最も簡単に動作させる推奨方法は、提供された全てのファイル (`form.html`, `add_user.php` など) をWebサーバーのドキュメントルート（例: MAMPの場合は `MAMP/htdocs/`、XAMPPの場合は `xampp/htdocs/`）に直接コピーすることです。**

```
htdocs/  (またはWebサーバーのドキュメントルート)
├── form.html
├── list_with_edit.html
├── add_user.php
├── users.php
├── update_user.php
├── delete_user.php
└── db_config.php
```

この配置により、各ページへのアクセスURLは以下のようになります（ポート番号 `8888` はMAMP Apacheのデフォルト例です。ご自身の環境に合わせてください）。
-   ユーザー登録フォーム: `http://localhost:8888/form.html`
-   ユーザー一覧ページ: `http://localhost:8888/list_with_edit.html`

もしサブディレクトリに配置したい場合は、HTMLファイル内の `fetch` APIのURL (`http://localhost:8888/...`) を手動で修正する必要があります（例: `http://localhost:8888/your_subdir/add_user.php` や相対パス `add_user.php`）。

## 6. 使用方法

1.  **ユーザー登録**:
    ブラウザで `http://localhost:[ポート番号]/form.html` にアクセスします。
    「名前」と「メール」を入力し、「送信」ボタンをクリックします。
    フォームの下に「✅ 登録成功！」または「❌ 登録失敗」のメッセージが表示されます。

2.  **ユーザー一覧、編集、削除**:
    ブラウザで `http://localhost:[ポート番号]/list_with_edit.html` にアクセスします。
    登録されているユーザーの一覧がテーブル形式で表示されます。
    -   **編集**: 各ユーザー行の「✏️ 編集」ボタンをクリックすると、名前とメールアドレスが入力フィールドに変わります。情報を編集後、「💾 保存」ボタンをクリックすると情報が更新されます。成功するとアラートが表示され、一覧が再読み込みされます。
    -   **削除**: 各ユーザー行の「🗑 削除」ボタンをクリックすると、確認ダイアログが表示されます。「OK」を選択するとユーザーが削除され、一覧が再読み込みされます。

## 7. 注意点と既知の問題

-   **セキュリティ**:
    -   このシステムは学習用であり、本番環境で利用するための十分なセキュリティ対策（XSS防止のためのエスケープ処理、CSRF対策、より厳密な入力バリデーション、パスワードポリシーなど）は施されていません。
    -   `db_config.php` にデータベースの認証情報が直接記述されています。本番環境では、このファイルのパーミッションを適切に設定し、不正アクセスから保護してください。
-   **エラーハンドリング**: PHP側では基本的なエラーメッセージをJSONで返しますが、クライアント側での表示は簡素です。
-   **URLのハードコーディング**: HTMLファイル内のJavaScript `fetch` APIのURLが `http://localhost:8888/` でハードコーディングされています。環境に合わせて修正が必要になる場合があります（上記「5.3. ファイルの配置」参照）。

## 8. 今後の改善案 (任意)

-   クライアントサイドおよびサーバーサイドでの入力バリデーションの強化。
-   パスワードフィールドとパスワードハッシュ化による安全な認証機能の追加。
-   ユーザー数が多い場合のページネーション機能の実装。
-   より詳細なエラーハンドリングとユーザーフレンドリーなフィードバック。
-   XSS (クロスサイトスクリプティング) 対策の徹底 (例: `htmlspecialchars` の適切な使用)。
-   API URLの相対パス化によるポータビリティの向上。
-   RESTful API設計への準拠。
-   CSSフレームワークの導入によるUI改善。

---
これで、現在のファイル構成に合わせた `README.md` となりました。ご確認ください。
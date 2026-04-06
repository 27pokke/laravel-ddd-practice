# Laravel + PHP で学ぶ DDD / TDD ロードマップと現在の進捗

## 目的
Laravel + PHP を使って、DDD を実践しながら学ぶ。  
あわせて、TDD によって仕様を先に固定し、設計を少しずつ改善していく。

---

## 全体ロードマップ

### Phase 0: 開発土台を整える
目的:
- Laravel の最小アプリを立ち上げる
- テスト実行環境を使えるようにする
- コード整形・静的解析の土台を入れる

やること:
- Laravel プロジェクト作成
- Git / GitHub 管理
- Pest 導入
- Pint 導入
- PHPStan 導入

完了条件:
- `php artisan test` が通る
- `./vendor/bin/pint` が動く
- `./vendor/bin/phpstan analyse` が動く

---

### Phase 1: Laravel の普通の CRUD を作る
目的:
- まずは Laravel 標準の流れでアプリを1つ動かす
- DDD を入れる前に「どこが苦しくなるか」を体感する

題材:
- 記事投稿アプリ

対象機能:
- 一覧表示
- 作成
- 公開
- 削除

完了条件:
- `/articles` で一覧表示できる
- 記事を投稿できる
- 記事を公開できる
- 記事を削除できる

---

### Phase 2: TDD で仕様を固定する
目的:
- 仕様を先にテストで表現する
- 「実装が動く」ではなく「仕様を満たしている」を保証する

#### 課題1: store の仕様を固定する
対象:
- `StoreArticleTest`

仕様:
- 今日の公開予定日なら記事を投稿できる
- 公開予定日なしでも記事を投稿できる
- 過去日付の公開予定日では記事を投稿できない
- タイトルが空なら記事を投稿できない

完了条件:
- `StoreArticleTest` の4本が通る

#### 課題2: publish の仕様を固定する
対象:
- `PublishArticleTest`

仕様:
- 下書き記事は公開できる
- 公開済み記事は再度公開できない

完了条件:
- `PublishArticleTest` の2本が通る

---

### Phase 3: DDD の入口として型と振る舞いを寄せる
目的:
- 文字列や Controller 直書きのルールを減らす
- 「意味のある概念」に寄せ始める

#### 課題3: `status` を enum 化する
対象:
- `ArticleStatus` enum
- `Article` モデルの cast
- Controller / Blade の status 文字列置換

狙い:
- `'draft'`, `'published'` のマジックストリングを減らす
- `status` を意味ある型として扱う

#### 課題4: `publish()` を Article の振る舞いへ寄せる
対象:
- `Article::publish()`
- `ArticleController@publish()`

狙い:
- Controller が状態遷移の詳細を知らないようにする
- `Article` 自身が公開可能かを判断する

---

### Phase 4: Unit Test でドメインルールを固定する
目的:
- HTTP を通さずに、業務ルールだけを直接テストする
- Feature Test と Unit Test の役割を分ける

#### 課題5: `Article` の Unit Test を追加する
対象:
- `tests/Unit/ArticleTest.php`

仕様:
- 下書き記事は作成できる
- 下書き記事は公開できる
- 公開済み記事は再度公開できない

狙い:
- `Article` の振る舞いを直接テストする
- 状態遷移ルールを HTTP から切り離す

---

### Phase 5: Value Object / UseCase へ進む
目的:
- 入力値や状態遷移の意味をより明確にする
- Laravel の都合とドメインの都合を分け始める

課題:
- `PublishDate` を Value Object 化する
- `ArticleTitle` を Value Object 化する
- `CreateArticleUseCase`
- `PublishArticleUseCase`
- 専用例外で失敗理由を表現する

この段階で考えること:
- `publish_date` は単なる日付か
- `title` は単なる文字列か
- `Article` の生成や公開はどこが責務を持つべきか
- Eloquent Model をどこまでドメインに寄せるか

---

### Phase 6: アプリケーション層と永続化の境界を考える
目的:
- Controller / UseCase / Eloquent の責務をさらに整理する
- 「どこまで Laravel 依存を許すか」を考え始める

候補:
- `index` も `ListArticlesUseCase` に出す
- `destroy` も UseCase 化する
- Repository を導入するか整理する
- Eloquent Model とドメインモデルを分けるか検討する

---

## 現在の進捗

### 完了済み

#### 開発環境
- Laravel プロジェクト作成済み
- GitHub / VSCode 利用開始済み
- Pest 導入済み
- テスト実行できる状態
- Pint / PHPStan の導入方針整理済み

#### Article 基本機能
- 記事一覧
- 記事投稿
- 記事公開
- 記事削除

#### Feature Test
- `StoreArticleTest` 4本すべて通過
    - 今日の公開予定日なら記事を投稿できる
    - 公開予定日なしでも記事を投稿できる
    - 過去日付の公開予定日では記事を投稿できない
    - タイトルが空なら記事を投稿できない

- `PublishArticleTest` 2本すべて通過
    - 下書き記事は公開できる
    - 公開済み記事は再度公開できない

#### Entity / Enum / ドメイン振る舞い
- `ArticleStatus` enum 導入済み
- `Article` モデルで enum cast 利用中
- `Article::publish()` 導入済み
- `Article::draft()` 導入済み
- `ArticleController@publish()` から `Article::publish()` を直接呼ぶ構成を卒業し、UseCase 経由に移行済み

#### Value Object
- `PublishDate` 導入済み
    - 文字列から生成できる
    - 過去日付は例外にする
- `ArticleTitle` 導入済み
    - 文字列から生成できる
    - 前後空白をトリムする
    - 空文字や255文字超過は例外にする

#### 専用例外
- `ArticleAlreadyPublishedException`
- `PastPublishDateException`
- `InvalidArticleTitleException`

#### UseCase
- `CreateArticleUseCase` 導入済み
- `PublishArticleUseCase` 導入済み
- Controller は validate と例外のマッピングに寄せ、作成・公開の処理本体は UseCase に移行済み

#### Unit Test
- `tests/Unit/ArticleTest.php`
    - 下書き記事は作成できる
    - 下書き記事は公開できる
    - 公開済み記事は再度公開できない

- `tests/Unit/PublishDateTest.php`
    - 文字列から公開予定日を作れる
    - 過去の日付を指定すると例外が投げられる

- `tests/Unit/ArticleTitleTest.php`
    - 文字列から記事タイトルを作成できる
    - 前後の空白がトリムされる
    - 空文字列なら例外
    - 255文字超過なら例外

- `tests/Unit/CreateArticleUseCaseTest.php`
    - 記事を作成できる

- `tests/Unit/PublishArticleUseCaseTest.php`
    - 記事を公開できる
    - 公開済み記事は再度公開できない

#### 現在のテスト結果
- `php artisan test`
- 20 tests passed / 33 assertions

---

## 現時点の実装イメージ

### Feature Test
- HTTP レベルの仕様を固定する
- ユーザーから見た振る舞いを保証する

### Unit Test
- `Article`
- `ArticleTitle`
- `PublishDate`
- UseCase

を HTTP から切り離して直接検証する

### 現在の設計状態
- まだ純DDDではない
- ただし、Controller からドメインルールをかなり追い出せている
- 「文字列」から `enum` / `Value Object`
- 「Controller の処理」から `UseCase`
- 「汎用例外」から「専用例外」

へ寄せるところまでは進んでいる

### いま残っている Laravel 依存
- 一覧取得は Controller が Eloquent を直接触っている
- 削除も Controller が Eloquent を直接触っている
- 永続化は UseCase 内で `save()` を使っている
- `Article` はまだ Eloquent Model のままドメインモデルも兼ねている

---

## 次にやること

### 優先度高
1. `ListArticlesUseCase` を導入して `ArticleController@index` を薄くする
2. `DeleteArticleUseCase` を導入して `destroy` も UseCase に寄せる
3. Controller が「HTTP を受けて UseCase を呼ぶだけ」に近づくよう整理する
4. すべてのテストを再実行して通す

### その次
5. Repository を導入するか検討する
6. Eloquent Model とドメインモデルを分けるか検討する
7. `Article` 一覧取得を Query / Read Model として分離するか考える

---

## 次回再開時の入り口
再開したら、まず以下を確認する。

- `php artisan test`
- `app/Http/Controllers/ArticleController.php`
- `app/UseCases/CreateArticleUseCase.php`
- `app/UseCases/PublishArticleUseCase.php`
- `app/Models/Article.php`

次の主題:
- `index` と `destroy` の責務を Controller から外す
- UseCase の揃え方を考える
- 永続化の境界をどう引くか整理する

---

## メモ
この学習は「最初から純DDDを完成させる」のではなく、  
Laravel の実装から始めて、痛みが見えたところを DDD で切り出していく進め方を取っている。

ここまでで、

- ドメインの振る舞い
- Value Object
- 専用例外
- UseCase
- UseCase の Unit Test

までは導入できた。

そのため、次は「読み取り」「削除」「永続化の境界」をどう整理するかが主題になる。

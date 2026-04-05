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

#### 課題5: `Article::publish()` の Unit Test を追加する
対象:
- `tests/Unit/ArticleTest.php`

仕様:
- 下書き記事は公開できる
- 公開済み記事は再度公開できない

次の改善候補:
- `Article::publish()` で `update()` を使わず、状態変更だけを行う
- 永続化は Controller か UseCase 側へ出す

---

### Phase 5: Value Object / UseCase へ進む
目的:
- 入力値や状態遷移の意味をより明確にする
- Laravel の都合とドメインの都合を分け始める

候補:
- `PublishDate` を Value Object 化する
- `CreateArticleUseCase`
- `PublishArticleUseCase`

この段階で考えること:
- `publish_date` は単なる日付か
- `Article` の生成や公開はどこが責務を持つべきか
- Eloquent Model をどこまでドメインに寄せるか

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

#### テスト
- `StoreArticleTest` 4本すべて通過
    - 今日の公開予定日なら記事を投稿できる
    - 公開予定日なしでも記事を投稿できる
    - 過去日付の公開予定日では記事を投稿できない
    - タイトルが空なら記事を投稿できない

- `PublishArticleTest` 2本すべて通過
    - 下書き記事は公開できる
    - 公開済み記事は再度公開できない

#### DDD 入口
- `ArticleStatus` enum 導入済み
- `Article` モデルで enum cast 利用中
- Blade でも enum を使う方向へ修正済み
- `ArticleController@publish()` から `Article::publish()` を呼ぶ構成へ移行済み

#### Unit Test
- `tests/Unit/ArticleTest.php` 作成済み
- 以下の2本を追加済み
    - 下書き記事は公開できる
    - 公開済み記事は再度公開できない

---

## 現時点の実装イメージ

### Feature Test
- HTTP レベルの仕様を固定する
- ユーザーから見た振る舞いを保証する

### Unit Test
- `Article` の振る舞いを直接確認する
- 状態遷移ルールを HTTP から切り離して検証する

### 現在の設計状態
- まだ純DDDではない
- ただし、Controller からドメインルールを少しずつ追い出している
- 「文字列」から「enum」
- 「Controller の条件分岐」から「Article の振る舞い」
へ寄せ始めている

---

## 次にやること

### 優先度高
1. `ArticleTest` を通しつつ、`Article::publish()` の責務を整理する
2. `Article::publish()` の中で `update()` ではなく状態変更だけを行う形へ寄せる
3. Controller 側で `save()` する形へ寄せる
4. すべてのテストを再実行して通す

### その次
5. `DomainException` を専用例外に置き換えるか検討する
6. `publish_date` を Value Object 候補として整理する
7. `CreateArticleUseCase` / `PublishArticleUseCase` を導入するか検討する

---

## 次回再開時の入り口
再開したら、まず以下を確認する。

- `php artisan test`
- `tests/Unit/ArticleTest.php`
- `app/Models/Article.php`
- `app/Http/Controllers/ArticleController.php`

次の主題:
- `Article::publish()` をよりドメイン寄りにする
- 永続化と振る舞いの責務を少し分離する

---

## メモ
この学習は「最初から純DDDを完成させる」のではなく、  
Laravel の実装から始めて、痛みが見えたところを DDD で切り出していく進め方を取っている。

そのため、現在の状態は途中段階として正しい。

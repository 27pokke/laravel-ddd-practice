# Laravel DDD Practice - Article Posting

Laravel + PHP で DDD / TDD を学ぶための練習用プロジェクトです。  
題材はタスク管理から、Note のような記事投稿アプリへ寄せています。

ただし、学習の進め方そのものは変えていません。  
「普通の CRUD から始めて、テストで仕様を固定し、enum やモデルの振る舞いへ寄せていく」という流れをそのまま残しています。

## 今の題材

現在は「記事投稿アプリ」を題材にしています。

- 記事一覧を表示する
- 記事を投稿する
- 下書き記事を公開する
- 記事を削除する

現段階では、学習の進捗を増やしすぎないために機能を最小限にしています。  
そのため、記事本文のような要素はまだ入れず、タイトル・公開状態・公開予定日を中心に扱っています。

## 学習ポイント

- Laravel 標準の CRUD をまず作る
- Feature Test で HTTP レベルの仕様を固定する
- `ArticleStatus` enum で状態を型として扱う
- `Article::publish()` に公開ルールを寄せる
- 次の段階で Value Object / UseCase へ進めるようにする

## 画面とルート

- 一覧画面: `/articles`
- 投稿: `POST /articles`
- 公開: `PATCH /articles/{article}/publish`
- 削除: `DELETE /articles/{article}`

## セットアップ

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

ブラウザで `/articles` を開くと、記事一覧と投稿フォームを確認できます。

## テスト

```bash
php artisan test
```

Feature Test と Unit Test を使って、現在の学習段階の仕様を固定しています。

## 学習メモ

- DDD の概要: [Doc/DDD.md](./Doc/DDD.md)
- TDD の概要: [Doc/TDD.md](./Doc/TDD.md)

## 進捗メモ

学習ロードマップと現在の進捗は [laravel-ddd-roadmap-progress.md](./laravel-ddd-roadmap-progress.md) にまとめています。  
題材は記事投稿に変えていますが、フェーズ構成と進捗の段階感は維持しています。

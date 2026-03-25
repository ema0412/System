# 勤怠管理システム (Laravel 10 / PHP 8.0 / MySQL 8.0)

このリポジトリは、要件に沿って勤怠管理システムの実装ベースを作成したものです。

## 実装済みポイント
- Fortifyによる一般ユーザーの会員登録・ログイン・メール認証導線
- 管理者ログイン導線 (`/admin/login`)
- 勤怠打刻（出勤/休憩入/休憩戻/退勤）
- 勤怠一覧・詳細・修正申請
- 承認待ち/承認済み一覧と管理者承認フロー
- 管理者向け日次勤怠一覧、スタッフ一覧、スタッフ別月次勤怠、CSV出力
- FormRequestによる主要バリデーションと日本語メッセージ

## セットアップ手順（想定）
1. Laravel 10 プロジェクトを作成し、このコードを配置
2. パッケージ導入
   - `composer require laravel/fortify`
3. `.env` でDBとメール設定
   - `DB_CONNECTION=mysql`
   - `DB_HOST=mysql`
   - `MAIL_MAILER=smtp`
   - `MAIL_HOST=mailhog`
   - `MAIL_PORT=1025`
4. マイグレーション
   - `php artisan migrate`
5. Fortifyプロバイダ登録・ミドルウェア登録
   - `App\\Providers\\FortifyServiceProvider::class`
   - `admin` ミドルウェアに `App\\Http\\Middleware\\EnsureAdmin::class`

## 主要URL
- 一般: `/register`, `/login`, `/attendance`, `/attendance/list`, `/attendance/detail/{id}`
- 申請: `/stamp_correction_request/list`, `/stamp_correction_request/approve/{id}`
- 管理: `/admin/login`, `/admin/attendance/list`, `/admin/staff/list`

# Reservation Management System

[![Build Status](https://travis-ci.com/technote-space/laravel-reservation-management-system.svg?branch=master)](https://travis-ci.com/technote-space/laravel-reservation-management-system)
[![Coverage Status](https://coveralls.io/repos/github/technote-space/laravel-reservation-management-system/badge.svg?branch=master)](https://coveralls.io/github/technote-space/laravel-reservation-management-system?branch=master)
[![CodeFactor](https://www.codefactor.io/repository/github/technote-space/laravel-reservation-management-system/badge)](https://www.codefactor.io/repository/github/technote-space/laravel-reservation-management-system)
[![License: GPL v2+](https://img.shields.io/badge/License-GPL%20v2%2B-blue.svg)](http://www.gnu.org/licenses/gpl-2.0.html)

[TechCommit](https://www.tech-commit.jp/)

## 概要
ホテルの予約管理システム

## スクリーンショット
### Login
<img src="https://raw.githubusercontent.com/technote-space/laravel-reservation-management-system/images/login.png" width="500px"/>

### Dashboard
<img src="https://raw.githubusercontent.com/technote-space/laravel-reservation-management-system/images/dashboard.png" width="500px"/>

### CRUD
<img src="https://raw.githubusercontent.com/technote-space/laravel-reservation-management-system/images/list.png" width="500px"/>
<img src="https://raw.githubusercontent.com/technote-space/laravel-reservation-management-system/images/edit.png" width="500px"/>

## 仕様
- 最大4泊5日
- 1部屋に最大2人
- 貸出単位は部屋毎
- 1予約者につき1部屋
- 支払いは利用当日に前払い
  - 2000円/1部屋1泊

## 要件
- 現在の予約状況の確認
- 予約登録
- 利用者の管理
  - 名前/住所/電話番号
- 月毎の売り上げ金額の確認

## データ設計
### 部屋 (rooms)
- 部屋名 (name)
- 最大人数 (number)
- 一泊の金額 (price)
### 利用者 (guests)
### 利用者詳細 (guest_details)
- 利用者ID (guest_id)
- 名前 (name)
- カナ名 (name_kana)
- 住所
  - 郵便番号 (zip_code)
  - 住所 (address)
- 電話番号 (phone)
### 予約 (reservations)
- 利用者ID (guest_id)
- 部屋ID (room_id)
- 利用開始日 (start_date)
- 利用終了日(1泊の場合 = 利用開始日) (end_date)
- 利用人数 (number)
### 管理者 (admins)
- 名前 (name)
- メールアドレス (email)
- メール認証完了日時 (email_verified_at)
- パスワード (password)
- パスワード再発行トークン (remember_token)
### パスワードリセット (password_resets)
- メールアドレス (email)
- トークン (token)

## 構成
### 言語・フレームワーク
- PHP（Laravel）
  - API サーバとして利用
- JavaScript（Vue.js）
### Lint
- PHP
  - PHPCS
  - PHPMD
- JavaScript
  - ESLint
- CSS
  - stylelint
### テスト
- PHP
  - PHPUnit（単体テスト）
  - Laravel Dusk（e2e）
- JavaScript
  - Jest（単体テスト）
  - Laravel Dusk（e2e）
### CI
- Travis CI
  - Lint
  - テスト
  - Deploy
    - GitHub Releases
    - GitHub Pages
### デザインフレームワーク
- Vuetify
### その他
- 多言語化

## Author
[GitHub (Technote)](https://github.com/technote-space)  
[Blog](https://technote.space)

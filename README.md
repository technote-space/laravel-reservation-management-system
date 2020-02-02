# Reservation Management System

[![CI Status](https://github.com/technote-space/laravel-reservation-management-system/workflows/CI/badge.svg)](https://github.com/technote-space/laravel-reservation-management-system/actions)
[![Build Status](https://travis-ci.com/technote-space/laravel-reservation-management-system.svg?branch=master)](https://travis-ci.com/technote-space/laravel-reservation-management-system)
[![codecov](https://codecov.io/gh/technote-space/laravel-reservation-management-system/branch/master/graph/badge.svg)](https://codecov.io/gh/technote-space/laravel-reservation-management-system)
[![CodeFactor](https://www.codefactor.io/repository/github/technote-space/laravel-reservation-management-system/badge)](https://www.codefactor.io/repository/github/technote-space/laravel-reservation-management-system)
[![License: GPL v2+](https://img.shields.io/badge/License-GPL%20v2%2B-blue.svg)](http://www.gnu.org/licenses/gpl-2.0.html)

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
<details>
<summary>Details</summary>

- [概要](#%E6%A6%82%E8%A6%81)
- [スクリーンショット](#%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88)
  - [Login](#login)
  - [Dashboard](#dashboard)
  - [CRUD](#crud)
- [要件](#%E8%A6%81%E4%BB%B6)
- [仕様](#%E4%BB%95%E6%A7%98)
- [データ設計](#%E3%83%87%E3%83%BC%E3%82%BF%E8%A8%AD%E8%A8%88)
  - [部屋 (rooms)](#%E9%83%A8%E5%B1%8B-rooms)
  - [利用者 (guests)](#%E5%88%A9%E7%94%A8%E8%80%85-guests)
  - [利用者詳細 (guest_details)](#%E5%88%A9%E7%94%A8%E8%80%85%E8%A9%B3%E7%B4%B0-guest_details)
  - [予約 (reservations)](#%E4%BA%88%E7%B4%84-reservations)
  - [予約詳細 (reservation_details)](#%E4%BA%88%E7%B4%84%E8%A9%B3%E7%B4%B0-reservation_details)
  - [管理者 (admins)](#%E7%AE%A1%E7%90%86%E8%80%85-admins)
- [構成](#%E6%A7%8B%E6%88%90)
  - [言語・フレームワーク](#%E8%A8%80%E8%AA%9E%E3%83%BB%E3%83%95%E3%83%AC%E3%83%BC%E3%83%A0%E3%83%AF%E3%83%BC%E3%82%AF)
  - [Lint](#lint)
  - [テスト](#%E3%83%86%E3%82%B9%E3%83%88)
  - [CI](#ci)
  - [デザインフレームワーク](#%E3%83%87%E3%82%B6%E3%82%A4%E3%83%B3%E3%83%95%E3%83%AC%E3%83%BC%E3%83%A0%E3%83%AF%E3%83%BC%E3%82%AF)
  - [その他](#%E3%81%9D%E3%81%AE%E4%BB%96)
- [Demonstration](#demonstration)
- [Author](#author)

</details>
<!-- END doctoc generated TOC please keep comment here to allow auto update -->

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

## 要件
- 部屋の管理
- 各部屋の現在の予約状況の確認
- 予約登録
- 利用者の管理
  - 名前/住所/電話番号
- 月毎の売り上げ金額の確認

## 仕様
- 貸出単位は部屋毎
- 1予約者につき1部屋
- 支払いは利用当日に前払い

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
- チェックアウト時間 (checkout)
### 予約詳細 (reservation_details)
- 利用人数 (number)
- 支払金額 (payment)
- 部屋名 (room_name)
- 利用者名 (guest_name)
- 利用者カナ名 (guest_name_kana)
- 利用者郵便番号 (guest_zip_code)
- 利用者住所 (guest_address)
- 利用者電話番号 (guest_phone)
### 管理者 (admins)
- 名前 (name)
- メールアドレス (email)
- パスワード (password)

## 構成
### 言語・フレームワーク
- PHP（Laravel）
  - API サーバとして利用
- JavaScript（Vue.js）
### Lint
- PHP
  - [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer)
  - [PHPMD](https://phpmd.org/)
- JavaScript
  - [ESLint](https://eslint.org/)
- CSS
  - [stylelint](https://github.com/stylelint/stylelint)
### テスト
- PHP
  - [PHPUnit](https://phpunit.de/)（単体テスト）
  - [Laravel Dusk](https://github.com/laravel/dusk)（e2e）
- JavaScript
  - [Jest](https://jestjs.io/)（単体テスト）
  - [Laravel Dusk](https://github.com/laravel/dusk)（e2e）
### CI
- [Travis CI](https://travis-ci.com/)
  - Lint
  - テスト
- [GitHub Actions](https://help.github.com/ja/actions)
  - Lint
  - テスト
  - Deploy
    - GitHub Pages
### デザインフレームワーク
- [Vuetify](https://vuetifyjs.com/)
### その他
- 多言語化
- Vuex, SPA
- [FullCalendar](https://fullcalendar.io/)
- [Chart.js](https://www.chartjs.org/)

## Demonstration
[GitHub Pages](https://technote-space.github.io/laravel-reservation-management-system)  
- ログイン情報
  - email: test@example.com
  - password: test1234
- APIはモックなので実際の動作と差異があります
  - データの並び順
  - データ検索
  - バリデーションなし

[Deployed](https://reservation.technote.space)
 - Basic認証の情報が必要な方はSlack等でお問い合わせください

## Author
[GitHub (Technote)](https://github.com/technote-space)  
[Blog](https://technote.space)

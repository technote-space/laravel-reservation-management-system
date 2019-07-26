# Reservation Management System

[![License: GPL v2+](https://img.shields.io/badge/License-GPL%20v2%2B-blue.svg)](http://www.gnu.org/licenses/gpl-2.0.html)

[TechCommit](https://www.tech-commit.jp/)

## 概要
ホテルの予約管理システム

## スクリーンショット

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
### 部屋
- 最大人数
- 一泊の金額
### 利用者
- 名前
- 住所
- 電話番号
- 利用人数
### 予約
- 利用者ID
- 部屋ID
- 利用開始日
- 利用終了日(1泊の場合 = 利用開始日)
- 利用人数

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

## Author
[GitHub (Technote)](https://github.com/technote-space)  
[Blog](https://technote.space)

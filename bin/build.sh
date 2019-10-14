#!/usr/bin/env bash

set -e

echo ""
echo ">> Build"
yarn install
composer build
yarn --prod install

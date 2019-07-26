#!/usr/bin/env bash

set -e

current=$(cd $(dirname $0);
pwd)
source ${current}/../variables.sh

echo ""
echo ">> Run composer migrate"
composer migrate

echo ""
echo ">> Run composer phpunit"
composer phpunit

ls -la ${TRAVIS_BUILD_DIR}/coverage/php/clover.xml
echo ""
echo ">> Run composer coveralls"
composer coveralls-php

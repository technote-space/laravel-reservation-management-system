#!/usr/bin/env bash

set -e

current=$(cd $(dirname $0);
pwd)
source ${current}/../variables.sh

echo ""
echo ">> Run composer jest"
composer jest

ls -la ${TRAVIS_BUILD_DIR}/coverage/js/lcov.info
echo ""
echo ">> Run yarn coveralls."
composer coveralls-js

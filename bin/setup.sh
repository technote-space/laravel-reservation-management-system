#!/usr/bin/env bash

set -e

current=$(cd $(dirname $0);
pwd)

if type yarn >/dev/null 2>&1; then
    yarn install
    yarn dev
else
    echo "yarn command required."
    echo -e 'Run\e[32;1m npm install -g yarn\e[m to install.'
    exit
fi

composer install --no-interaction --prefer-dist --no-suggest

bash ${current}/prepare/install-chrome.sh

if [[ ! -f ${current}/../.env ]]; then
    cp ${current}/../.env.sample ${current}/../.env
fi

echo -e "Please run\e[32;1m composer migrate:seed\e[m after prepare .evn file."

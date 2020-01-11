#!/usr/bin/env bash

set -e

current=$(
  # shellcheck disable=SC2046
  cd $(dirname "$0")
  pwd
)

if type yarn >/dev/null 2>&1; then
  composer prepare
else
  echo "yarn command required."
  echo -e 'Run\e[32;1m npm install -g yarn\e[m to install.'
  exit 1
fi

if [[ ! -f "${current}"/../.env ]]; then
  cp "${current}"/../.env.sample "${current}"/../.env
fi

composer m
composer build

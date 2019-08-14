#!/usr/bin/env bash

set -e

current=$(
  # shellcheck disable=SC2046
  cd $(dirname "$0")
  pwd
)

if type yarn >/dev/null 2>&1; then
  yarn --prod install
else
  echo "yarn command required."
  echo -e 'Run\e[32;1m npm install -g yarn\e[m to install.'
  exit
fi

composer build

if [[ -z "${CI}" ]]; then
  if [[ ! -f "${current}"/../.env ]]; then
    cp "${current}"/../.env.sample "${current}"/../.env
  fi

  echo -e "Please run\e[32;1m composer m\e[m after prepare .evn file."
fi

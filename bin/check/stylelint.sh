#!/usr/bin/env bash

set -e

current=$(
  # shellcheck disable=SC2046
  cd $(dirname "$0")
  pwd
)
# shellcheck disable=SC1090
source "${current}"/../variables.sh

echo ""
echo ">> Setup"
composer prepare:js

echo ""
echo ">> Run composer stylelint"
if [[ -n "${GIT_DIFF}" ]]; then
  # shellcheck disable=SC2046
  "${WORKSPACE}"/node_modules/.bin/stylelint --cache $(eval echo "${GIT_DIFF}")
else
  if [[ ! -f "${WORKSPACE}/package.json" ]] || [[ $(< "${WORKSPACE}/package.json" jq -r '.scripts.stylelint' | wc -l) != 1 ]]; then
    echo "yarn stylelint command is invalid."
  else
    yarn --cwd "${WORKSPACE}" stylelint
  fi
fi

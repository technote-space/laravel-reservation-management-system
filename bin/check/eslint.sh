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
echo ">> Run composer eslint"
if [[ -n "${GIT_DIFF_FILTERED}" ]] && [[ -z "${MATCHED_FILES}" ]]; then
  # shellcheck disable=SC2046
  "${WORKSPACE}"/node_modules/.bin/eslint --cache $(eval echo "${GIT_DIFF_FILTERED}")
else
  if [[ ! -f "${WORKSPACE}/package.json" ]] || [[ $(< "${WORKSPACE}/package.json" jq -r '.scripts.eslint' | wc -l) != 1 ]]; then
    echo "yarn eslint command is invalid."
  else
    yarn --cwd "${WORKSPACE}" eslint
  fi
fi

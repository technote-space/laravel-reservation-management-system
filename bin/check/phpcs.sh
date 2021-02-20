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
composer prepare:php

echo ""
echo ">> Run composer phpcs"
if [[ -n "${GIT_DIFF_FILTERED}" ]] && [[ -z "${MATCHED_FILES}" ]]; then
  # shellcheck disable=SC2046
  "${WORKSPACE}"/vendor/bin/phpcs --cache --standard="${WORKSPACE}/phpcs.xml" $(eval echo "${GIT_DIFF_FILTERED}")
else
  "${WORKSPACE}"/vendor/bin/phpcs --cache --standard="${WORKSPACE}/phpcs.xml"
fi

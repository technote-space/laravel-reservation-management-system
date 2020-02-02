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
echo ">> Run composer phpmd"
if [[ -n "${GIT_DIFF}" ]]; then
  "${WORKSPACE}"/vendor/bin/phpmd "$(eval echo "${GIT_DIFF}")" ansi "${WORKSPACE}/phpmd.xml" --exclude database/migrations/
else
  targets="./app/"
  if [[ -d "${WORKSPACE}/configs" ]]; then
    targets="${targets},./configs/"
  fi
  if [[ -d "${WORKSPACE}/config" ]]; then
    targets="${targets},./config/"
  fi
  if [[ -d "${WORKSPACE}/tests" ]]; then
    targets="${targets},./tests/"
  fi
  if [[ -d "${WORKSPACE}/resources" ]]; then
    targets="${targets},./resources/"
  fi
  if [[ -d "${WORKSPACE}/routes" ]]; then
    targets="${targets},./routes/"
  fi
  if [[ -d "${WORKSPACE}/database/factories" ]]; then
    targets="${targets},./database/factories/"
  fi
  if [[ -d "${WORKSPACE}/database/seeds" ]]; then
    targets="${targets},./database/seeds/"
  fi
  "${WORKSPACE}"/vendor/bin/phpmd "${targets}" ansi "${WORKSPACE}/phpmd.xml" --exclude database/migrations/
fi

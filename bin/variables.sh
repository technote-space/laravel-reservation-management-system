#!/usr/bin/env bash

set -e

if [[ -z "${WORKSPACE}" ]]; then
  if [[ -z "${TRAVIS_BUILD_DIR}" ]]; then
    echo "<WORKSPACE> is required"
    exit 1
  fi
  WORKSPACE=${TRAVIS_BUILD_DIR}
fi

WORK_DIR=${WORKSPACE}/.work
# shellcheck disable=SC2034
PACKAGE_DIR=${WORK_DIR}/packages
# shellcheck disable=SC2034
GH_PAGES_DIR=${WORKSPACE}/public/gh-pages

if [[ -n "${TRAVIS_BUILD_NUMBER}" ]]; then
  # shellcheck disable=SC2034
  COMMIT_MESSAGE="feat: Update version data (Travis build: ${TRAVIS_BUILD_WEB_URL})"
else
  # shellcheck disable=SC2034
  COMMIT_MESSAGE="feat: Update version data"
fi

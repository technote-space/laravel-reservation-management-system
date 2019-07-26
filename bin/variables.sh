#!/usr/bin/env bash

set -e

if [[ -z "${TRAVIS_BUILD_DIR}" ]]; then
	echo "<TRAVIS_BUILD_DIR> is required"
	exit 1
fi

WORK_DIR=${TRAVIS_BUILD_DIR}/.work
CACHE_WORK_DIR=${TRAVIS_BUILD_DIR}/.work/cache
GH_WORK_DIR=${CACHE_WORK_DIR}/playground
PACKAGE_DIR=${WORK_DIR}/packages
GH_PAGES_DIR=${TRAVIS_BUILD_DIR}/gh-pages
BIN_DIR=${TRAVIS_BUILD_DIR}/bin

REPO_NAME=${TRAVIS_REPO_SLUG##*/}
if [[ -n "${TRAVIS_BUILD_NUMBER}" ]]; then
	COMMIT_MESSAGE="feat: Update version data (Travis build: ${TRAVIS_BUILD_WEB_URL})"
else
	COMMIT_MESSAGE="feat: Update version data"
fi

#!/usr/bin/env bash

set -e

VERSION=$(google-chrome-stable --version)
VERSION=${VERSION% *}
VERSION=${VERSION##* }
echo ${VERSION%%.*}

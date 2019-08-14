#!/usr/bin/env bash

set -e

current=$(cd $(dirname $0);
pwd)

if type google-chrome-stable >/dev/null 2>&1; then
    bash ${current}/get-chrome-version.sh | xargs -I {} php artisan dusk:chrome-driver {} --env=testing --ansi
else
    echo "[google-chrome-stable] command not found"
fi

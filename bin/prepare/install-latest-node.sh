#!/usr/bin/env bash

set -e

#NODE_JS_VERSION=${NODE_JS_VERSION:-node}
NODE_JS_VERSION=${NODE_JS_VERSION:-11}

export NVM_DIR="${HOME}/.nvm"
curl -o "${NVM_DIR}"/nvm.sh https://raw.githubusercontent.com/nvm-sh/nvm/master/nvm.sh

# shellcheck disable=SC1090
[[ -s "${NVM_DIR}/nvm.sh" ]] && \. "${NVM_DIR}/nvm.sh"                   # This loads nvm
# shellcheck disable=SC1090
[[ -s "${NVM_DIR}/bash_completion" ]] && \. "${NVM_DIR}/bash_completion" # This loads nvm bash_completion

nvm install "${NODE_JS_VERSION}"
nvm alias default "${NODE_JS_VERSION}"

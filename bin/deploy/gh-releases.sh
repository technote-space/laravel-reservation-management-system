#!/usr/bin/env bash

set -e

current=$(
  # shellcheck disable=SC2046
  cd $(dirname "$0")
  pwd
)
# shellcheck disable=SC1090
source "${current}"/../variables.sh

if [[ -z "${RELEASE_FILE}" ]]; then
  echo "<RELEASE_FILE> is required."
  exit 1
fi

echo ""
echo ">> Prepare release files"
rm -rdf ${PACKAGE_DIR}
rm -f ${RELEASE_FILE}
mkdir -p ${PACKAGE_DIR}

targets=()
targets+=("app")
targets+=("bootstrap")
targets+=("config")
targets+=("database")
targets+=("public")
targets+=("resources")
targets+=("routes")
targets+=("vendor")
for target in "${targets[@]}"; do
  cp -r ${TRAVIS_BUILD_DIR}/${target} ${PACKAGE_DIR}/ 2>/dev/null || :
done

targets=()
targets+=(".env.sample")
targets+=("artisan")
targets+=("composer.json")
targets+=("LICENSE")
targets+=("package.json")
targets+=("README.md")
targets+=("server.php")
for target in "${targets[@]}"; do
  cp ${TRAVIS_BUILD_DIR}/${target} ${PACKAGE_DIR}/ 2>/dev/null || :
done

rm -rdf ${PACKAGE_DIR}/vendor/bin
rm -rdf ${PACKAGE_DIR}/resources/js
rm -rdf ${PACKAGE_DIR}/resources/sass

pushd ${PACKAGE_DIR}
echo ""
echo ">> Create zip file."
zip -9 -qr ${TRAVIS_BUILD_DIR}/${RELEASE_FILE} .
pushd

ls -la ${RELEASE_FILE}

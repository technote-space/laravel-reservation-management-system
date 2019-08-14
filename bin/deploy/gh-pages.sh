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
echo ">> Prepare files"
rm -rdf ${GH_PAGES_DIR}
mkdir -p ${GH_PAGES_DIR}

yarn local

cp -a ${TRAVIS_BUILD_DIR}/public/css ${GH_PAGES_DIR}/css
cp -a ${TRAVIS_BUILD_DIR}/public/js ${GH_PAGES_DIR}/js
cp -a ${TRAVIS_BUILD_DIR}/public/fonts ${GH_PAGES_DIR}/fonts
cp ${TRAVIS_BUILD_DIR}/public/favicon.ico ${GH_PAGES_DIR}/

HTML=$(
  cat <<EOS
<!doctype html>
<html lang="ja">
<head>
    ___google_analytics___
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="timezone" content="Asia/Tokyo">
    <title>Reservation Service</title>
    <script src="./js/app.js" defer></script>
    <link rel="icon" href="./favicon.ico">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
    <link href="./css/app.css" rel="stylesheet">
</head>
<body>
    <div id="app"></div>
</body>
</html>
EOS
)

if [[ -n "${GH_PAGES_TRACKING_ID}" ]]; then
  SCRIPT=$(
    cat <<EOS
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=${GH_PAGES_TRACKING_ID}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '${GH_PAGES_TRACKING_ID}');
</script>
EOS
  )
  SCRIPT=$(echo ${SCRIPT} | sed -e 's/\n//g')
  echo "${HTML}" | sed -e "s/___google_analytics___/${SCRIPT//\//\\/}/g" >>${GH_PAGES_DIR}/index.html
  echo "${HTML}" | sed -e "s/___google_analytics___/${SCRIPT//\//\\/}/g" >>${GH_PAGES_DIR}/404.html
else
  echo "${HTML}" | sed -e "/___google_analytics___/d" >>${GH_PAGES_DIR}/index.html
  echo "${HTML}" | sed -e "/___google_analytics___/d" >>${GH_PAGES_DIR}/404.html
fi

if [[ -f ${WORK_DIR}/gh-pages.sh ]]; then
  bash ${WORK_DIR}/gh-pages.sh
fi

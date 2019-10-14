#!/usr/bin/env bash

set -e

if type google-chrome-stable >/dev/null 2>&1; then
  echo "[google-chrome-stable] has been installed"
else
  wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | apt-key add -
  echo 'deb http://dl.google.com/linux/chrome/deb/ stable main' | tee /etc/apt/sources.list.d/google-chrome.list
  apt update -y
  apt install -y google-chrome-stable
fi

#!/bin/sh

# Do you want to execute a specific test suites? this script accept as first paramete the suite name!

if ! lsof -Pi :4444 -sTCP:LISTEN -t >/dev/null; then
	echo " - Run Chromedriver"
	if [ ! -f "/tmp/chromedriver" ]; then
#		wget https://chromedriver.storage.googleapis.com/103.0.5060.53/chromedriver_linux64.zip > /dev/null 2>&1
		wget https://edgedl.me.gvt1.com/edgedl/chrome/chrome-for-testing/117.0.5938.92/mac-arm64/chromedriver-mac-arm64.zip > /dev/null 2>&1
		unzip chromedriver_mac_arm64.zip
	fi
	chromedriver --port=9515 &
fi

echo " - Run Codeception"

sh ./tests/custom.sh

codecept run "$1"

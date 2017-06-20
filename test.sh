#!/bin/sh

BASE_PATH=`dirname $0`

php -S 127.0.0.1:8080 -d always_populate_raw_post_data=-1 -t ${BASE_PATH}/tests/server > /dev/null 2>&1 &
pid=$!
${BASE_PATH}/vendor/bin/phpunit
kill ${pid}
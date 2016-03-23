#!/usr/bin/env bash

filepath=$(cd "$(dirname "$0")"; pwd)
cd $filepath'/../'

chmod -R 777 log

chmod -R 777 admin/assets
chmod -R 777 admin/protected/runtime

chmod -R 777 portal/assets
chmod -R 777 portal/protected/runtime

chmod -R 777 uploads

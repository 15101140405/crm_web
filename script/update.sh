#!/usr/bin/env bash

filepath=$(cd "$(dirname "$0")"; pwd)
cd $filepath'/../'

#rm -rf admin/assets
#rm -rf portal/assets
rm -rf admin/protected/runtime
rm -rf portal/protected/runtime
rm -rf log

mkdir -p admin/assets
mkdir -p portal/assets
mkdir admin/protected/runtime
mkdir portal/protected/runtime
mkdir log

touch admin/assets/.gitkeep
touch portal/assets/.gitkeep
touch admin/protected/runtime/.gitkeep
touch portal/protected/runtime/.gitkeep
touch log/.gitkeep

git pull

chmod +x script/init.sh
script/init.sh
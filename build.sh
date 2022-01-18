#!/bin/bash
read -p "Enter Version : " version
REPO=$PWD;

rm -rf ./standalone-gutenberg;
rm ./rm standalone-gutenberg-*.zip;

mkdir standalone-gutenberg;
#cp -R . standalone-gutenberg;
wp i18n make-pot $REPO $REPO'/languages/standalone-gutenberg.pot' --exclude=".github,.git,node_modules,woofunnels,.gitignore,.gitmodules,gruntfile,license.txt,package.json,package-lock.json,wpml-config.xml,admin/assets,assets,/libraries/action-scheduler"

rsync -av --exclude='.git' --exclude='.babelrc' --exclude='.gitignore' --exclude='.github' --exclude='.gitmodules' --exclude='node_modules' --exclude='build.sh' --exclude='gruntfile.js' --exclude='package.json' --exclude='phpcs.xml' --exclude='package-lock.json' --exclude='admin/frontend/src' --exclude='.prettierrc.js' --exclude='webpack.config.js'  --exclude='webpack.production.config.js' --exclude='webpack.development.config.js' --exclude='webpack.production-source-maps.config.js' --exclude='.eslintrc' --exclude='.babelrc.js' --exclude='.editorconfig' --exclude="jsconfig.json" --exclude="README.md" ./ ./standalone-gutenberg

rm -rf ./standalone-gutenberg/src;

rm -rf ./standalone-gutenberg/standalone-gutenberg;

grep -rl "pc::" ./standalone-gutenberg;
grep -rl "var_dump" ./standalone-gutenberg;
grep -rl "print_r" ./standalone-gutenberg;

zip -r standalone-gutenberg-v$version.zip ./standalone-gutenberg;

rm -rf ./standalone-gutenberg;
#!/usr/bin/env bash

echo "Enter version number: "

read VERSION
jq ".version=\"$VERSION\"" --indent 4 composer.json >composer.json.new
mv composer.json.new composer.json

git add composer.json
git commit -qm "Bump version to $VERSION"
git tag -a -m "Tagging version $VERSION" "$VERSION"

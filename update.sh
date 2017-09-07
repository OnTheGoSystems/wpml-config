#!/bin/bash

rm -f config-index.json
php update.php
if [[ -f config-index.json ]]; then
	aws s3 sync . s3://wpmldebug/wpml-config/ --exclude "update.*" --exclude ".git*" --delete --acl public-read
fi

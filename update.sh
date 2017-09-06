#!/bin/bash

aws s3 sync . s3://wpmlorg/wpml-config/ --exclude "update.*" --exclude ".git*" --delete


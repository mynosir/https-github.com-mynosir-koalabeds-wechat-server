#!/bin/sh
find "/pub/koalabeds-server.kakaday.com/" -name "update_cloudbeds_access_token.lock" -cmin +10 -type f | xargs rm -rf

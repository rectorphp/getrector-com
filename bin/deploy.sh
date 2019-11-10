#!/usr/bin/env bash

echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin
docker pull rector/getrector.org:$TRAVIS_COMMIT
docker tag rector/getrector.org:$TRAVIS_COMMIT rector/getrector.org:latest
docker push rector/getrector.org

eval $(ssh-agent -s)
mkdir -p ~/.ssh
# here is the name of the server we connect to, not final domain
ssh-keyscan -H pehapkari.cz >> ~/.ssh/known_hosts

# create single-line string from private key with https://www.samltool.com/format_privatekey.php
# you need the private key from the remote server on your server (e.g. Digital Ocean)
echo "$DEPLOY_PRIVATE_KEY" | ssh-add - > /dev/null
ssh root@pehapkari.cz "cd /projects/getrector.org && ./run.sh"

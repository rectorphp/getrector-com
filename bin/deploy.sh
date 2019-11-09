#!/usr/bin/env bash

echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin
docker pull rector/getrector.org:$TRAVIS_COMMIT
docker tag rector/getrector.org:$TRAVIS_COMMIT rector/getrector.org:latest
docker push rector/getrector.org

eval $(ssh-agent -s)
mkdir -p ~/.ssh
ssh-keyscan -H getrector.org >> ~/.ssh/known_hosts
echo "$DEPLOY_PRIVATE_KEY" | ssh-add - > /dev/null
ssh root@pehapkari.cz "cd /projects/getrector.org && ./run.sh"

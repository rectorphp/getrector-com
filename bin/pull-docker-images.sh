#!/usr/bin/env bash
docker-compose pull --include-deps
docker pull rector/rector-secured:latest

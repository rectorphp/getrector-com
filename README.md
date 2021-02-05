# [GetRector.org](https://getrector.org)

[![Coverage Status](https://coveralls.io/repos/github/rectorphp/getrector.org/badge.svg?branch=master)](https://coveralls.io/github/rectorphp/getrector.org?branch=master)


## Run via docker

```bash
docker-compose up
```

Voilá!

Web: [localhost:8080](http://localhost:8080)
Adminer: [localhost:8081](http://localhost:8081) (host: mysql, user: root, pass: root)

MySQL runs on port 3306, if you need to change that, you can use `GETRECTOR_ORG_MYSQL_PORT` env var:
```bash
GETRECTOR_ORG_MYSQL_PORT=12345 docker-compose up
```

### Troubleshooting

Sometimes, you might have outdated Docker images locally, to update, please run:
```bash
bin/pull-docker-images.sh
```

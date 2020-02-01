# [GetRector.org](https://getrector.org)

[![Coverage Status](https://coveralls.io/repos/github/rectorphp/getrector.org/badge.svg?branch=master)](https://coveralls.io/github/rectorphp/getrector.org?branch=master)
[![SonarCube](https://img.shields.io/badge/SonarCube_Debt-%3C20min-brightgreen.svg?style=flat-square)](https://sonarcloud.io/dashboard?id=rectorphp_getrector_org)


## How to Run Locally?

### Install

- run `npm install`
- run `composer install`

### Configure

- copy `.env` to `.env.local`
- change `DATABASE_HOST` there to `localhost`
- copy `docker-compose.dist.yml` to `docker-compose.yml`

### Run

- run `npm run watch`
- run `php -S localhost:8000 -t public`
- run `docker-compose up`

Open: [localhost:8000](http://localhost:8000)

Voilá:

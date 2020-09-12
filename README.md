Ezyord Docker Orchestrator
==========================

Docker global deployment
------------------------
Build and Run:

```bash
$ docker-compose up -d
```

Backend configuration
---------------------
Install symfony vendors:

```bash
$ docker-compose exec engine composer install
```

Then, update db schema and load fixtures:
```bash
$ docker-compose exec engine backend/bin/console doctrine:schema:update --force
```

Frontend configuration
----------------------
Launch install :
```bash
$ docker-compose exec engine node yarn install
```

Build angular app, execute:

```bash
$ docker-compose exec engine node yarn encore dev
```

For prod env :
```bash
$ docker-compose exec engine node yarn encore production
```

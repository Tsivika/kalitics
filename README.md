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
$ docker-compose exec engine bin/console doctrine:schema:update --force
```

Frontend configuration
----------------------
Launch install :
```bash
$ docker-compose exec engine yarn install
```

Build angular app, execute:

```bash
$ docker-compose exec engine yarn encore dev
```

For prod env :
```bash
$ docker-compose exec engine yarn encore production
```

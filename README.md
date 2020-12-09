Hiboo Docker Orchestrator
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

Then, update db schema:
```bash
$ docker-compose exec engine bin/console doctrine:schema:update --force
```

Frontend configuration
----------------------
Launch install :
```bash
$ docker-compose exec engine yarn install
```

Build encore webpack app, execute:

```bash
$ docker-compose exec engine yarn encore dev
```

For prod env :
```bash
$ docker-compose exec engine yarn encore production
```
Chmod upload :
```bash
sudo chmod 777 -R uploads/
```
CronTab :
```bash
php bin/console app:notify-participants
```

Maquette XD : 
https://xd.adobe.com/view/35a7b46b-6eca-4bbb-b660-04ea32a610df-60f4/grid	

Preprod : 
http://212.47.251.160/	
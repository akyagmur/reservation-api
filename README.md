# MISSAFIR case project: Simple Reservation API

This is a simple reservation API for MISSAFIR case project. It is a simple API that allows you to surf around listings and reservations. 
The app is built with *Symfony 6.2*.

## Third-parties
- doctrine/doctrine-bundle
- doctrine/doctrine-migrations-bundle
- doctrine/orm
- fakerphp/faker
- symfony/serializer
- symfony/test-pack
- symfony/validator

## Installation

1. Clone the repository

```bash
git clone git@github.com:akyagmur/reservation-api.git
```

2. Install dependencies

```bash
composer install
```

3. Run docker-compose

```bash
docker-compose up -d
```

4. Run migrations to create database schema

```bash
php bin/console doctrine:migrations:migrate
```

5. Load fixtures

```bash
php bin/console doctrine:fixtures:load
```

With a standard installation, the authority used to sign certificates generated in the Caddy container is not trusted by your local machine. You must add the authority to the trust store of the host :

```bash
# Mac
$ docker cp $(docker compose ps -q caddy):/data/caddy/pki/authorities/local/root.crt /tmp/root.crt && sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain /tmp/root.crt
# Linux
$ docker cp $(docker compose ps -q caddy):/data/caddy/pki/authorities/local/root.crt /usr/local/share/ca-certificates/root.crt && sudo update-ca-certificates
# Windows
$ docker compose cp caddy:/data/caddy/pki/authorities/local/root.crt %TEMP%/root.crt && certutil -addstore -f "ROOT" %TEMP%/root.crt
```

6. Available Endpoints Table

| Method | Endpoint | Description |
| ------ | ------ | ------ |
| POST | /api/v1/listing/search | Search listings |
| GET | /api/v1/listing/{reference} | Get a listing by id |
| POST | /api/v1/reservation/create | Create a reservation |
| GET | /api/v1/reservation/{reference} | Get a reservation by id |
| GET | /api/v1/reservations/{guest} | Get all reservations by guest |
| GET | /api/v1/guests/list | Get all guests |
| PATCH | /api/v1/reservation/{reference}/cancel | Cancel a reservation |
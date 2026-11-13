# FitnesSpot

A web platform to find and share **outdoor fitness spots** (street workout parks, fitness trails) on an interactive map – built in PHP / MySQL with Mapbox.

<p align="center"><img src="docs/add-spot.png" width="720" alt="Add a spot: form and map to pick its location"></p>

> CFPT Geneva · Swiss Federal VET Diploma (CFC) in IT – final individual practical exam (*TPI*) · 2021
> Solo project, carried out in 11 days (analysis, implementation, tests and documentation)

## Features

| Role | What they can do |
|---|---|
| **Visitor** | browse the map of spots, list and filter spots with pagination, view a spot (photos, location, comments, rating) |
| **Spotter** (registered user) | sign up / sign in, add a spot (photos + position picked on the map, address found by reverse geocoding), comment, like and rate spots, edit their profile |
| **Administrator** | validate the spots submitted by spotters (notified by e-mail), manage users, delete spots and comments |

The database comes with ~1,800 fitness stations in Switzerland imported from OpenStreetMap.

## Architecture

```
www/
├── model/        PHP classes, one per table (PDO prepared statements, singleton connection)
├── controller/   endpoints called by the pages (forms and AJAX)
├── view/         pages (PHP + Bootstrap) and JavaScript (maps, pagination, likes / votes)
├── config/       configuration examples (database, Mapbox token)
└── asset/        CSS and uploaded images
database/         schema + database user, and default data
```

<p align="center"><img src="docs/database-schema.png" width="520" alt="Database schema"></p>

## Getting started

Requirements: PHP 7.4+ with the `pdo_mysql` and `curl` extensions, MySQL or MariaDB, and a free [Mapbox](https://www.mapbox.com/) access token.

```bash
# 1. Database: schema + application user, then the default data
mysql -u root -p < database/dump-fitnesspot_TPI.sql
mysql -u root -p < database/default_data-fitnesspot_TPI.sql

# 2. Configuration: copy the examples and fill in the DB password and Mapbox token
cp www/config/config.inc.example.php www/config/config.inc.php
cp www/config/config.inc.example.js  www/config/config.inc.js

# 3. Run
php -S localhost:8000 -t www
```

Then open <http://localhost:8000>. All demo accounts use the password `demo1234` (administrator: `admin@FitnesSpot.ch`).
E-mail notifications rely on PHP's `mail()` and need a configured mail transport.

## Tech

PHP · MySQL · PDO · JavaScript · jQuery · Bootstrap 5 · Mapbox GL JS (maps, geocoding, directions)

## Credits & license

- Code: [MIT](LICENSE)
- Spot data © [OpenStreetMap contributors](https://www.openstreetmap.org/copyright), available under the [ODbL](https://opendatacommons.org/licenses/odbl/)
- Photos in `www/asset/` are demo content taken from the web and are not covered by the MIT license

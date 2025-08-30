# Laravel CRM API

A CRM API built in Laravel.

## Installation

```bash
git clone git@github.com:MichaelRushton/laravel-crm-api.git . && bin/app install
```

The API will be accessible at http://localhost and the [Mailpit](https://mailpit.axllent.org/) dashboard at http://localhost:8025.

Database connection details:

Host: 127.0.0.1\
Port: 5432\
Database: db\
Username: postgres\
Password:

## Commands

```bash
bin/app help                List available commands
bin/app install             Install the app
bin/app start               Start the Docker containers
bin/app stop                Stop the Docker containers
bin/app dev                 Clear cache and watch for changes
bin/app format              Run Prettier and Laravel Pint
bin/app composer [command]  Run a composer command
bin/app npm [command]       Run an npm command
bin/app php [command]       Run a php command
bin/app vendor [executable] Run a vendor/bin executable
bin/app test [options]      Run feature and unit tests
bin/app artisan [command]   Run an artisan command
bin/app [command]           Run an artisan command (shorthand)
```

## Insomnia

The repo includes an `insomnia.yaml` file for importing into [Insomnia](https://insomnia.rest/).

## Routes:

```bash
POST            api/v1/auth
GET|HEAD        api/v1/auth
DELETE          api/v1/auth

GET|HEAD        api/v1/customers
POST            api/v1/customers
GET|HEAD        api/v1/customers/{customer}
PUT|PATCH       api/v1/customers/{customer}
DELETE          api/v1/customers/{customer}

GET|HEAD        api/v1/users
POST            api/v1/users
GET|HEAD        api/v1/users/{user}
PUT|PATCH       api/v1/users/{user}
DELETE          api/v1/users/{user}
```

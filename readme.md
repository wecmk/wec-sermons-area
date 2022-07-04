# WEC Members

[![DevOps By Rultor.com](http://www.rultor.com/b/samuelpearce/wec-members)](http://www.rultor.com/b/samuelpearce/wec-members)

[![Build Status](https://travis-ci.com/samuelpearce/wec-members.svg?token=19LW8Y8PepjC4HymS1nt&branch=master)](https://travis-ci.com/samuelpearce/wec-members)

WEC Members is a Symfony web project to host sermons.

## Requirements

Supported

- Ubuntu CentOS 7

Requires

- [Composer](https://getcomposer.org/)
- MariaDB/MySQL
- PHP 8.1
  - curl
  - ctype
  - gettext
  - libsodium
  - mysql
  - xml
  - zip
- [Symfony CLI](https://symfony.com/download)
- [Yarn](https://yarnpkg.com/)

For development

- [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)

# Quick Start

## Start Database
Start your MySQL/MariaDB Database (however it's configured for you)

## Configure settings

Copy `.env` to `.env.local`

`.env.local` is ignored in git so add your config here, not in .env

## Seed database

Run Migration (this creates your DB Schema)
```shell
symfony console doctrine:migrations:migrate
```

Run init to seed database
```shell
symfony console app:init
```
note: this is a custom implementation of Fixtures. We don't use fixtures as it's only designed for development, 
but we want to seed a production database.

## Start a web server
```
symfony serve
```

# CRON Scripts
You'll need to set up a CRON script to run every 1 hour

```shell
APP_ENV=prod symfony console app:renewoauth2
```
This will renew the OAuth2 tokens (not required, but nicer for UX for admin)
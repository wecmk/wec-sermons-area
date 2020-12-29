# WEC Members

[![DevOps By Rultor.com](http://www.rultor.com/b/samuelpearce/wec-members)](http://www.rultor.com/b/samuelpearce/wec-members)

[![Build Status](https://travis-ci.com/samuelpearce/wec-members.svg?token=19LW8Y8PepjC4HymS1nt&branch=master)](https://travis-ci.com/samuelpearce/wec-members)

WEC Members is a Symfony web project to host sermons, Team lists and other
members-only data.

## Requirements

Supported

- Ubuntu 16.04

Requires

- [Composer](https://getcomposer.org/)
- [Elasticsearch](https://www.elastic.co/downloads/elasticsearch) - It's free,
- MariaDB/MySQL
- PHP 7.4
  - curl
  - ctype
  - gettext
  - libsodium
  - mysql
  - xml
- [Symfony CLI](https://symfony.com/download)
- [Yarn](https://yarnpkg.com/)

For development

- [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)

# Quick Start

Start your MySQL/MariaDB Database (however it's configured for you)

Run Migration (this creates your DB Schema)

Update .env.local

```
php bin/console doctrine:migrations:migrate
```

Run Fixtures (this loads some default values for the DB)
Note that some fixtures contain sensitive information which is not contained 
within the repository. Contact a repository admin if you need a copy.
```
php bin/console doctrine:fixtures:load --group=dev
```

Start a web server
```
symfony serve
```

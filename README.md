Requirements
============

1. [symfony-cli](https://symfony.com/download)
2. PHP >= 8.1
3. pdo_sqlite
4. composer
5. yarn

Install
=======

1. clone the repository
2. Fetch an API Key from https://www.omdbapi.com/apikey.aspx
3. create a `.env.local` at the root of the project with teh following content:
```dotenv
OMDB_API_KEY="API_KEY_FROM_OMDB_WEBSITE"
```
4. Run the following commands :

```bash
$ symfony composer install
$ yarn install
$ yarn dev
$ symfony console doctrine:migrations:migrate -n
$ symfony console doctrine:fixtures:load -n
$ symfony serve -d
```

Running the tests
=================

```bash
$ symfony php ./bin/phpunit --testdox
```

Importing movies
================

```bash
$ symfony console app:movies:import "harry potter" "deadpool" "asterix" "avatar" tt123456
```

Use the following to try the command without importing :
```bash
$ symfony console app:movies:import "harry potter" "deadpool" "asterix" "avatar" tt123456 --dry-run
```

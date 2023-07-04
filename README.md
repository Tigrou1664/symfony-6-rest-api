# API backend

## Get Started

```shell
# Start server
$ symfony server:start 

# Create bdd
$ php bin/console doctrine:database:create

# Migrate
$ php bin/console doctrine:migrations:migrate

# Insert fixtures
* php bin/console doctrine:fixtures:load

# Doc
http://localhost:8000/api/doc
```

## Known problems

- problem with ManyToMany relation with extra field
- missing tests
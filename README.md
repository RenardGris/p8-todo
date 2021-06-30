## Project

### Presentation
This project was make during my apprenticeship in programming
this is a base of a symfony project, it's a simple todo list.
I had to fix some issues and add many improvements, wrote some functional and unit test with PHPUnit to obtain at least 70% of coverage.
Finally, I had to make a guide for the next developers who's gonna take this project.

## Installation

###Setting up
1.  get project from repository
```
git clone https://github.com/RenardGris/p8-todo.git
```

2.  install dependency
```
composer install
```

- 2.1 Symfony will ask you for some information, like your database name and other...
  (In case of mistake you can edit the information in your parameters.yml file in app/config)


###Database
3.  Next, you can run the next command line :
- 3.1 create database
``` 
 php bin/console doctrine:database:create
```
- 3.2 create tables
``` 
 php bin/console doctrine:schema:update --force
```
- 3.3 fill tables with fake data (Optional)
``` 
 php bin/console doctrine:fixtures:load --fixtures src\AppBundle\DataFixtures\ORM\dev
```


###Tests
4. In case you want to run test : 
- 4.1 set your db information in app/config/config_test.yml
``` yml
doctrine:
    dbal:
        host:     YourHost
        dbname:   YourDbNameTest
        user:     YourUser
        password: YourPassword
```
- 4.2 fill tables with test data
``` 
 php bin/console doctrine:fixtures:load --fixtures src\AppBundle\DataFixtures\ORM\test --env test
```
- 4.3 Run the next command to launch the entire test dir
``` 
 php vendor/bin/phpunit
```
- 4.3.bis Same but with coverage
``` 
 php vendor/bin/phpunit --coverage-html web/test-coverage
```

## Contribution
It would be very appreciate if you want to help us, see contributing.md
Trout REST API Test
========================

This is a test for Trout Co.

What's inside?
--------------

This project contains the following technologies:

  * TroutBundle contains all the logic

  * [**DoctrineBundle**][1] - To support for the Doctrine ORM

  * [**FOSRestBundle**][2] - To support RESTful API's with Symfony2

  * [**DoctrineFixturesBundle**][3] - To generate some test data

  * [**behat-rest-testing**][4] - To test REST API with Behat

  * Annotations enabled for everything. Including @ParamConverter

All libraries and bundles included in the Symfony Standard Edition are released under the MIT or BSD license.

How to Start
------------

1. Check out the project from "https://github.com/orodsem/trout.git"
2. Go to where you locate Trout project (e.g. ~/Sites/trout/)
3. Run comopser update to get the latest bundles and dependencies
4. $ sh bash/init.sh

 To create a schema (called 'trout') and insert some test data, follow by running all Behat test scenarios.

 **NOTE**: You'll be asked "Careful, database will be purged. Do you want to continue Y/N ?", press Y

Enjoy!

A few small things to be considered:
-----------------------------------

1. Make sure app/cache directory has read/write permission
2. $ sh bash/init.sh regenerates the whole database to reuse consistent data via Behat including insert/update and delete
3. DB configuration details can be changed at ~/Sites/trout/app/config/parameters.yml
4. Behat scenarios can be found at ~/Sites/trout/features/trout.feature
5. Entity validation done by Annotation in each entity.


End Points
----------
## 1. Display a JobOffer by id

```
curl -v -H "Accept: application/json" -H "Content-type: application/json" http://trout.dev.com.au/jobOffer/get/1
```

Or use httpie for much nicer display

```
http http://trout.dev.com.au/jobOffer/get/9 Accept:application/json
```

## 2. Add a JobOffer

**Valid**

```
curl -v -H "Accept: application/json" -H "Content-type: application/json" -X POST -d '{"jobOffer": {"company": "company","salaryMinimum": "20000","salaryMaximum": "30000"}}' http://trout.dev.com.au/jobOffer/add
```

**Invalid** - Try to add expiry date
```
curl -v -H "Accept: application/json" -H "Content-type: application/json" -X POST -d '{"jobOffer": {"company": "company","salaryMinimum": "20000","salaryMaximum": "30000", "expiryDate":"12"}}' http://trout.dev.com.au/jobOffer/add -b "XDEBUG_SESSION=1" > response.json
```


[1]:  http://symfony.com/doc/2.8/book/doctrine.html
[2]:  https://github.com/FriendsOfSymfony/FOSRestBundle
[3]:  https://packagist.org/packages/doctrine/doctrine-fixtures-bundle
[4]:  https://github.com/deminy/behat-rest-testing

Trout REST API Test
========================

This is a dummy project to create REST API with Symfony2 including Behat

What's inside?
--------------

This project contains the following technologies:

  * TroutBundle contains all the logic

  * [**DoctrineBundle**][1] - To support for the Doctrine ORM

  * [**FOSRestBundle**][2] - To support RESTful API's with Symfony2

  * [**DoctrineFixturesBundle**][3] - To generate some test data

  * [**behat-rest-testing**][4] - To test REST API with Behat

  * [**NelmioApiDocBundle**][5] - To generate documents for REST API

  * [**JMSSerializerBundle**][6] - To serialise objects to XML,JSON or YML

  * Annotations enabled for everything. Including @ParamConverter

All libraries and bundles included in the Symfony Standard Edition are released under the MIT or BSD license.

How to Start
------------

1. Check out the project from "https://github.com/orodsem/trout.git"
2. Go to where you locate Trout project (e.g. ~/Sites/trout/)
3. Run comopser install/update to get the latest bundles and dependencies
4. $ sh bash/init.sh

 To create a schema, insert test data and then run all Behat test scenarios.

 **NOTE**: You'll be asked "Careful, database will be purged. Do you want to continue Y/N ?", press Y

A few small things to be considered:
-----------------------------------

1. Make sure app/cache directory has read/write permission
2. If http://trout.dev.com.au/ used as the base url, then you don't need to change Behat and other configuration (TODO: this should be configurable)
3. $ sh bash/init.sh regenerates the whole database to reuse consistent data via Behat including insert/update and delete
4. DB configuration details can be changed at ~/Sites/trout/app/config/parameters.yml
5. Behat scenarios can be found at ~/Sites/trout/features/trout.feature
6. Form validation done by Annotation in each entity.
7. After an image uploaded, it gets encrypted and saved /app/files/<kernel_dev>/ and image details saved in tout_file table.


End Points
----------
## 0. API Documentation

  You can see the API documentation at http://trout.dev.com.au/api/doc

  **NOTE**: This is not the complete list, only generated for demonstration and proof of concept

## 1. Display a JobOffer/Profile by id

```
curl -v -H "Accept: application/json" -H "Content-type: application/json" http://trout.dev.com.au/jobOffer/get/1
curl -v -H "Accept: application/json" -H "Content-type: application/json" http://trout.dev.com.au/profile/get/2
```

Or use httpie for much nicer display

```
http http://trout.dev.com.au/jobOffer/get/9 Accept:application/json
```

## 2. Add a JobOffer/Profile

**Valid**

```
curl -v -H "Accept: application/json" -H "Content-type: application/json" -X POST -d '{"jobOffer": {"company": "company","salaryMinimum": "20000","salaryMaximum": "30000"}}' http://trout.dev.com.au/jobOffer/add
curl -v -H "Accept: application/json" -H "Content-type: application/json" -X POST -d '{"profile": {"firstName": "new_first_name","lastName": "new_last_name"}}' http://trout.dev.com.au/profile/add
```

**Invalid** - Try to add expiry date to JobOffer
```
curl -v -H "Accept: application/json" -H "Content-type: application/json" -X POST -d '{"jobOffer": {"company": "company","salaryMinimum": "20000","salaryMaximum": "30000", "expiryDate":"12"}}' http://trout.dev.com.au/jobOffer/add -b "XDEBUG_SESSION=1" > response.json
```

## 3. Edit a JobOffer/Profile

```
curl -v -H "Accept: application/json" -H "Content-type: application/json" -X PUT -d '{"jobOffer": {"company": "trout","salaryMinimum": "20000","salaryMaximum": "30000"}}' http://trout.dev.com.au/jobOffer/edit/1
curl -v -H "Accept: application/json" -H "Content-type: application/json" -X PUT -d '{"profile": {"firstName": "edited_first_name","lastName": "edited_last_name"}}' http://trout.dev.com.au/profile/edit/2
```

## 4. upload a profile photo

```
curl -F "profilePhoto=@/<path>/<to>/<yourPhoto>/photo.png" http://trout.dev.com.au/profile/upload/2
```

TODO: Validate the image size and format.

## 5. Publish a JobOffer

```
curl -v -H "Accept: application/json" -H "Content-type: application/json" http://trout.dev.com.au/jobOffer/publish/2 Accept:application/json
```


## 6. Offer a JobOffer to a profile

```
curl -v -H "Accept: application/json" -H "Content-type: application/json" http://trout.dev.com.au/jobOffer/offer/2/1 Accept:application/json
```
TODO: Only published job offers can be offered

## 7. Close a JobOffer

```
curl -v -H "Accept: application/json" -H "Content-type: application/json" http://trout.dev.com.au/jobOffer/close/2 Accept:application/json
```

## 8. Delete a JobOffer/Profile

```
curl -i -H "Accept: application/json" -X DELETE http://trout.dev.com.au/jobOffer/delete/2
curl -i -H "Accept: application/json" -X DELETE http://trout.dev.com.au/profile/delete/2
```

## 9. Show all JobOffers/Profiles

```
curl -v -H "Accept: application/json" -H "Content-type: application/json" http://trout.dev.com.au/jobOffer/all
curl -v -H "Accept: application/json" -H "Content-type: application/json" http://trout.dev.com.au/profile/all
```

[1]:  http://symfony.com/doc/2.8/book/doctrine.html
[2]:  https://github.com/FriendsOfSymfony/FOSRestBundle
[3]:  https://packagist.org/packages/doctrine/doctrine-fixtures-bundle
[4]:  https://github.com/deminy/behat-rest-testing
[5]:  https://github.com/nelmio/NelmioApiDocBundle/blob/master/Resources/doc/index.md
[6]:  https://github.com/schmittjoh/JMSSerializerBundle





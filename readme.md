**Prerequisite**
* required PHP >= 7.1

**My Process**

***Initial BruteForce Approach***
* set up Controller to handle route request 
* use load for recipe and rating csv into cache and use as data source
* create modal for ratings and recipe
* overwrite Modal method for saving and updating new values
* write unit test to cover the routes and make sure it returns a json string 
  

**API documentation** 
https://documenter.getpostman.com/view/952945/collection/RVtxKCRV

*Q&A*

How to use your solution
* Make sure you are in the root folder - recipe
* Execute 'php composer.phar install' to install vendor library
* start server using 'php -S localhost:8000 -t public/'
* api documentation available here https://documenter.getpostman.com/view/952945/collection/RVtxKCRV

 
Reasons for your choice of web application framework
* Lumen is fast, lightweight micro-framework for writing RESTful APIs.
* it is an ideal choice because we don’t need stuff like sessions, templates and other features of full stack 
applications.


Explain how your solution would cater for different API consumers that require different recipe data e.g. a mobile app and the
front-end of a website
* you can apply versioning to the api, such that you have 
* http://localhost:8000/mobile/v1/api/recipes/1
* http://localhost:8000/web/v1/api/recipes/1

Anything else you think is relevant to your solution
* current implementation is missing an authentication layer
* current implementation using php built in server however for production we require 
nginx or apache 
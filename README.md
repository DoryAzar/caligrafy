# Caligrafy - A Light PHP Framework
![Caligrafy](https://caligrafy.com/public/images/resources/banner_white.png)

## What is Caligrafy

Caligrafy is a new and modern MVC framework for PHP that was built from the ground up to provide quick an easy ways for you to build sophisticated and modern web applications. The framework comes with powerful features such as Responsive Templates, easy database interfacing, social sharing, creating clean structured, easy authentication, easy credit card and cryptocurrency payments, easy search referencing and analytics.

Caligrafy bridges the power of server-side languages like a PHP with the sophistication of the client-side languages like Javascript to bring you one of the most versatile MVC frameworks on the market today.


## Requirements
+ PHP > 7.0
+ MySql > 5.6


## Installation
+ Pull the code from github
+ create a .env file by copying the example `cp .env.example .env`
+ Change the values in .env file to match your local or production server settings
+ run `composer install` to get all the dependencies needed
+ make the folder `/public/uploads/` readable if you intend to allow uploads in your application. You will need to run the command `sudo chmod -R 777 /public/uploads`
+ You are good to go!

> For more advanced installation, check the documentation [here](https://github.com/DoryAzar/mvc/wiki/1.-Getting-Started)

## New in Caligrafy
+ Caligrafy can now support VueJS a progressive Javascript framework that helps you build powerful user interfaces

> Learn more about VueJS [here](https://vuejs.org/)


## Dependencies

This framework uses several third-party librairies that are included in the distribution
+ Phug (PHP Pug) is used for creating powerful HTML views and templates
+ GUMP Validation is used to provide an easy and painless data validation and filtering
+ dotEnv is used to provide the capability of setting up environment variables for both local and production servers
+ claviska/SimpleImage is used to provide the ability to do server-side manipulations on images
+ stripe/stripe-php is used for payment features
+ coinbase/coinbase-commerce is used for cryptopayment features


## Documentation

We have created a rigorous documentation to help you understand the basics of the framework and to get you started as quickly as possible

1. [Getting Started](https://github.com/DoryAzar/mvc/wiki/1.-Getting-Started)
    > + [Installation](https://github.com/DoryAzar/mvc/wiki/1.-Getting-Started)
    > + [Understanding MVC architecture](https://github.com/DoryAzar/mvc/wiki/1.-Getting-Started#architecture)
    > + [Framework fundamentals](https://github.com/DoryAzar/mvc/wiki/1.-Getting-Started#fundamentals)
    > + [File Structure](https://github.com/DoryAzar/mvc/wiki/1.-Getting-Started#filestructure)

2. [Routing](https://github.com/DoryAzar/mvc/wiki/2.-Routing)
    > + [Defining Routes](https://github.com/DoryAzar/mvc/wiki/2.-Routing#definingroutes)
    > + ["Hello World" Route](https://github.com/DoryAzar/mvc/wiki/2.-Routing#basicroute)
    > + [Routing with parameters](https://github.com/DoryAzar/mvc/wiki/2.-Routing#parameterroute)
    > + [Controller Routing](https://github.com/DoryAzar/mvc/wiki/2.-Routing#controllerroute)
    > + [HTML Form Methods](https://github.com/DoryAzar/mvc/wiki/2.-Routing#htmlformmethods)
    
3. [Request](https://github.com/DoryAzar/mvc/wiki/3.-Request)
    > + [Accessing the Request](https://github.com/DoryAzar/mvc/wiki/3.-Request#accessrequest)
    > + [Request Properties](https://github.com/DoryAzar/mvc/wiki/3.-Request#requestproperties)
    > + [Request Methods](https://github.com/DoryAzar/mvc/wiki/3.-Request#requestmethods)

4. [Models](https://github.com/DoryAzar/mvc/wiki/4.-Models)
    > + [Relational Databases](https://github.com/DoryAzar/mvc/wiki/4.-Models)
    > + [Model Fundamentals](https://github.com/DoryAzar/mvc/wiki/4.-Models#modelfundamentals)
    > + [Interfacing with the Model](https://github.com/DoryAzar/mvc/wiki/4.-Models#modelfundamentals)
    > + [Model Methods](https://github.com/DoryAzar/mvc/wiki/4.-Models#modelmethods)
5. [Relationships](https://github.com/DoryAzar/mvc/wiki/5.-Relationships)
    > + [One-to-One Relationship](https://github.com/DoryAzar/mvc/wiki/5.-Relationships)
    > + [One-to-Many Relationship](https://github.com/DoryAzar/mvc/wiki/5.-Relationships#onetomany)
    > + [Many-to-Many Relationship](https://github.com/DoryAzar/mvc/wiki/5.-Relationships#manytomany)
    > + [Overriding Naming Convention](https://github.com/DoryAzar/mvc/wiki/5.-Relationships#override)
6. [Validation](https://github.com/DoryAzar/mvc/wiki/6.-Validation)
    > + [Data Validation](https://github.com/DoryAzar/mvc/wiki/6.-Validation#validation)
    > + [Data Filtering](https://github.com/DoryAzar/mvc/wiki/6.-Validation#filter)
    > + [Validation & Filtering](https://github.com/DoryAzar/mvc/wiki/6.-Validation#validationandfiltering)
    > + [File Validation](https://github.com/DoryAzar/mvc/wiki/6.-Validation#filevalidation)
7. [Views](https://github.com/DoryAzar/mvc/wiki/7.-Views)
    > + [Introduction to Phug](https://github.com/DoryAzar/mvc/wiki/7.-Views#introduction)
    > + [Pug Templates](https://github.com/DoryAzar/mvc/wiki/7.-Views#templates)
    > + [Structure and Format](https://github.com/DoryAzar/mvc/wiki/7.-Views#structure)
    > + [Getting Started with Views](https://github.com/DoryAzar/mvc/wiki/7.-Views#viewsstartup)
    > + [Simple View](https://github.com/DoryAzar/mvc/wiki/7.-Views#simpleview)
    > + [View with parameters](https://github.com/DoryAzar/mvc/wiki/7.-Views#parameterview)
8. [Controllers](https://github.com/DoryAzar/mvc/wiki/8.-Controllers)
    > + [Getting Started with Controllers](https://github.com/DoryAzar/mvc/wiki/8.-Controllers)
    > + [Creating a Controller](https://github.com/DoryAzar/mvc/wiki/8.-Controllers#createcontroller)
    > + [Routing to the Controller](https://github.com/DoryAzar/mvc/wiki/8.-Controllers#controllerrouting)
    > + [Controller Context](https://github.com/DoryAzar/mvc/wiki/8.-Controllers#controllercontext)
    > + [Controller Methods](https://github.com/DoryAzar/mvc/wiki/8.-Controllers#controllermethods)
9. [Helpers, Forms & REST API](https://github.com/DoryAzar/mvc/wiki/9.-Helpers-,-Forms-&-REST-API)
    > + [Helpers](https://github.com/DoryAzar/mvc/wiki/9.-Helpers-,-Forms-&-REST-API#helpers)
    > + [HTML Forms](https://github.com/DoryAzar/mvc/wiki/9.-Helpers-,-Forms-&-REST-API#forms)
    > + [REST API](https://github.com/DoryAzar/mvc/wiki/9.-Helpers-,-Forms-&-REST-API#restapi)
10. [Authentication](https://github.com/DoryAzar/mvc/wiki/9.1-Authentication)
11. [Stripe & Cryptocurrency Payment](https://github.com/DoryAzar/caligrafy/wiki/9.2-Stripe-&-Cryptocurrency-Payment)
12. [Metadata & Rich Cards](https://github.com/DoryAzar/mvc/wiki/9.3-Metadata-&-Social-Rich-Cards)
13. [Search Referencing and Analytics](https://github.com/DoryAzar/caligrafy/wiki/9.4-Search-Referencing-and-Analytics)
    
## Learn through examples
Caligrafy is an outstanding framework for educational purposes. We constantly develop instructional video materials to illustrate the main features of the framework. Stay on the look for more video tutorials on our you tube channel.
[Caligrafy Video Channel](https://www.youtube.com/channel/UCo0ZZbiHVGlF9lFWFpVhG8g)
    
## Connecting with the Caligrafy Community
There are several ways for the Caligrafy community to connect:
+ **github:** You can always use github to stay up to date with the roadmap of Caligrafy, to post issues and to track when feature requests and issues are done
+ **slack:** Joining our slack group is a great way to exchange with other members of the community, to get help on using the framework and to discuss any issues or features.
[Join our slack community](https://join.slack.com/t/caligrafy/shared_invite/enQtNjI4OTQwOTk0NTM0LWMyMmJiODI5M2E2ZTI5MjEwM2E3MTM2NWRkMWJjOTc3NmU4ZmY2ZjRiN2ZkMmE2YmE1YjhkZTRmNmI5MzE5Yzc)
+ **facebook Caligrafy Group:** Joining our Caligrafy group on facebook gives you more ways to interact with the community and to share success stories. 
[Join our facebook group](https://www.facebook.com/groups/caligrafy/)

## Learn Caligrafy

+ Caligrafy offers an online course that gives you all the basics that you need to create powerful applications with Caligrafy.


    [![](https://caligrafy.com/public/images/resources/viewcourse.png)](https://www.udemy.com/caligrafy/)



+ If you want to start using Caligrafy, join us in this first online live class that will introduce you to the framework and will help you get started quickly.
    
    
    [![](https://caligrafy.com/public/images/resources/joinevent.png)](https://www.eventbrite.com/e/learn-php-using-caligrafy-tickets-62056781504?utm-medium=discovery&utm-campaign=social&utm-content=attendeeshare&aff=escb&utm-source=cp&utm-term=listing)

## Need help getting started?
We are always here to help when you need us. If you need assistance getting started or if you need help understanding how Caligrafy can be useful to you, we can help. Reach out to us by joining our slack channel.
[Reach out to us](https://join.slack.com/t/caligrafy/shared_invite/enQtNjI4OTQwOTk0NTM0LWMyMmJiODI5M2E2ZTI5MjEwM2E3MTM2NWRkMWJjOTc3NmU4ZmY2ZjRiN2ZkMmE2YmE1YjhkZTRmNmI5MzE5Yzc)

## Keep Caligrafy going...
Your support keeps us going. If you like Caligrafy, there are several ways you could contribute:
+ **Promote us:** On our website, you can share our page with your friends and fans [caligrafy.com](https://caligrafy.com)
+ **Fund our project:** You can fund our project on [Kickposter](https://kickposter.us/kickposter/project/index.php?email=bVVCOHhURXQ0RjhFd1hTUjBVeGR0Umh6LzVucm80NHNDckNQdGI3Rkp3bz0=&postcart_id=a3pQM0c3UlFoZVdUK00zL1RhSjU3Zz09). Kickposter is an application that was built using earlier versions of Caligrafy. 

# Caligrafy - A Web Application Framework For Novice Developers
![Caligrafy](https://caligrafy.com/public/images/resources/banner_white.png)

## What is Caligrafy

Caligrafy is a new and modern MVC framework for PHP that was built from the ground up to provide easy and elegant ways for novice developers to build sophisticated and modern web applications. We've laid a solid foundation of security, social, e-commerce, analytics and machine learning features so that you focus your genius on your ideas. 

Caligrafy bridges the power of server-side languages like PHP with the sophistication of client-side languages like Javascript to expose you to how the most advanced Web capabilities are built.


## Requirements
+ PHP > 7.2
+ MySql > 5.6
+ curl, mbstring, openssl, mcrypt, gd and headers modules must be enabled in your servers

## Quick Installation
+ Pull the code from github
+ run `composer install` to get all the dependencies needed
+ run `php caligrafer.php initialize` from the terminal to initialize the framework
+ You are good to go!
+ If the quick installation does not complete successfully, proceed with the manual installation

## Manual Installation
+ Pull the code from github
+ create a .env file by copying the example `cp .env.example .env`
+ Change the values in .env file to match your local or production server settings
+ run `composer install` to get all the dependencies needed
+ make the folder `/public/uploads/` writable if you intend to allow uploads in your application. You will need to run the command `sudo chmod -R 777 /public/uploads`
+ You are good to go!

> For more advanced installation, check the documentation [here](https://github.com/DoryAzar/mvc/wiki/1.-Getting-Started)

## New in Caligrafy
+ Caligrafy now supports Face Detection and Recognition using faceapi framework by Vincent Muhler
+ Caligrafy now supports ChatBot Assistant using IBM Watson
+ Caligrafy now supports ACH payments
+ Caligrafy now supports API Authorization
+ Caligrafy can now support VueJS a progressive Javascript framework that helps you build powerful user interfaces

## Why Caligrafy

### 1. Full-Stack Framework
Caligrafy is a full-stack framework that leverages the power of server-side languages such as PHP with the power of client-side languages such as Javascript to help you build powerful and sophisticated web applications.

### 2. MVC Architecture
Caligrafy is built with an MVC architecture pattern that separates the business logic from the presentation layers. The Caligrafy MVC established an arsenal of methods and features that ensure that the separation of concerns is maintained between business and presentation layers.

### 3. Modern Modular Librairies
Caligrafy comes pre-packaged with ready-to-use modern features. In few lines, you can start accepting credit card payments, ach payments, cryptocurrency payments, building chatbots and assistants. In one line of code, your application can have rich structured data and can be ready for social sharing. In no time, your application can have a REST-API exposed for third-party applications. And many others...

### 4. VueJS
Caligrafy supports VueJS as an alternative to using its template engine. VueJS complements Caligrafy in its ability to create powerful and nimble user experiences in the "View" layer of an MVC architecture. If you are a PHP developer, Caligrafy makes it easy for you to use VueJS. If you are a Javascript developer, Caligrafy makes it easy for you to not be overwhelmed by PHP.

### 5. AI and Machine Learning
Caligrafy integrates machine learning capabilities at its core to help you build artificial intelligence seemlessly into your application. 

### 6. Template Engine
Caligrafy has a built-in and powerful template engine that can be used to create sophisticated user experiences easily.

### 7. Lightweight Syntax
Caligrafy is closer to bare-bone programming than other frameworks out there. While it delivers equivalent - if not more powerful - features, it is built from the ground up with developers in mind.

### 8. Documentation & Support
Caligrafy is committed to providing continuous support to its community of developers through an extensive online Documentation, Online video tutorials on its youtube channel, training courses on Udemy and live help through Slack, Facebook and Github.


## Dependencies

This framework uses several third-party librairies that are included in the distribution
+ Phug (PHP Pug) is used for creating powerful HTML views and templates
+ GUMP Validation is used to provide an easy and painless data validation and filtering
+ dotEnv is used to provide the capability of setting up environment variables for both local and production servers
+ claviska/SimpleImage is used to provide the ability to do server-side manipulations on images
+ stripe/stripe-php is used for payment features
+ coinbase/coinbase-commerce is used for cryptopayment features


## Documentation

### Learning Caligrafy

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

### Caligrafy and Vue.js

Caligrafy is a modern MVC framework that leverages the powerful technologies that fuel each of the M (Model), the V (View) and the C (Controller). Vue.js is a modern and progressive Javascript framework that has been built from the  ground up just like Caligrafy. Vue.js empowers you to create powerful and sophisticated Views. 
This framework integrates seamlessly with Vue.js to combine the best of PHP with the best of JS.

#### For prototyping and learning purposes

In this section, we cover the basics of a PHP developer could quickly use VueJS. 

1. [Understanding the flow of information](https://github.com/DoryAzar/caligrafy/wiki/9.7-Understanding-the-flow-of-information)
2. [Understanding structure](https://github.com/DoryAzar/caligrafy/wiki/9.7.1-Understanding-the-structure)
3. [Routes](https://github.com/DoryAzar/caligrafy/wiki/9.7.2-Routes)
3. [Requests](https://github.com/DoryAzar/caligrafy/wiki/9.7.3-Requests)
4. [Forms](https://github.com/DoryAzar/caligrafy/wiki/9.7.4-Forms)
5. [Validations](https://github.com/DoryAzar/caligrafy/wiki/9.7.5-Validations)
6. [Components](https://github.com/DoryAzar/caligrafy/wiki/9.7.6-Components)

#### For large scale application purposes

In order to build large scale applications using Vue, we need the ability to leverage the powerful capabilities of Vue such as Single Page Application (SPA) and Single File Components (SFC). 

1. [Setting up the Vue application](https://github.com/DoryAzar/caligrafy/wiki/9.8.1---Setting-up-the-Vue-application)
2. [Routing for Single Page Applications](https://github.com/DoryAzar/caligrafy/wiki/9.8.2-Routing-for-Single-Page-Applications)
3. [Single File Components](https://github.com/DoryAzar/caligrafy/wiki/9.8.3-Single-File-Components)
4. [State Management](https://github.com/DoryAzar/caligrafy/wiki/9.8.4-State-Management)

### AI in Caligrafy

Caligrafy provides easy ways to include Artificial Intelligence and Machine Learning to offer features such as Bots, Face Detection and Recognition. 

1. [Creating Bots with Watson](https://github.com/DoryAzar/caligrafy/wiki/9.9.1-Creating-Bots-with-Watson)
2. [Face Detection and Recognition](https://github.com/DoryAzar/caligrafy/wiki/9.9.2-Face-Detection-and-Face-Recognition)
    
## Learn through examples
Caligrafy is an outstanding framework for educational purposes. We constantly develop instructional video materials to illustrate the main features of the framework. Stay on the look for more video tutorials on our you tube channel.
[Caligrafy Video Channel](https://www.youtube.com/channel/UCo0ZZbiHVGlF9lFWFpVhG8g)
    
## Connecting with the Caligrafy Community
There are several ways for the Caligrafy community to connect:
+ **github:** You can always use github to stay up to date with the roadmap of Caligrafy, to post issues and to track when feature requests and issues are done
+ **slack:** Joining our slack group is a great way to exchange with other members of the community, to get help on using the framework and to discuss any issues or features.
[Join our slack community](https://join.slack.com/t/caligrafy/shared_invite/enQtNzI4MDY2OTA4MTgzLTI2NDc2NTVmMDNlMWQ5YWYxN2RjZTkwZjdiNjM5ZTg3NjQ2YWYyMzRmZDgzNWE0Nzc4YjQyODM2NDNkNjQ2OTU)
+ **facebook Caligrafy Group:** Joining our Caligrafy group on facebook gives you more ways to interact with the community and to share success stories. 
[Join our facebook group](https://www.facebook.com/groups/caligrafy/)

## Learn Caligrafy

+ Caligrafy offers an online course that gives you all the basics that you need to create powerful applications with Caligrafy.


    [![](https://caligrafy.com/public/images/resources/viewcourse.png)](https://www.udemy.com/caligrafy/)


## Need help getting started?
We are always here to help when you need us. If you need assistance getting started or if you need help understanding how Caligrafy can be useful to you, we can help. Reach out to us by joining our slack channel.
[Reach out to us](https://join.slack.com/t/caligrafy/shared_invite/enQtNzI4MDY2OTA4MTgzLTI2NDc2NTVmMDNlMWQ5YWYxN2RjZTkwZjdiNjM5ZTg3NjQ2YWYyMzRmZDgzNWE0Nzc4YjQyODM2NDNkNjQ2OTU)

## Keep Caligrafy going...
Your support keeps us going. If you like Caligrafy, there are several ways you could contribute:
+ **Promote us:** On our website, you can share our page with your friends and fans [caligrafy.com](https://caligrafy.com)
+ **Fund our project:** You can fund our project on [Kickposter](https://kickposter.us/kickposter/project/index.php?email=bVVCOHhURXQ0RjhFd1hTUjBVeGR0Umh6LzVucm80NHNDckNQdGI3Rkp3bz0=&postcart_id=a3pQM0c3UlFoZVdUK00zL1RhSjU3Zz09). Kickposter is an application that was built using earlier versions of Caligrafy. 

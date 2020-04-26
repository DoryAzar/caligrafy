# Caligrafy Vue App
+ By: *Caligrafy*

## Requirements
+ Node is needed in order to run and execute the Vue app
+ npm is required to install and manage all the necessary dependencies

## Dependencies included in the distribution

### Production
+ Vue: The VueJS library is the core of this app
+ Vue-Router: Vue Route is used for creating routers for the app
+ Vuex: State Management library for Vue
+ Axios: Library used for making asynchronous calls to the Caligrafy Server

## Installation

In order to get started with a Caligrafy Vue app:

1. Create a project using Caligrafer by running the following shell command

```bash
php caligrafer.php create your_project_name
```

A new folder will appear in the Caligrafy structure with the name you specified.

2. Install the dependencies for this app

+ In Development

```bash
cd your_project_name
npm install
```

+ In production

```bash
cd your_project_name
npm install --production
```

3. Running in Development

```bash
npm run serve
```

## Deploying in production

### MAMP/LAMP/WAMP/XAMP Servers

In order to deploy your application in a PHP/Apache/MySql environment running on Linux or Windows for example, you need to:

1. If you don't want to install node on that server, then make sure to run production installation on your development machine first
```bash
npm install --production
```
2. A `dist` folder is created in your app that contains the running logic of your application. 
3. Transfer the entire app folder onto your production server
4. Create a virtual host in your Apache server that points to the `dist` folder of your application


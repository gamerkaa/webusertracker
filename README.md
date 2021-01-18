# webusertracker
A script that assists in updating records meant for recording page visits into a database.

The configuration for the database can be set in config.php.

## FrontEnd
webusertracker.js
    Has functions for saving data tracking and event tracking. The saved data can be read too.
    The setTrackerPHP(url) has to be set to the relative path to the webusertracker.php before any other functions are invoked.

## Backend
config.php - Has the database configuration details.
Model.php - Contains source program for interacting with the database.
webusertracker.php - has functions for storing the tracking data using Model.php.
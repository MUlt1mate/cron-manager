# PHP Cron tasks manager

## File storage example

Using this models you can store tasks information in file. 
This method is **NOT** secure - data file can be modified by anyone.
Integrity is **NOT**  guaranteed - new task_id calculated from the last task in file. But it's not a problem usually.

Data files store in /tmp/ folder by default.

### Requirements

* PHP 5.3 or above
* [monolog/monolog](https://github.com/Seldaek/monolog)

Monolog uses to write log messages

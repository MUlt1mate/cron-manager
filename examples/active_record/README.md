# PHP Cron tasks manager

## Installation with ActiveRecord library

[ActiveRecord](https://github.com/jpfuentes2/php-activerecord) is an open source ORM library based on the ActiveRecord pattern.
This example is a simple standalone application that uses **Cron tasks manager** and **ActiveRecord**

Install packages via Composer
```
composer update
```

### Requirements

* PHP 5.3 or above

### Configure
* Set DB credentials in *index.php*
* Create DB tables (SQL queries in `DbHelper` class)
* Import tasks through interface or add them manually
* Add new line into crontab file that will invoke ```TasksController::checkTasks()```
* Disable tasks that will be invoke through the manager
* Make sure that manager is not publicly available

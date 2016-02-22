# PHP Cron tasks manager

## Installation with Yii2 framework

Install package via Composer
```
composer require mult1mate/cron-manager
```

### Requirements

* PHP 5.4 or above
* Yii2

Tested with Yii2 v2.0.6

### Implementation

Yii2 controllers use parameters in controllers __construct() method. It's recommended to run methods only from models.

### Configure
* Copy folders into your project files
* run `yii/migrate` to create DB tables or use SQL queries in `DbHelper` class
* Modify controller and views or create your own.
* Import tasks through interface or add them manually
* Add new line into crontab file ```yii cron/check-tasks```
* Disable tasks that will be invoke through the manager
* Make sure that manager is not publicly available

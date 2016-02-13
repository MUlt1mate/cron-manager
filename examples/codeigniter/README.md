# PHP Cron tasks manager

## Installation with CodeIgniter framework

Enable Composer in *application/config.php*
```$config['composer_autoload'] = true;```

Install package via Composer
```
composer require mult1mate/cron-manager
```

### Requirements

* PHP 5.3 or above
* CodeIgniter framework

Tested with CodeIgniter 3.0, but should work with v2.

### Implementation

In this example DbBaseModel provides access to DB. Models Task and TaskRun extend it.

CodeIgniter has issues with controller classes loading. It's recommended to run methods only from models.

### Configure
* Copy application folder into your project files
* Make sure that *manager_actions.js* in the web root directory
* Create DB tables (SQL queries in `DbHelper` class)
* Copy and modify controller and views. Or create your own.
* Import tasks through interface or add them manually
* Add new line into crontab file that will invoke ```TasksController::checkTasks()```
* Disable tasks that will be invoke through the manager
* Make sure that manager is not publicly available

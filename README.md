# PHP Cron tasks manager

This is a flexible tasks manager designed for MVC-type applications. It's used instead of standard linux *crontab* command.

The purpose of this tool is to provide an easy way to manipulate repetitive tasks. 

[Live Demo](https://cron.multimate.ru)

[![Build Status](https://travis-ci.org/MUlt1mate/cron-manager.svg?branch=master)](https://travis-ci.org/MUlt1mate/cron-manager)
[![Code Climate](https://codeclimate.com/github/MUlt1mate/cron-manager/badges/gpa.svg)](https://codeclimate.com/github/MUlt1mate/cron-manager)
[![Test Coverage](https://codeclimate.com/github/MUlt1mate/cron-manager/badges/coverage.svg)](https://codeclimate.com/github/MUlt1mate/cron-manager/coverage)
[![License](http://img.shields.io/:license-mit-blue.svg)](http://doge.mit-license.org)
[![Gitter](https://badges.gitter.im/MUlt1mate/cron-manager.svg)](https://gitter.im/MUlt1mate/cron-manager)

## How this is works
Replace all tasks in crontab file with one which will invoke method ```TaskRunner::checkAndRunTasks()```.

Import tasks from current crontab file or add them manually. Active tasks will run one by one if current time matches with the task's time expression. Output of tasks can be handled. For each execution will be assigned status:
* **Success** if method returned ```true```; 
* **Error** if method returned ```false``` or an exception was caught; 
* **Started** if task is running or wasn't ended properly.

### Features
* Works with any storage engines
* Flexible implementation with interfaces
* Disable, enable and run tasks through tool interface
* Handle tasks output however you want
* Time expression helper shows next run dates
* Monitor runs results
* Export and import tasks from crontab
* Add needed method for new task from dropdown

## Installation

Install package via Composer
```
composer require mult1mate/cron-manager
```

### Requirements

* PHP 5.3 or above
* [mtdowling/cron-expression](https://github.com/mtdowling/cron-expression)

### Configure
* Create tables if you want to store data in database (SQL queries in `DbHelper` class)
* Implement `TaskInterface` and `TaskRunInterface` or use predefined classes from the Example folder
* Copy and modify controller and views. Or create your own.
* Import tasks through interface or add them manually
* Add new line into crontab file that will invoke ```TaskRunner::checkAndRunTasks()```
* Disable tasks that will be invoke through the manager
* Make sure that manager is not publicly available

See also examples for [ActiveRecord](https://github.com/MUlt1mate/cron-manager/tree/master/examples/active_record), 
[CodeIgniter](https://github.com/MUlt1mate/cron-manager/tree/master/examples/codeigniter), 
[Yii2](https://github.com/MUlt1mate/cron-manager/tree/master/examples/yii2_basic),
[File storage](https://github.com/MUlt1mate/cron-manager/tree/master/examples/file_storage)

## Screenshots

![Tasks list](https://cron.multimate.ru/img/Selection_006.png)
![Report](https://cron.multimate.ru/img/Selection_008.png)
![Logs](https://cron.multimate.ru/img/Selection_007.png)
![Import and export](https://cron.multimate.ru/img/Selection_003.png)

See [Live Demo](https://cron.multimate.ru) for more!

## Changelog

* v1.1.0 - File storage models added
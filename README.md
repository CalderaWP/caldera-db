# Caldera DB

## 👀🌋 This Is A Module Of The [Caldera Framework](https://github.com/CalderaWP/caldera)
* 🌋 Find Caldera Forms Here:
    - [Caldera Forms on Github](http://github.com/calderawp/caldera-forms/)
    - [CalderaForms.com](http://calderaforms.com)
    
* 🌋 [Issues](https://github.com/CalderaWP/caldera/issues) and [pull requests](https://github.com/CalderaWP/caldera/pulls), should be submitted to the [main Caldera repo](https://github.com/CalderaWP/caldera/pulls).

### Install
* Add to your package:
    - `composer require calderawp/http`
* Install for development:
    - `git clone git@github.com:CalderaWP/http.git && composer install`

## Overview
This package provides database table creation and basic queries.

Tables schemas are defined using YAML. As of now, only WPDB integration is available.
## Caldera Forms Tables
### V1 Tables


### V2 Tables


### Create Table From Attributes

```php
use calderawp\DB\Factory;
use calderawp\interop\Attribute;

//id column
$attributeData = [
    'name' => 'id',
    'sqlDescriptor' => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
    'format' => '%d',
];

//name column
$attribute2Data = [
    'name' => 'name',
    'sqlDescriptor' => 'varchar(256) NOT NULL',
    'format' => '%s',
];

//name of table
$tableName = 'cf_whatever';
//name of primary key(s)
$primaryKey = 'id';
//name of index(es)
$indexes = ['name'];

//Create factory
$factory = new Factory();
//Create Table schema
$attributes = [$attributeData, $attribute2Data];
$tableSchema = $factory->tableSchema($attributes, $tableName, $primaryKey, $indexes);
//Create table using WordPress' wpdb
$table = $factory->wordPressDatabaseTable($tableSchema,$wpdb);

```

## License, Copyright, etc.
Copyright 2018+ CalderaWP LLC and licensed under the terms of the GNU GPL license. Please share with your neighbor.

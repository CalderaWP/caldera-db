<?php
require_once dirname(__FILE__, 4).'/tests/vendor/autoload.php';

new \calderawp\caldera\Forms\CalderaForms(\caldera(), \caldera()->getServiceContainer());

if (!defined('OBJECT')) {
    define('OBJECT', 'OBJECT');
}
if (!defined('object')) {
    define('object', 'OBJECT');
}
if (!defined('OBJECT_K')) {
    define('OBJECT_K', 'OBJECT_K');
}

if (!defined('ARRAY_A')) {
    define('ARRAY_A', 'ARRAY_A');
}

if (!defined('ARRAY_N')) {
    define('ARRAY_N', 'ARRAY_N');
}


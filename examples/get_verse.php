<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

$passage_data = getPassageData('Genesis 1:1');

var_dump($passage_data);
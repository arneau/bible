<?php

# Set warning levels
error_reporting(E_ERROR | E_PARSE | E_WARNING);

# Set include path
set_include_path(__DIR__);

# Require function files
require_once 'bibles.php';
require_once 'books.php';
require_once 'lessons.php';
require_once 'passages.php';
require_once 'tags.php';
require_once 'topics.php';
require_once 'trees.php';
require_once 'verses.php';
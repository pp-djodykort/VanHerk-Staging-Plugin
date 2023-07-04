<?php
// ============ Imports ============
# WordPress
if ($wpLoad = dirname(__DIR__, 6).'/wp/wp-load.php' and file_exists($wpLoad)) require_once($wpLoad);
elseif ($wpLoad = dirname(__DIR__, 6).'/wp-load.php' and file_exists($wpLoad)) require_once($wpLoad);

# Classes / Functions
require_once(dirname(__DIR__,1).'/includes/classes.php');
require_once(dirname(__DIR__,1).'/includes/functions.php');

// ============ Declaring Variables ============
# Limits
// Changing the execution time and memory limit
ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');

# Globals
global $wpdb;

// ============ Start of Program ============
$ogOffers = new OGOffers();
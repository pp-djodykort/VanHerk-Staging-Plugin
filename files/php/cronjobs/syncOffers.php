<?php
// ==================================== Imports ====================================
# Limits
// Changing the exectuion time and memory limit
ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');

# Wordpress
require_once(dirname(__DIR__, 6).'/wp-load.php');

# Classes / Functions
require_once(dirname(__DIR__,1).'/includes/classes.php');
require_once(dirname(__DIR__,1).'/includes/functions.php');
require_once(dirname(__DIR__, 6).'/wp-load.php');
// ==================================== Declaring Variables ====================================
# Globals
global $wpdb;

// ==================================== Start of Program ====================================
$ogOffers = new OGOffers();
<?php
// ==================================== Imports ====================================
# Wordpress
require_once(dirname(__DIR__, 6).'/wp-load.php');

# Classes / Functions
require_once(dirname(__DIR__,1).'/includes/classes.php');
require_once(dirname(__DIR__,1).'/includes/functions.php');

// ==================================== Declaring Variables ====================================
# Globals
global $wpdb;

// ==================================== Start of Program ====================================
$ogOffers = new OGOffers();
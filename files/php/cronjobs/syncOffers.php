<?php
// ============ Access allowed & Lock file ============
// ======== Declaring Variables ========
# Plus this variable: '/lockfiles/syncOffers.lock' do it the right way so it can be used on every os
$lockFile = __DIR__. DIRECTORY_SEPARATOR. 'lockfiles'. DIRECTORY_SEPARATOR. 'syncOffers.lock';
# Variables
$boolLockFileSystemEnabled = OGVanHerkOffers::boolFirstInit() ? True : /* Change this to True if u want it to be permanent */ False;
$accessToken = ['accessToken', '5375636B4D79416363657373546F6B656E'];
$overrideToken = ['overrideToken', '5375636B4D794C6F636B46696C65546F6B656E'];

# GET/$argv Arguments
if (php_sapi_name() == 'cli') {
	$accessAllowed = isset($argv[1]) && (($argv[1] == $accessToken[1] ?? false));
	$overrideActivated = isset($argv[2]) && (($argv[2] == $overrideToken[1] ?? false));
}
else {
	$accessAllowed = isset($_GET[$accessToken[0]]) && (($_GET[$accessToken[0]] == $accessToken[1] ?? false));
	$overrideActivated = isset($_GET[$overrideToken[0]]) && (($_GET[$overrideToken[0]] == $overrideToken[1] ?? false));
}

// ======== Start Program ========
if (!$accessAllowed) {
	die("Access not allowed.");
}

if ($boolLockFileSystemEnabled) {
	# Check if this cronjob currently is activated
	if (file_exists($lockFile)) {
		# Check if an override is activated or not
		if (!$overrideActivated) {
			die("The cronjob currently already is running.");
		}
	}
	else {
		# Check if the directory exists
		if (!file_exists(dirname($lockFile))) {
			mkdir(dirname($lockFile), 0777, true);
		}
	}

	# Touching the file, so it can be used as a lock file
	touch($lockFile);

	# Registering a shutdown function, so the lock file can be removed after the program has finished
	register_shutdown_function(function () use ($lockFile) {
		unlink($lockFile);
	});
}

// ============ Imports ============
# WordPress
if ($wpLoad = dirname(__DIR__, 6) . '/wp/wp-load.php' and file_exists( $wpLoad ) ) {require_once( $wpLoad );}
elseif ($wpLoad = dirname(__DIR__, 6) . '/wp-load.php' and file_exists( $wpLoad ) ) {require_once( $wpLoad );}

# Classes / Functions
require_once(dirname(__DIR__) . '/includes/classes.php');
require_once(dirname(__DIR__) . '/includes/functions.php');

// ============ Declaring Variables ============
# Limits
// Changing the execution time and memory limit
ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');

# Globals
global $wpdb;

// ============ Start of Program ============
$ogOffers = new OGVanHerkOffers();
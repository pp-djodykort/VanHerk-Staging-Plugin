<?php
// ============ Access allowed & Lock file ============
// ======== Declaring Variables ========
$lockFile = __DIR__. '/lockfiles/syncOffers.lock';
# Variables
$accessToken = [ 'accessToken', '5375636B4D79416363657373546F6B656E' ];
$overrideToken = [ 'overrideToken', '5375636B4D794C6F636B46696C65546F6B656E' ];

# GET Arguments
$accessAllowed = isset($_GET[$accessToken[0]]) && (($_GET[$accessToken[0]] == $accessToken[1] ?? false));
$overrideActivated = isset($_GET[$overrideToken[0]]) && (($_GET[$overrideToken[0]] == $overrideToken[1] ?? false));
print($overrideActivated);
// ======== Start Program ========
if (!$accessAllowed) {
	die("Access not allowed.");
}

# Check if this cronjob currently is activated
if (file_exists($lockFile)) {
	# Check if an override is activated or not
	if (!$overrideActivated) {
		die("The cronjob currently already is running.");
	}
}
touch($lockFile);

// ============ Imports ============
# WordPress
if ( $wpLoad = dirname( __DIR__, 6 ) . '/wp/wp-load.php' and file_exists( $wpLoad ) ) {
	require_once( $wpLoad );
} elseif ( $wpLoad = dirname( __DIR__, 6 ) . '/wp-load.php' and file_exists( $wpLoad ) ) {
	require_once( $wpLoad );
}

# Classes / Functions
require_once( dirname( __DIR__, 1 ) . '/includes/classes.php' );
require_once( dirname( __DIR__, 1 ) . '/includes/functions.php' );

// ============ Declaring Variables ============
# Limits
// Changing the execution time and memory limit
ini_set( 'max_execution_time', '0' );
ini_set( 'memory_limit', '-1' );

# Globals
global $wpdb;

// ============ Start of Program ============
$ogOffers = new OGOffers();

// ============ End of Program ============
unlink($lockFile);
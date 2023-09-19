<?php
// ============ Declaring Variables ============
# Lockfile
$boolLockFileSystemEnabled = False;
$lockFile = __DIR__. DIRECTORY_SEPARATOR. 'lockfiles'. DIRECTORY_SEPARATOR. 'syncOffers.lock';

# Access
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

// ============ Security ============
# Checking the Access Allowed
if (!$accessAllowed) die("Access not allowed.");

# Checking the LockFile System
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

		# Touching the file, so it can be used as a lock file
		touch($lockFile);
	}
}

// ============ Declaring Variables ============
session_start();
# Bools
$_SESSION['boolDone'] = False;

# Ints
$beginTime = time();

# Limits
ini_set( 'max_execution_time', '0');
ini_set( 'memory_limit', '-1' );

// ============ Imports ============
# WordPress
if ($wpLoad = dirname(__DIR__, 6) . DIRECTORY_SEPARATOR . 'wp' . DIRECTORY_SEPARATOR . 'wp-load.php' and file_exists( $wpLoad ) ) {require_once( $wpLoad );}
elseif ($wpLoad = dirname(__DIR__, 6) . DIRECTORY_SEPARATOR .'wp-load.php' and file_exists( $wpLoad ) ) {require_once( $wpLoad );}

# Classes / Functions
require_once(dirname(__DIR__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'classes.php');
require_once(dirname(__DIR__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'functions.php');

# Registering a shutdown function, so the lock file can be removed after the program has finished
register_shutdown_function(function () use ($lockFile, $beginTime) {
	// ======== Declaring Variables ========
	# Classes
	global $wpdb;

	# Date / Time
	date_default_timezone_set('Europe/Amsterdam');

	// ======== Start of Function ========
	# Untouching the file, so it can be used as a lock file
	unlink($lockFile);

	# Putting in the database how much memory it ended up using maximum from bytes to megabytes
	$maxMemoryUsage = (memory_get_peak_usage(true) / 1024 / 1024);
	$memoryUsage = (memory_get_usage(true) / 1024 / 1024);
	$wpdb->insert('cronjobs', [
		'name' => 'OGOffers',
		# convert to megabytes
		'memoryUsageMax' => $maxMemoryUsage,
		'memoryUsage' => $memoryUsage,
		'boolGiveLastCron' => OGVanHerkSettingsData::$boolGiveLastCron,
		'objectsCreated' => OGVanHerkSettingsData::$intObjectsCreated,
		'objectsUpdated' => OGVanHerkSettingsData::$intObjectsUpdated,
		'datetime' => date('Y-m-d H:i:s', $beginTime),
		'duration' => round((time() - $beginTime) / 60, 2),
		'boolDone' => $_SESSION['boolDone']
	]);
});

// ============ Start of Program ============
$OGSyncOffers = new OGVanHerkOffers();
$_SESSION['boolDone'] = True;
exit();
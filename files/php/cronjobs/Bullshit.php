<?php
// ============ Access allowed & Lock file ============
// ======== Declaring Variables ========
# Plus this variable: '/lockfiles/syncOffers.lock' do it the right way so it can be used on every os
$lockFile = __DIR__. DIRECTORY_SEPARATOR. 'lockfiles'. DIRECTORY_SEPARATOR. 'syncOffers.lock';
# Variables
$boolLockFileSystemEnabled = false;
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
	touch($lockFile);
}

// ============ Imports ============
# WordPress
if ($wpLoad = dirname( __DIR__, 6 ) . '/wp/wp-load.php' and file_exists( $wpLoad ) ) {require_once( $wpLoad );}
elseif ($wpLoad = dirname( __DIR__, 6 ) . '/wp-load.php' and file_exists( $wpLoad ) ) {require_once( $wpLoad );}

# Classes / Functions
require_once( dirname( __DIR__, 1 ) . '/includes/classes.php' );
require_once( dirname( __DIR__, 1 ) . '/includes/functions.php' );

// ============ Declaring Variables ============
# Limits
// Changing the execution time and memory limit
ini_set( 'max_execution_time', '0');
ini_set( 'memory_limit', '-1' );

# Globals
global $wpdb;

// ============ Start of Program ============
# Getting all the nieuwbouw posts
$nieuwbouwPosts = new WP_Query([
	'post_type' => 'nieuwbouw',
	'post_status' => 'any',
	'posts_per_page' => -1,
	'meta_key' => 'type',
	'meta_value' => 'project'
]);
$nieuwbouwPostsExists = $nieuwbouwPosts->have_posts();
# Delete every single one of them including the children
if ($nieuwbouwPostsExists) {
	foreach ($nieuwbouwPosts->posts as $post) {
		$children = get_children([
			'post_parent' => $post->ID,
			'post_type' => 'any',
			'post_status' => 'any',
			'posts_per_page' => -1
		]);
		if (!empty($children)) {
			foreach ($children as $child) {
				# Getting their child
				$grandChildren = get_children([
					'post_parent' => $child->ID,
					'post_type' => 'any',
					'post_status' => 'any',
					'posts_per_page' => -1
				]);
				if (!empty($grandChildren)) {
					foreach ($grandChildren as $grandChild) {
						# Getting their child
						$greatGrandChildren = get_children([
							'post_parent' => $grandChild->ID,
							'post_type' => 'any',
							'post_status' => 'any',
							'posts_per_page' => -1
						]);
						if (!empty($greatGrandChildren)) {
							foreach ($greatGrandChildren as $greatGrandChild) {
								# Getting their child
								$greatGreatGrandChildren = get_children([
									'post_parent' => $greatGrandChild->ID,
									'post_type' => 'any',
									'post_status' => 'any',
									'posts_per_page' => -1
								]);
								if (!empty($greatGreatGrandChildren)) {
									foreach ($greatGreatGrandChildren as $greatGreatGrandChild) {
										wp_delete_post($greatGreatGrandChild->ID, true);
									}
								}
								wp_delete_post($greatGrandChild->ID, true);
							}
						}
						wp_delete_post($grandChild->ID, true);
					}
				}
				wp_delete_post($child->ID, true);
			}
		}
		wp_delete_post($post->ID, true);
	}
}

echo('done');
// ============ End of Program ============
if ($boolLockFileSystemEnabled) {
	unlink($lockFile);
}
?>
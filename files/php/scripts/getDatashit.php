<?php
# WordPress
if ( $wpLoad = dirname( __DIR__, 6 ) . '/wp/wp-load.php' and file_exists( $wpLoad ) ) {require_once( $wpLoad );}
elseif ( $wpLoad = dirname( __DIR__, 6 ) . '/wp-load.php' and file_exists( $wpLoad ) ) {require_once( $wpLoad );}

# Classes / Functions
require_once( dirname( __DIR__, 1 ) . '/includes/classes.php' );
require_once( dirname( __DIR__, 1 ) . '/includes/functions.php' );

// ============ Declaring Variables ============
# Variables
$meta_keys1 = [];
$meta_keys2 = [];

$bouwTypeIds = [];

# Limits
// Changing the execution time and memory limit
ini_set( 'max_execution_time', '0' );
ini_set( 'memory_limit', '-1' );

# Globals
global $wpdb;

// ============ Start of Program ============
$Ids = $wpdb->get_results("SELECT ID FROM wp_posts WHERE post_type = 'nieuwbouw' AND post_parent = 0", ARRAY_A);

foreach($Ids as $id) {
	$bouwTypeIds[] = $wpdb->get_results("SELECT ID FROM wp_posts WHERE post_type = 'nieuwbouw' AND post_parent = ".$id['ID'], ARRAY_A);
}
# Make it a single array
$bouwTypeIds = array_merge(...$bouwTypeIds);

foreach ($bouwTypeIds as $id) {
	$meta_keys1[] = get_post_meta($id['ID']);
}

# Remove duplicates
$meta_keys1 = array_unique($meta_keys1);

foreach($meta_keys1 as $meta_key) {
	$meta_keys2[] = array_keys($meta_key);
}

pre($meta_keys2);
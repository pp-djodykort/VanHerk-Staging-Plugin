<?php
/*
 * Plugin Name: Pixelplus - OG Koppeling
 * Plugin URI: https://pixelplus.nl/
 * Description: Plugin for Pixelplus to sync data from Van Herk database to Van Herk site
 * Version: 0.1
 * Author: Pixelplus - Djody
 * Author URI: https://djody.nl/
*/

// ========= Imports =========
include_once 'files/php/includes/classes.php';
include_once 'files/php/includes/functions.php';

// ============ Activation and Deactivation and Uninstall ============
$activateAndDeactivate = new OGActivationAndDeactivation();
register_activation_hook(__FILE__, array($activateAndDeactivate, 'activate'));
register_deactivation_hook(__FILE__, array($activateAndDeactivate, 'deactivate'));
register_uninstall_hook(__FILE__, 'OGUninstallPlugin');

// ============ Classes initialisation ============
$OGPostTypes = new OGPostTypes();
//$menu = new OGMenu();

// ============ Start ============
//adminNotice('success', getLoadTime());
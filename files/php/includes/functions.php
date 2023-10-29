<?php
// ========= Imports =========
include_once 'classes.php';

// ============ HTML Functions ============
function pre($input): void {
	echo('<pre>'); print_r($input); echo('</pre>');
}
function br(): void {
	echo("<br/>");
}
function htmlAdminHeader($title): void {
	echo("
		<head>
			<link rel='stylesheet' href='".plugins_url('css/bootstrap.min.css', dirname(__DIR__))."'>
			<link rel='stylesheet' href='".plugins_url('css/style.css', dirname(__DIR__))."'>
		</head>	
				<!-- Showing the header -->
		<header>
			<div class='container-fluid'>
	            <div class='div-Header'>
	                <div class='floatLeft'><h1><b>$title</b></h1></div>
	            </div>
	        </div>
		</header>
		<hr/>
		");
}
function htmlAanbodEditorHeader($leftSideHTML, $rightSideHTML, $extraHTMLNotice=''): void {
	echo("
		<head>
			<link rel='stylesheet' href='".plugins_url('css/bootstrap.min.css', dirname(__DIR__))."'>
			<link rel='stylesheet' href='".plugins_url('css/style.css', dirname(__DIR__))."'>
		</head>
		<!-- Showing the notice -->
		$extraHTMLNotice	
		
		<!-- Showing the header -->
		<header>
			<div class='container-fluid'>
	            <div class='div-Header'>
	                <div class='floatLeft'>$leftSideHTML</div>
	                <div class='floatRight'>$rightSideHTML</div>
	            </div>
	        </div>
		</header>
		<hr/>
		");
}
function htmlAdminFooter($title=''): void {
	echo("
	<!-- Bootstrap -->
	<script src='".plugins_url('js/bootstrap.bundle.min.js', dirname(__DIR__))."'></script>
	<!-- JQuery -->
	<script src='".plugins_url('js/jquery-3.7.0.min.js', dirname(__DIR__))."'></script>
	");
}
function adminNotice($type, $input): void {
	if ($type == "error") {
		add_action('admin_notices', function() use ($input) {
			echo ("<div class='alert alert-danger' role='alert'>"); print_r($input); echo ("</div>");
		});
	}
	else if ($type == "success") {
		add_action('admin_notices', function() use ($input) {
			echo ("<div class='alert alert-success' role='alert'>"); print_r($input); echo ("</div>");
		});
	}
	else if ($type == "warning") {
		add_action('admin_notices', function() use ($input) {
			echo ("<div class='alert alert-warning' role='alert'>"); print_r($input); echo ("</div>");
		});
	}
	else if ($type == "info") {
		add_action('admin_notices', function() use ($input) {
			echo ("<div class='alert alert-info' role='alert'>"); print_r($input); echo ("</div>");
		});
	}
	else {
		add_action('admin_notices', function() use ($input) {
			echo ("<div class='alert alert-primary' role='alert'>"); print_r($input); echo ("</div>");
		});
	}
}

// ============ JS Functions ============
function hidePasswordByName($name): void {
	echo("
    <script>
        // ======== Declaring Variables ========
        let passwordTextField = document.getElementsByName('$name')[0];
        
        // ======== Functions ========
        function showPassword() {
            if (passwordTextField.type === 'password') {
                passwordTextField.type = 'text';
                document.getElementsByName('$name')[0].type = 'text';
                document.getElementsByClassName('eye')[0].src = '" .plugins_url('img/eye-slash.svg', dirname(__DIR__))."';
            }
            else {
                passwordTextField.type = 'password';
                document.getElementsByClassName('eye')[0].src = '".plugins_url('img/eye.svg', dirname(__DIR__))."';
            }
        }
        
        // ======== Start of Function ========
        // Hide password
        passwordTextField.type = 'password';
        
        // Creating a test button
        button = document.getElementsByName('$name')[0].insertAdjacentHTML('afterend', '<img width=\"37px\" src=\"".plugins_url('img/eye.svg', dirname(__DIR__))."\" alt=\"Show Password\" class=\"eye\" onclick=\"showPassword()\">');
        // Giving the button a cursor pointer
        document.getElementsByClassName('eye')[0].style.cursor = 'pointer';
        // A bit of margin to the right
        document.getElementsByClassName('eye')[0].style.marginLeft = '14px';
    </script>
    ");
}

// ============ Normal Functions ============
function getAanbodTitle($postType): string {
	return "Aanbod &raquo ".($postType == 'bog' ? strtoupper($postType) : ucfirst($postType));
}
function aanbodEditor_showStatusData($postType, $postID, $type): void {
	// ======== Declaring Variables ========
	# Post
	$postmeta = get_post_meta($postID);

	# Strings
	if (strtolower($postType) == OGVanHerkSettingsData::$postTypeNieuwbouw) {
		$keyRealworksStatus = OGVanHerkPostTypeData::customPostTypes()["$postType"]['database_tables'][$type]['ObjectStatus_database'];
		$keyTiaraID = OGVanHerkPostTypeData::customPostTypes()["$postType"]['database_tables'][$type]['ID'];
	}
	else {
		$keyRealworksStatus = OGVanHerkPostTypeData::customPostTypes()["$postType"]['database_tables']['object']['ObjectStatus_database'];
		$keyTiaraID = OGVanHerkPostTypeData::customPostTypes()["$postType"]['database_tables']['object']['ID'];
	}

	// ======== Start of Function ========
	;
	echo( "
		<table style='border: 1px solid black; border-collapse: collapse;'>
			<!-- TiaraID -->
			<tr>
				<th style='border: 1px solid black; padding: 5px 0.7vw 5px 5px;'>Tiara id</th>
				<td style='border: 1px solid black; padding: 5px 1vw 5px 1vw;'>".$postmeta[$keyTiaraID][0]."</td>
			</tr>
			<!-- Realworks status -->
			<tr>
				<th style='border: 1px solid black; padding: 5px 0.7vw 5px 5px;'>Realworks status</th>
				<td style='border: 1px solid black; padding: 5px 1vw 5px 1vw;'>".$postmeta[$keyRealworksStatus][0]."</td>
			</tr>
		</table>
		");
}
function getJSONFromAPI($url, $args=null) {
	// ======== Start of Function ========
	// Get data from API
	$response = wp_remote_get($url, $args);

	if (is_wp_error($response)) {
		$error_message = $response->get_error_message();
		$response_code = wp_remote_retrieve_response_code($response);
		$response_body = wp_remote_retrieve_body($response);
		// Log or display the error information for debugging
		error_log("WP_Error: $error_message, Response Code: $response_code, Response Body: $response_body");

		// Return data
		return $response;
	}
	else {
		return json_decode($response['body'], true);
	}
}
function getLoadTime(): string {
	// tell me how much time this took
	$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
	return "This page took $time seconds to load.";
}
function removeParentheses ($string): array|string {
	// ======== Start of Function ========
	// Removing the parentheses from the string and returning it
	return str_replace(")", "", str_replace("(", "", $string));
}
function isConditional($dbString): bool {
	// ======== Start of Function ========
	# Check if the string is empty or null
	if (empty($dbString) || $dbString == null) {
		return false;
	}
	# Checking if the string is an coditional based off the first and last character
	elseif (str_starts_with($dbString, "(") && str_ends_with($dbString, ")")){
		return true;
	}
	else {
		return false;
	}
}
function isNumericBasedOffMetaKey($meta_key): bool {
	// ======== Declaring Variables ========
	# Globals
	global $wpdb;

	// ======== Start of Function ========
	$meta_values = $wpdb->get_col($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s", $meta_key));
	foreach ($meta_values as $meta_value) {
		if (!is_numeric($meta_value)) {
			return false;
		}
	}

	return true;
}
function getThumbnailOfPost($postID) {
	// ======== Declaring Variables ========
	$postMedia = new WP_Query([
		'post_type' => 'attachment',
		'posts_per_page' => -1,
		'post_status' => 'any',
		'post_parent' => $postID,
		'orderby' => 'menu_order',
		'order' => 'ASC'
	]);

	// ======== Start of Function ========
	if ($postMedia->have_posts()) {
		# Getting the hoofdfoto
		foreach($postMedia->posts as $post) {
			if (str_contains( $post->post_excerpt, 'HOOFDFOTO')) {
				# Making it thumbnail sized
				return wp_get_attachment_image_src($post->ID)[0] ?? '';
			}
		}
	}
	return '';
}
function aanbodEditor_showMedia($postID): void {
	// ======== Declaring Variables ========
	# WP Query
	$postMedia = new WP_Query([
		'post_type' => 'attachment',
		'posts_per_page' => -1,
		'post_status' => 'any',
		'post_parent' => $postID,
		'orderby' => 'menu_order',
		'order' => 'ASC'
	]);
	# Strings
	$carouselWidth = '750px';
	$maxHeight = '500px';
	$hoofdFoto = '';

	# Bools
	$boolHoofdFotoShown = false;

	// ======== Start of Function ========
	if ($postMedia->have_posts()) {
		# Getting the hoofdfoto & filtering the other posts
		foreach($postMedia->posts as $post) {
			# Hoofdfoto
			if (str_contains( $post->post_excerpt, 'HOOFDFOTO')) {
				# Making it thumbnail sized
				$hoofdFoto = wp_get_attachment_image_src($post->ID, 'thumbnail')[0] ?? '';
				# Deleting it from the array
				unset($postMedia->posts[array_search($post, $postMedia->posts)]);

				break;
			}

			# Deleting the other posts besides the normal pictures and video's
			if (!str_contains( $post->post_excerpt, 'FOTO') && !str_contains( $post->post_excerpt, 'VIDEO')) {
				unset($postMedia->posts[array_search($post, $postMedia->posts)]);
			}
		}

		# Showing the media ?>
		<div style='width: <?= $carouselWidth ?>; background-color: #e9ecef; border: 2.75px solid #adb9de; border-radius: 0.25rem;' id='carouselExample' class='carousel slide'>
			<!-- Items -->
			<div class='carousel-inner'>
				<!-- Hoofdfoto -->
				<?php if(!empty($hoofdFoto)): ?>
					<div style='height:<?= $maxHeight ?>;' class='carousel-item active'>
						<img style='height:<?= "-webkit-fill-available"?>;' src='<?= $hoofdFoto ?>' class='mx-auto d-block' alt='Hoofdfoto niet beschikbaar'>
					</div>
					<?php $boolHoofdFotoShown = true; ?>
				<?php endif; ?>

				<!-- Media -->
				<?php foreach($postMedia->posts as $post): ?>
					<div style='height:<?= $maxHeight ?>;' class='carousel-item <?php echo($boolHoofdFotoShown ? '' : 'active'); ?>'>
						<img style='height:<?= "-webkit-fill-available"?>;' src='<?= wp_get_attachment_image_src($post->ID)[0] ?>' class='mx-auto d-block' alt='Media niet beschikbaar'>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Buttons -->
			<button class='carousel-control-prev' type='button' data-bs-target='#carouselExample' data-bs-slide='prev'>
				<span class='carousel-control-prev-icon' aria-hidden='true'></span>
				<span class='visually-hidden'>Previous</span>
			</button>
			<button class='carousel-control-next' type='button' data-bs-target='#carouselExample' data-bs-slide='next'>
				<span class='carousel-control-next-icon' aria-hidden='true'></span>
				<span class='visually-hidden'>Next</span>
			</button>
		</div> <?php
	}
}
function checkIfAanbodColumnThumbnail($column, $post_id): void {
	if (strtolower($column) == 'thumbnail') {
		$imgSource = getThumbnailOfPost($post_id);
		if (!empty($imgSource)) {
			echo("<img style='width: ".'-webkit-fill-available'.";' src='$imgSource' alt='⠀Thumbnail niet beschikbaar'/>");
		}
		else {
			echo("<p style='color: red'>Geen media aanwezig</p>");
		}
	}
}
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
function htmlHeader($title): void {
    echo("
	<head>
		<link rel='stylesheet' href='".plugins_url('css/bootstrap.min.css', dirname(__DIR__))."'>
		<link rel='stylesheet' href='".plugins_url('css/style.css', dirname(__DIR__))."'>
	</head>
	<header>
		<div class='container-fluid'>
			<!-- Having the logo and title next to each other -->
			<img src='".plugins_url('img/pixelplus-logo.jpg', dirname(__DIR__))."' alt='Pixelplus Logo'>
		
            <div class='div-Header'>
                <span class='floatLeft'><h1><b>$title</b></h1></span>
                <span class='floatRight'><h5>".welcomeMessage()."</h5></span>
            </div>
        </div>
	</header>
	<hr/>
	");
}
function htmlFooter($title): void {
    echo("
	<!-- Bootstrap -->
	<script src='".plugins_url('js/bootstrap.min.js', dirname(__DIR__))."'></script>
	");
}
function welcomeMessage(): string {
    $welcomeMessage = "Welkom";
    $wpUser = _wp_get_current_user();

    if ($wpUser->user_firstname != "") {
        $welcomeMessage .= " ".$wpUser->user_firstname;
    }
    else {
        $welcomeMessage .= " ".$wpUser->user_login;
    }
    return $welcomeMessage;
}

function adminNotice($type, $strInput): void {
    if ($type == "error") {
        add_action('admin_notices', function() use ($strInput) {
            echo ("<div class='alert alert-danger' role='alert'>"); print_r($strInput); echo ("</div>");
        });
    }
    else if ($type == "success") {
        add_action('admin_notices', function() use ($strInput) {
            echo ("<div class='alert alert-success' role='alert'>"); print_r($strInput); echo ("</div>");
        });
    }
    else if ($type == "warning") {
        add_action('admin_notices', function() use ($strInput) {
            echo ("<div class='alert alert-warning' role='alert'>"); print_r($strInput); echo ("</div>");
        });
    }
    else if ($type == "info") {
        add_action('admin_notices', function() use ($strInput) {
            echo ("<div class='alert alert-info' role='alert'>"); print_r($strInput); echo ("</div>");
        });
    }
    else {
        add_action('admin_notices', function() use ($strInput) {
            echo ("<div class='alert alert-primary' role='alert'>"); print_r($strInput); echo ("</div>");
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
function getJSONFromAPI($url, $args=null) {
	// ======== Start of Function ========
	// Get data from API
	$data = json_decode(wp_remote_get($url, $args)['body'], true);

	// Return data
	return $data;
}
function getLoadTime(): string {
    // tell me how much time this took
    $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
    return "This page took $time seconds to load.";
}
function removeParentheses ($string) {
	// ======== Start of Function ========
	// Removing the parentheses from the string
	$string = str_replace("(", "", $string);
	$string = str_replace(")", "", $string);

	// Return the string
	return $string;
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
<?php
// ================== Imports ==================

use function PHPSTORM_META\type;

include_once("functions.php");

// ==== Activation and Deactivation (Uninstallation is in the functions.php because it needs to be a static function) ====
class OGActivationAndDeactivation {
    // ======== Activation ========
    function activate() {
        $this->registerSettings();
        $this->createCacheFiles();
    }

    // ======== Deactivation ========
    function deactivate()
    {

    }

    // ============ Functions ============
    // A function for registering base settings of the unactivated plugin as activation hook.
    function registerSettings() {
        // ==== Declaring Variables ====
        $settingData = new OGSettingsData();

        // ==== Start of Function ====
        // Registering settings
        foreach ($settingData->settings as $settingName => $settingValue) {
            add_option($settingData->settingPrefix.$settingName, $settingValue);
        }
    }

    function createCacheFiles() {
        // ==== Declaring Variables ====
        # Classes
        $settingsData = new OGSettingsData();

        # Variables
        $cacheFolder = plugin_dir_path(dirname(__DIR__, 1)).$settingsData->cacheFolder;

        // ==== Start of Function ====
        // Creating the cache files
        foreach ($settingsData->cacheFiles as $cacheFile) {
            // Creating the cache folder if it doesn't exist
            if (!file_exists($cacheFolder)) {
                mkdir($cacheFolder, 0777, true);
            }

            // Creating the cache file if it doesn't exist
            if (!file_exists($cacheFolder.$cacheFile)) {
                $file = fopen($cacheFolder.$cacheFile, 'w');
                fwrite($file, '');
                fclose($file);
            }
        }
    }
}

// ==== Data Classes ====
class OGPostTypeData {
    // ============ Begin of Class ============
    function customPostTypes() {
        // ===== Declaring Variables =====
        # Variables
        $customPostTypes = array(
            // Post Type 1
            /* post_type */'wonen' => array(
                'post_type_args' => array(
                    'labels' => array(
                        'name' => 'OG Wonen Objecten',
                        'singular_name' => 'OG Wonen Object',
                        'add_new' => 'Nieuwe toevoegen',
                        'add_new_item' => 'Nieuw OG Wonen Object toevoegen',
                        'edit_item' => 'OG Wonen Object bewerken',
                        'new_item' => 'Nieuw OG Wonen Object',
                        'view_item' => 'Bekijk OG Wonen Object',
                        'search_items' => 'Zoek naar OG Wonen Objecten',
                        'not_found' => 'Geen OG Wonen Objecten gevonden',
                        'not_found_in_trash' => 'Geen OG Wonen Objecten gevonden in de prullenbak',
                        'parent_item_colon' => '',
                        'menu_name' => 'Wonen'
                    ),
                    'post_type_meta' => array(
                        'meta_box_title' => 'OG Wonen Object',
                        'meta_box_id' => 'og-wonen-object',
                        'meta_box_context' => 'normal',
                        'meta_box_priority' => 'high',
                        'meta_box_fields' => array(
                            'OG Wonen Object' => array(
                                'type' => 'text',
                                'id' => 'og-wonen-object',
                                'name' => 'og-wonen-object',
                                'label' => 'OG Wonen Object',
                                'placeholder' => 'OG Wonen Object',
                                'description' => 'OG Wonen Object',
                                'value' => '',
                                'required' => true
                            )
                        )
                    ),
                    'public' => true,
                    'seperate_table' => true,
                    'has_archive' => true,
                    'publicly_queryable' => true,
                    'query_var' => true,
                    'capability_type' => 'post',
                    'hierarchical' => false,
                    'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
                    'show_in_menu' => 'pixelplus-og-plugin-aanbod',
                    'taxonomies' => array('category', 'post_tag')
                ),
                'database_tables' => array(
                    'tbl_og_wonen2' => array(
                        'ID' => 'object_ObjectCode',
                        'post_title' => 'objectDetails_Adres_NL_Straatnaam;objectDetails_Adres_NL_Huisnummer;objectDetails_Adres_NL_Woonplaats',
                        'post_content' => 'objectDetails_Aanbiedingstekst',
                        'datum_gewijzigd' => 'datum_gewijzigd',
                    )
                )
            ),
            // Post Type 2
            /* post_type */'bedrijven' => array(
                'post_type_args' => array(
                    'labels' => array(
                        'name' => 'OG BOG Objecten',
                        'singular_name' => 'OG BOG Object',
                        'add_new' => 'Nieuwe toevoegen',
                        'add_new_item' => 'Nieuw OG BOG Object toevoegen',
                        'edit_item' => 'OG BOG Object bewerken',
                        'new_item' => 'Nieuw OG BOG Object',
                        'view_item' => 'Bekijk OG BOG Object',
                        'search_items' => 'Zoek naar OG BOG Objecten',
                        'not_found' => 'Geen OG BOG Objecten gevonden',
                        'not_found_in_trash' => 'Geen OG BOG Objecten gevonden in de prullenbak',
                        'parent_item_colon' => '',
                        'menu_name' => 'BOG'
                    ),
                    'public' => true,
                    'has_archive' => true,
                    'publicly_queryable' => true,
                    'query_var' => true,
                    'capability_type' => 'post',
                    'hierarchical' => false,
                    'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
                    'show_in_menu' => 'pixelplus-og-plugin-aanbod',
                    'taxonomies' => array('category', 'post_tag')
                ),
                'database_tables' => array(
                    'ppog_databog2' => array(
                        'ID' => 'object_ObjectCode',
                        'post_title' => 'objectDetails_Adres_Straatnaam;objectDetails_Adres_Huisnummer;objectDetails_Adres_Woonplaats',
                        'post_content' => 'objectDetails_Aanbiedingstekst',
                        'datum_gewijzigd' => 'datum_gewijzigd',
                    )
                )
            ),
        );

        // ===== Start of Construct =====
        // Returning the array
        return $customPostTypes;
    }
}
class WPColorScheme {
    // ================ Declaring Variables ================
    public array $mainColors = array(
        'light' => 3,
        'modern' => 1,
        'coffee' => 2,
        'ectoplasm' => 2,
        'midnight' => 3,
        'ocean' => 2,
        'sunrise' => 2,
        '80s-kid' => 1,
        'adderley' => 2,
        'aubergine' => 3,
        'blue' => 1,
        'contrast-blue' => 0,
        'cruise' => 3,
        'flat' => 2,
        'kirk' => 0,
        'lawn' => 3,
        'modern-evergreen' => 3,
        'primary' => 3,
        'seashore' => 3,
        'vinyard' => 3
    );

    // ================ Begin of Class ================
    function returnColor(): string
    {
        // ======== Declaring Variables ========
        global $_wp_admin_css_colors;
        $WPColorScheme = get_user_option('admin_color');
        $boolResult = false;

        // ======== Start of Function ========
        foreach ($this->mainColors as $key => $value) {
            if ($key == $WPColorScheme) {
                return $_wp_admin_css_colors[$WPColorScheme]->colors[$this->mainColors[$key]];
            }
        }
        return $_wp_admin_css_colors['fresh']->colors[2];
    }
}
class OGSettingsData {
    // ============ Declare Variables ============
    // Strings
    public $settingPrefix = 'ppOG_'; // This is the prefix for all the settings used within the OG Plugin
    public $cacheFolder = 'caches/'; // This is the folder where all the cache files are stored within the server/ftp
    // Arrays
    public array $apiURLs = [
        'license' => 'https://og-feeds2.pixelplus.nl/api/validate.php',
        'syncTimes' => 'https://og-feeds2.pixelplus.nl/api/latest.php'
    ];

    public array $cacheFiles = [
        'licenseCache' => 'licenseCache.json', // This is the cache file for the checking the Licence key
    ];

    public array $settings = [
        /* Setting Name */'licenseKey' => /* Default Value */       '',     // License Key
    ];
    public array $adminSettings = [
        // Settings 1
        /* Option Group= */ 'ppOG_AdminOptions' => [
            // General information
            'settingPageSlug' => 'pixelplus-og-plugin-settings',
            // Sections
            'sections' => [
                // Section 1 - Licentie section
                /* Section Title= */'Licentie' => [
                    'sectionID' => 'ppOG_SectionLicence',
                    'sectionCallback' => 'htmlLicenceSection',
                    // Fields
                    'fields' => [
                        // Field 1 - Licentie sleutel
                        /* Setting Field Title= */'Licentie Sleutel' => [
                            'fieldID' => 'ppOG_licenseKey',
                            'fieldCallback' => 'htmlLicenceKeyField',
                        ]
                    ]
                ]
            ]
        ]
    ];

    // ============ HTML Functions ============
    // Sections
    function htmlLicenceSection(): void { ?>
        <p>De licentiesleutel die de plugin activeert</p>
    <?php }
    // Fields
    function htmlLicenceKeyField(): void {
        // ===== Declaring Variables =====
        // Vars
        $licenseKey = get_option($this->settingPrefix.'licenseKey');

        // ===== Start of Function =====
        // Check if licenseKey is empty
        if ($licenseKey == '') {
            // Display a message
            echo('De licentiesleutel is nog niet ingevuld.');
        }
        echo(" <input type='text' name='".$this->settingPrefix."licenseKey' value='".esc_attr($licenseKey)."' ");
    }
}
class OGMapping {
    // ================ Constructor ================
    function __construct() {

    }

    // ================ Begin of Class ================
    function mapMetaData($postTypeName, $object, $databaseKeys) {
        // ======== Declaring Variables ========
        # Classes
        global $wpdb;


        
        # Vars
	    $OGTableRecord = $object;
        $mappingTable = $wpdb->get_results("SELECT * FROM `og_mapping".$postTypeName."`", ARRAY_A);
        // ======== Start of Function ========
        # Getting rid of all the useless and empty values
        foreach ($OGTableRecord as $OGTableRecordKey => $OGTableRecordValue) {
            # Check if the value is empty and if so remove the whole key from the OBJECT
            if (is_null($OGTableRecordValue) or empty($OGTableRecordValue)) {
                unset($OGTableRecord->{$OGTableRecordKey});
            }
        }

        # Looping through again to map the values now that the empty values are gone
        foreach ($OGTableRecord as $OGTableRecordKey => $OGTableRecordValue) {
            # Looping through the mapping table
            foreach ($mappingTable as $mappingTableValue) {
	            # Looking if there is a direct match
                if ($OGTableRecordKey == $mappingTableValue['pixelplus']) {
                    # Changing the old key to the new key
                    $OGTableRecord->{$mappingTableValue['vanherk']} = $OGTableRecordValue;
                    # Removing the old key
                    unset($OGTableRecord->{$OGTableRecordKey});
                }

                #
            }
        }

        echo('<pre>'); print_r($OGTableRecord); echo('</pre>');

        # Return the object
        return $object;
    }
}

// ============ Start of Classes ============
class OGMenu {
    // ============ Constructor ============
    function __construct() {
        // ========== Start of Function ==========
        add_action('admin_menu', array($this, 'addMenu'));
    }

    // ================ Begin of Class ================
    # ==== Functions ====
    # This function is for adding the menu to the admin panel
    function addMenu() {
        // ======== Declaring Variables ========

        // ======== Start of Function ========
        add_menu_page(
            'Pixelplus OG Plugin',
            'Pixelplus OG Plugin',
            'manage_options',
            'pixelplus-og-plugin',
            array($this, 'htmlMenu'),
            'dashicons-admin-generic',
            100
        );
	    // ==== Items OG Aanbod ====
	    // Menu Item: OG Aanbod Dashboard
	    add_menu_page(
		    'OG Aanbod',
		    'OG Aanbod',
		    'manage_options',
		    'pixelplus-og-plugin-aanbod',
		    array($this, 'HTMLOGAanbodDashboard'),
		    'dashicons-admin-multisite',
		    40);
	    // First sub-menu item name change
	    add_submenu_page(
		    'pixelplus-og-plugin-aanbod',
		    'Aanbod Dashboard',
		    'Dashboard',
		    'manage_options',
		    'pixelplus-og-plugin-aanbod',
		    array($this, 'HTMLOGAanbodDashboard'),
		    0
	    );
    }



    # ==== HTML ====
    function htmlMenu(): void {
        // ========== Declaring Variables ==========
        # Classes
        $ogTableMapping = new OGTableMappingDisplay();

        # Vars
        $postWonen_columns = $ogTableMapping->getPostColumns('wonen');
        $tableWonen_columns = $ogTableMapping->getTableColumns('tbl_OG_wonen');

        $postBOG_columns = $ogTableMapping->getPostColumns('bedrijven');
        $tableBOG_columns = $ogTableMapping->getTableColumns('tbl_OG_bog');

        // ========== Start of Function ==========
        htmlHeader('Pixelplus OG Plugin');
        echo("
            <div class='container'>
                <div class='row'>
                    <div class='col'>");
                        if (!empty($postWonen_columns)) {
	                        foreach ($postWonen_columns as $key => $value) {
		                        echo("<p>".$key."</p>");
	                        }
                        }
echo("              </div>
                    <div class='col'>");
                        if (!empty($postBOG_columns)) {
	                        foreach ($tableWonen_columns as $key => $value) {
		                        echo("<p>".$value->Field."</p>");
	                        }
                        }
echo("              </div>
                </div>
            </div>
        ");
        htmlFooter('Pixelplus OG Plugin');
    }
	// OG Aanbod
	function HTMLOGAanbodDashboard(): void { htmlHeader('OG Aanbod Dashboard'); ?>
        <p>dingdong bishass</p>
		<?php htmlFooter('OG Aanbod Dashboard');}

}
class OGTableMappingDisplay {
    // ============ Constructor ============
    function __construct() {
        // ========== Start of Function ==========
    }

    // ================ Begin of Class ================
    # This function is for extracting all the columns of the wp_posts table to eventually create a mapping system.
    function getPostColumns($postType) {
        // ======== Declaring Variables ========
        # Vars
        $columns = array();
        $posts = new WP_Query(array(
            'post_type' => $postType,
            'post_status' => 'any',
            'posts_per_page' => 1
        ));

        // ======== Start of Function ========
        // ======== Wonen ========
        # Getting all the columns of the first item's metatable
        if ($posts->have_posts()) {
	        $columns = get_post_meta($posts->posts[0]->ID);
        }

        return $columns;
    }
    function getTableColumns($tableName) {
        // ======== Declaring Variables ========
        # Classes
        global $wpdb;

        # Vars
        $table_columns = $wpdb->get_results("SHOW COLUMNS FROM ".$tableName);

        // ======== Start of Function ========
        return $table_columns;
    }
}
class OGPostTypes {
	// ==== Declaring Variables ====

	// ==== Start of Class ====
	function __construct() {
		add_action('init', array($this, 'createPostTypes'));
		add_action('init', array($this, 'checkMigrationPostTypes'));
	}

	// =========== Functions ===========
	function createPostTypes() {
		// ==== Declaring Variables ====
		// Classes
		$postTypeData = new OGPostTypeData();
		$postTypeData = $postTypeData->customPostTypes();

		// ==== Start of Function ====

		// Create the OG Custom Post Types (if the user has access to it)
		foreach($postTypeData as $postType => $postTypeArray) {
			register_post_type($postType, $postTypeArray['post_type_args']);
		}
	}
	# This function is for checking if the post types are migrated to different tables / metadata tables
	function checkMigrationPostTypes() {
		// ==== Declaring Variables ====
		# Classes
		global $wpdb;
		$postTypeData = new OGPostTypeData();
		$postTypeData = $postTypeData->customPostTypes();

		# Variables
		$defaultPrefix = "wp_cpt_";
		$sqlCheck = "SHOW TABLES LIKE '".$defaultPrefix."";

		// ==== Start of Function ====
		// Checking
		foreach ($postTypeData as $postType => $postTypeArray) {
			// Preparing the statement
			$result = $wpdb->get_results($sqlCheck.$postType."'");

			if (empty($result)) {
				// Migrating the data
				adminNotice('error', 'Please migrate the '.strtoupper($postType).' custom post type to the new table structure using the CPT Tables Plugin.');
			}
		}
	}
}

class OGOffers {
	// ==== Start of Class ====
	function __construct() {
        # Use this one if it is going to be run on the site itself.
        add_action('admin_init', array($this, 'examinePosts'));

        # Use this one if it is going to be a cronjob.
//        $this->examinePosts();
	}

	// ================ Functions ================
	function getNames($post_data, $object, $databaseKeys) {
		// ======== Declaring Variables ========
		$postTitle = explode(';', $databaseKeys['post_title']);

		// ======== Start of Function ========
		# Post Title
		foreach ($postTitle as $title) {
			$post_data['post_title'] .= $object->{$title}.' ';
		}
		// Removing the last space
		$post_data['post_title'] = rtrim($post_data['post_title']);

		# Post Content
		$post_data['post_content'] = $object->{$databaseKeys['post_content']};

		return $post_data;
	}

	function createPost($postTypeName, $object, $databaseKeys) {
		// ======== Declaring Variables ========
        # Classes
        $ogMapping = new OGMapping();
        # Vars
		$post_data = [
			'post_type' => $postTypeName,
			'post_title' => '',
			'post_content' => '',
			'post_status' => 'draft'
		];
		$post_data = $this->getNames($post_data, $object, $databaseKeys);
		$object = $ogMapping->mapMetaData($postTypeName, $object, $databaseKeys);

		// ======== Start of Function ========
//		# Creating the post
//		$postID = wp_insert_post($post_data);
//
//		# Adding the post meta
//		foreach ($object as $key => $value) {
//			add_post_meta($postID, $key, $value);
//		}
//
//		# Adding meta data for images
//
//		# Publishing the post
//		wp_publish_post($postID);
	}

	function updatePost($postID, $object, $databaseKeys) {
		// ======== Declaring Variables ========
        # Classes
        $ogMapping = new OGMapping();

        # Vars
		$post_data = [
			'ID' => $postID,
			'post_title' => '',
			'post_content' => ''
		];

		$post_data = $this->getNames($post_data, $object, $databaseKeys);

		// ======== Start of Function ========
//		# Overwriting the post
//		wp_update_post($post_data);
//
//		# Updating the post meta
//		foreach ($object as $key => $value) {
//			update_post_meta($postID, $key, $value);
//		}
	}

	function checkPosts($objects, $databaseKeys, $postTypeName) {
		// ======== Start of Function ========
		foreach ($objects as $object) {
			// ==== Declaring Variables ====
			# Classes
			$postData = new WP_Query(([
				'post_type' => $postTypeName,
				'meta_key' => $databaseKeys['ID'],
				'meta_value' => $object->{$databaseKeys['ID']},
			]));

			# Variables
			$postExists = $postData->have_posts();

			if ($postExists) {
				$dataUpdatedPost = $postData->posts[0]->{$databaseKeys['datum_gewijzigd']};
			}

			// Database object
			$tiaraID = $object->{$databaseKeys['ID']};
			$dataUpdatedObject = $object->{$databaseKeys['datum_gewijzigd']};

			// ==== Start of Function ====
			if ($postExists) {
				// Checking if the post is updated
				if ($dataUpdatedPost != $dataUpdatedObject) {
					// Updating/overwriting the post
					$this->updatePost($postData->posts[0]->ID, $object, $databaseKeys);
				}
			} else {
				// Creating the post
				$this->createPost($postTypeName, $object, $databaseKeys);
			}
		}
	}

	function examinePosts() {
		// ======== Declaring Variables ========
		# Classes
		global $wpdb;
		$postTypeData = new OGPostTypeData();

		# Variables
		$beginTime = time();
		$postTypeData = $postTypeData->customPostTypes();

		// ======== Start of Function ========s
		foreach ($postTypeData as $postTypeName => $postTypeArray) {
			// ==== Declaring Variables ====
			$databaseTableName = key($postTypeArray['database_tables']);
			$databaseKeys = $postTypeArray['database_tables'][$databaseTableName];

			# Getting the database objects
			$objects = $wpdb->get_results("SELECT * FROM ".$databaseTableName."");

			# Removing every null out of the objects so Wordpress won't get crazy
			foreach ($objects as $key => $object) {
				foreach ($object as $key2 => $value) {
					if ($value == 'null' or $value == 'NULL' or $value == null) {
						$objects[$key]->{$key2} = '';
					}
				}
			}

			// ==== Start of Loop ====
			if (!empty($objects)) {
				// Looping through the objects and putting them in the right post type
				$this->checkPosts($objects, $databaseKeys, $postTypeName);
			}
		}

		// Putting in the database how much memory it ended up using maximum from bytes to megabytes
		$maxMemoryUsage = (memory_get_peak_usage(true) / 1024 / 1024);
		$memoryUsage = (memory_get_usage(true) / 1024 / 1024);
		$wpdb->insert('cronjobs', [
			'name' => 'OGOffers',
			# convert to megabytes
			'memoryUsageMax' => $maxMemoryUsage,
			'memoryUsage' => $memoryUsage,
			'datetime' => date('Y-m-d H:i:s', $beginTime),
			'duration' => round((time() - $beginTime) / 60, 2)
		]);
	}
}
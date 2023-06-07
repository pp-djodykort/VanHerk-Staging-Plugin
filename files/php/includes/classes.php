<?php
// ================== Imports ==================
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
                    'object' => array(
                        'tableName' => 'tbl_og_wonen',
                        'ID' => 'id',
                        'post_title' => 'objectDetails_Adres_NL_Straatnaam;objectDetails_Adres_NL_Huisnummer;objectDetails_Adres_NL_Woonplaats',
                        'post_content' => 'objectDetails_Aanbiedingstekst',
                        'datum_gewijzigd_database' => 'datum_gewijzigd',
                        'datum_gewijzigd_post' => 'ObjectUpdated',
                    ),
                    'media' => array(
                        'tableName' => 'tbl_OG_media',
	                    'search_id' => 'id_OG_wonen',
                        'object_keys' => array(
                            'objectTiara' => '_id',
                            'objectVestiging' => 'ObjectKantoor',
                        )
                    ),
	                # Only if mapping is neccesary uncomment the following lines and fill in the correct table name
                    'mapping' => array(
                        'tableName' => 'og_mappingwonen',
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
                    'object' => array(
                        'tableName' => 'ppog_databog',
                        'ID' => 'id',
                        'post_title' => 'objectDetails_Adres_Straatnaam;objectDetails_Adres_Huisnummer;objectDetails_Adres_Woonplaats',
                        'post_content' => 'objectDetails_Aanbiedingstekst',
                        'datum_gewijzigd_database' => 'datum_gewijzigd',
                        'datum_gewijzigd_post' => 'ObjectUpdated',
                    ),
                    'media' => array(
                        'tableName' => 'tbl_OG_media',
                        'search_id' => 'id_OG_bog',
                    ),
                    # Only if mapping is neccesary uncomment the following lines and fill in the correct table name
                    'mapping' => array(
                        'tableName' => 'og_mappingbedrijven',
                    ),
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
    function mapMetaData($postTypeName, $OGTableRecord, $databaseKeysMapping) {
        if (isset($databaseKeysMapping)) {
	        // ======== Declaring Variables ========
	        # Classes
	        global $wpdb;

	        # Vars
	        $mappingTable = $wpdb->get_results("SELECT * FROM `{$databaseKeysMapping['tableName']}`", ARRAY_A);

	        // ========================= Start of Function =========================
	        // ================ Cleaning the Tables/Records ================
	        # Getting rid of all the useless and empty values in the OBJECT
	        foreach ($OGTableRecord as $OGTableRecordKey => $OGTableRecordValue) {
		        # Check if the value is empty and if so remove the whole key from the OBJECT
		        if (is_null($OGTableRecordValue) or empty($OGTableRecordValue)) {
			        unset($OGTableRecord->{$OGTableRecordKey});
		        }
	        }
	        # Getting rid of all the useless and empty values in the MAPPING TABLE
	        foreach ($mappingTable as $mappingKey => $mappingTableValue) {
		        # Check if the value is empty and if so remove the whole key from the OBJECT
		        if (is_null($mappingTableValue['pixelplus']) or empty($mappingTableValue['pixelplus'])) {
			        unset($mappingTable[$mappingKey]);
		        }
	        }

	        // ================ Mapping the Data ================
	        foreach ($mappingTable as $mappingKey => $mappingValue) {
		        // ==== Checking conditional ====
		        if (str_starts_with($mappingValue['pixelplus'], '(') and str_ends_with($mappingValue['pixelplus'], ')')) {
			        // ==== Declaring Variables ====
			        $strTrimmedKey = trim($mappingValue['pixelplus'], '()');
			        $arrExplodedKey = explode('|', $strTrimmedKey);
			        $boolResult = false;

			        // ==== Start of Function ====
			        # Step 1: Looping through all the keys
			        foreach ($arrExplodedKey as $arrExplodedKeyValue) {
				        # Step 2: Check if the key even isset or empty in OG Record
				        if (isset($OGTableRecord->{$arrExplodedKeyValue}) and !empty($OGTableRecord->{$arrExplodedKeyValue})) {
					        # Step 3: Change the mapping table's value to just one key instead of making the the key an array/conditional
					        $mappingTable[$mappingKey]['pixelplus'] = $arrExplodedKeyValue;
					        $boolResult = true;
				        }
			        }
			        # Step 4: Check if the result is false and if so unset the whole key from the mapping table
			        if (!$boolResult) {
				        unset($mappingTable[$mappingKey]);
			        }
		        }
		        // ==== Checking concatinations ====
		        if (str_starts_with($mappingValue['pixelplus'], '{') and str_ends_with($mappingValue['pixelplus'], '}')) {
			        // ==== Declaring Variables ====
			        # Vars
			        $strTrimmedKey = trim($mappingValue['pixelplus'], '{}');
			        $arrExplodedKey = explode('+', $strTrimmedKey);
			        $strResult = '';

			        // ==== Start of Function ====
			        # Step 1: Looping through all the keys
			        foreach($arrExplodedKey as $arrExplodedKeyValue) {
				        # Step 2: Check if the key even isset or empty in OG Record
				        if (isset($OGTableRecord->{$arrExplodedKeyValue}) and !empty($OGTableRecord->{$arrExplodedKeyValue})) {
					        # Step 3: Add the value to the result string
					        $strResult .= $OGTableRecord->{$arrExplodedKeyValue}.' ';
				        }
			        }
			        # Step 5: Putting it in the mapping table as a default value
			        $mappingTable[$mappingKey]['pixelplus'] = "'".rtrim($strResult)."'";
		        }
	        }
	        # Looping through the mapping table with the updated values
	        foreach ($mappingTable as $mappingKey => $mappingValue) {
		        // ======== Checking default values ========
		        if (str_starts_with($mappingValue['pixelplus'], "'") and str_ends_with($mappingValue['pixelplus'], "'")) {
			        // ==== Declaring Variables ====
			        # Vars
			        $strTrimmedKey = trim($mappingValue['pixelplus'], "'");

			        // ==== Start of Function ====
			        # Step 1: Making a new key with the value of the old key
			        $OGTableRecord->{$mappingValue['vanherk']} = $strTrimmedKey;
			        # Step 2: Removing the old key
			        unset($OGTableRecord->{$mappingValue['pixelplus']});
		        }
	        }

	        # Direct matches
	        foreach ($OGTableRecord as $OGTableRecordKey => $OGTableRecordValue) {
		        foreach ($mappingTable as $mappingKey => $mappingValue) {
			        // ==== Checking direct match ====
			        if ($OGTableRecordKey == $mappingValue['pixelplus']) {
				        # Making a new key with the value of the old key
				        $OGTableRecord->{$mappingValue['vanherk']} = $OGTableRecordValue;
				        # Removing the old key
				        unset($OGTableRecord->{$OGTableRecordKey});
			        }
		        }
	        }
        }

        // ================ Returning the Object ================
        # Return the object
//	    echo("<pre>"); print_r($OGTableRecord); echo("</pre>");
        return $OGTableRecord;
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
        $tableWonen_columns = $ogTableMapping->getTableColumns('tbl_og_wonen');

        $postBOG_columns = $ogTableMapping->getPostColumns('bedrijven');
        $tableBOG_columns = $ogTableMapping->getTableColumns('ppog_databog');

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
        // add_action('admin_init', array($this, 'examinePosts'));

        # Use this one if it is going to be a cronjob.
       $this->examinePosts();
	}

	// ================ Functions ================
	function getNames($post_data, $object, $databaseKeys) {
		// ======== Declaring Variables ========
		$postTitle = explode(';', $databaseKeys['post_title']);

		// ======== Start of Function ========
		# Post Title
		foreach ($postTitle as $title) {
            # Checking if the title is full caps
            if ($object->{$title} == strtoupper($object->{$title})) {
                # Make it lowercase and capitalize the first letter
                $post_data['post_title'] .= ucfirst(strtolower($object->{$title})).' ';
            }
            else {
	            $post_data['post_title'] .= $object->{$title}.' ';
            }
		}
		// Removing the last space
		$post_data['post_title'] = rtrim($post_data['post_title']);

		# Post Content
		$post_data['post_content'] = $object->{$databaseKeys['post_content']};

		return $post_data;
	}

    function updateMedia($postID, $postTypeName, $object, $databaseKeysMedia): void {
        // ================ Declaring Variables ================
        # Classes
        global $wpdb;
        # Vars
        $mime_type_map = [
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf',
        ];
        $guid_url = get_site_url();

        $results = $wpdb->get_results("SELECT * FROM `".$databaseKeysMedia['tableName']."` WHERE `".$databaseKeysMedia['search_id']."` = ".$object->id."");

        // ================ Start of Function ================
        print("Searching for: ".$databaseKeysMedia['search_id']." with id: ".$object->id."<br><br>");

        $media_data = array();
        foreach ($results as $result) {
            // ======== Declaring Variables ========
            # Vars
            $post_title = "".$result->media_Id."-".$result->bestandsnaam."";
            $post_mime_type = $mime_type_map[$result->{'bestands_extensie'}];

            $media_url = "og_media/{$postTypeName}_{$object->{$databaseKeysMedia['object_keys']['objectVestiging']}}_{$object->{$databaseKeysMedia['object_keys']['objectTiara']}}/{$object->{$databaseKeysMedia['object_keys']['objectTiara']}}}_{$result->media_Id}.{$result->bestands_extensie}";

            $post_data = [
                'post_content' => '',
                'post_title' => $post_title,
                'post_excerpt' => strtoupper($result->{'media_Groep'}),
                'post_status' => 'inherit',
                'comment_status' => 'open',
                'ping_status' => 'closed',
                //'post_name' => $post_name,
                'post_parent' => $postID,
                'guid' => "{$guid_url}/{$media_url}",
                'menu_order' => $result->{'media_volgorde'},
                'post_type' => 'attachment',
                'post_mime_type' => $post_mime_type,
            ];
            $media_data[] = array(
                'post_data' => $post_data,
                'post_meta' => array(
                    '_wp_attached_file' => '/'.$media_url,
                    'file_url' => $media_url,
                    '_wp_attachment_metadata' => '',
                    'ObjectCode' => '',
                    'MediaType' => strtoupper($result->{'media_Groep'}),
                    'MediaName' => $post_title,
                    'MediaUpdated' => strtotime($result->{'datum_gewijzigd'}),
                    '_wp_attachment_image_alt' => '',
                    '_id' => $result->{'media_Id'},
                ),
            );
            print($media_url);
        }
        print('<br>');

        // Insert or update media files
        foreach ($media_data as $media) {
            $query = get_posts(array(
                'post_type' => 'attachment',
                'meta_key' => '_id',
                'meta_value' => $media['post_meta']['_id'],
            ));

            if (empty($query)) {
                $mediaID = wp_insert_post($media['post_data']);

                foreach ($media['post_meta'] as $key => $value) {
                    wp_set_object_terms($mediaID, $value, $key);
                }

                echo 'created ' . $mediaID . PHP_EOL;

            } else {
                $post_data = $media['post_data'];
                $post_data['ID'] = $query[0]->ID;
                wp_update_post($post_data);

                foreach ($media['post_meta'] as $key => $value) {
                    wp_set_object_terms($query[0]->ID, $value, $key);
                }

                echo 'updated ' . $post_data['ID'] . PHP_EOL;
            }
        }
    }

	function createPost($postTypeName, $object, $databaseKeysObject, $databaseKeysMedia, $databaseKeysMapping): void {
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
		$post_data = $this->getNames($post_data, $object, $databaseKeysObject);
		$object = $ogMapping->mapMetaData($postTypeName, $object, $databaseKeysMapping);

		// ======== Start of Function ========
		# Creating the post
		$postID = wp_insert_post($post_data);

		# Adding the post meta
		foreach ($object as $key => $value) {
			add_post_meta($postID, $key, $value);
		}

		# Adding meta data for images
        $this->updateMedia($postID, $postTypeName, $object, $databaseKeysMedia);

		# Publishing the post
		wp_publish_post($postID);
	}

	function updatePost($postTypeName, $postID, $object, $databaseKeysObject, $databaseKeysMedia, $databaseKeysMapping): void {
		// ======== Declaring Variables ========
        # Classes
        $ogMapping = new OGMapping();

        # Vars
		$post_data = [
			'ID' => $postID,
			'post_title' => '',
			'post_content' => ''
		];
		$post_data = $this->getNames($post_data, $object, $databaseKeysObject);
		$object = $ogMapping->mapMetaData($postTypeName, $object, $databaseKeysMapping);

		// ======== Start of Function ========
		# Overwriting the post
		wp_update_post($post_data);

		$this->updateMedia($postID, $postTypeName, $object, $databaseKeysMedia);

		# Updating the post meta
		foreach ($object as $key => $value) {
			update_post_meta($postID, $key, $value);
		}
	}

    function deleteUnneededPosts($postTypeName, $databaseKeysObject, $objectIDs) {
        // ======== Declaring Variables ========
        # Variables

        $posts = new WP_Query(([
            'post_type' => $postTypeName,
            'posts_per_page' => -1,
        ]));

        // ======== Start of Function ========
        # Getting all the post IDs from the meta data
        foreach ($posts->posts as $post) {
            // ==== Declaring Variables ====
            # Getting metadata
            $postMetaData = get_post_meta($post->ID);
            # Getting the post ID
            $postID = $postMetaData[$databaseKeysObject['ID']][0];

            // ==== Rest of loop ====
            # Checking if the post is in the database
            if (!in_array($postID, $objectIDs)) {
                # Delete the post
                wp_delete_post($post->ID, true);

                # Deleting every post with this as parent post
                $childPosts = new WP_Query(([
                    'post_type' => $postTypeName,
                    'posts_per_page' => -1,
                    'post_parent' => $post->ID,
                ]));

                foreach ($childPosts->posts as $childPost) {
                    print('Deleting child post: ' . $childPost->ID . '<br>');
                    wp_delete_post($childPost->ID, true);
                }
                print('Deleted post: ' . $post->ID . '<br>');
            }
            else {
                print('Post: ' . $postID . ' is still in the database<br>');
            }
        }
    }

	function checkPosts($postTypeName, $OGobjects, $databaseKeysObject, $databaseKeysMedia, $databaseKeysMapping): void {
        // ======== Declaring Variables ========
        # Vars
        $objectIDs = [];

		// ================ Start of Function ================
        # ======== Creating/Updating the posts ========
		foreach ($OGobjects as $object) {
			// ==== Declaring Variables ====
			# Variables
			$postData = new WP_Query(([
				'post_type' => $postTypeName,
				'meta_key' => $databaseKeysObject['ID'],
				'meta_value' => $object->{$databaseKeysObject['ID']},
			]));
			$postExists = $postData->have_posts();
			if ($postExists) {
				$dataUpdatedPost = $postData->posts[0]->{$databaseKeysObject['datum_gewijzigd_post']};
			}

			// Database object
			$tiaraID = $object->{$databaseKeysObject['ID']};
			$dataUpdatedObject = $object->{$databaseKeysObject['datum_gewijzigd_database']};

			// ==== Start of Function ====
			if ($postExists) {
				// Checking if the post is updated
				if ($dataUpdatedPost != $dataUpdatedObject) {
					// Updating/overwriting the post
					$this->updatePost($postTypeName, $postData->posts[0]->ID, $object, $databaseKeysObject, $databaseKeysMedia, $databaseKeysMapping);
				}
			} else {
				// Creating the post
				$this->createPost($postTypeName, $object, $databaseKeysObject, $databaseKeysMedia, $databaseKeysMapping);
			}
                
            # Adding the object ID to the array
            array_push($objectIDs, $object->{'id'});
		}

        # ======== Deleting the posts ========
         $this->deleteUnneededPosts($postTypeName, $databaseKeysObject, $objectIDs);
        
	}

	function examinePosts(): void {
		// ================ Declaring Variables ================
		# Classes
		global $wpdb;
		$postTypeData = new OGPostTypeData();

		# Variables
		$beginTime = time();
		$postTypeData = $postTypeData->customPostTypes();

		// ================ Start of Function ================
		foreach ($postTypeData as $postTypeName => $postTypeArray) {
			// ==== Declaring Variables ====
            # OG objects
            $databaseTableObject = $postTypeArray['database_tables']['object']['tableName'];
			$databaseKeysObject = $postTypeArray['database_tables']['object'];
            # Media
            $databaseKeysMedia = $postTypeArray['database_tables']['media'];
            # Mapping
            if (isset($postTypeArray['database_tables']['mapping'])) {
                $databaseKeysMapping = $postTypeArray['database_tables']['mapping'];
            }
            else {
                $databaseKeysMapping = null;
			}

			# Getting the database objects
			$OGobjects = $wpdb->get_results("SELECT * FROM ".$databaseTableObject."");
			# Removing every null out of the objects so Wordpress won't get crazy
			foreach ($OGobjects as $key => $object) {
				foreach ($object as $key2 => $value) {
					if ($value == 'null' or $value == 'NULL' or $value == null) {
						$OGobjects[$key]->{$key2} = '';
					}
				}
			}

			// ==== Start of Loop ====
			if (!empty($OGobjects)) {
				// Looping through the objects and putting them in the right post type
				$this->checkPosts($postTypeName, $OGobjects, $databaseKeysObject, $databaseKeysMedia, $databaseKeysMapping);
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
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
    function deactivate() {

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
						# TableName
						'tableName' => 'tbl_og_wonen',
						# Normal fields
						'ID' => '_id',
						'post_title' => 'straat;huisnummer;plaats',
						'post_content' => 'aanbiedingstekst',
						'datum_gewijzigd' => 'ObjectUpdated',
                        'datum_toegevoegd' => 'ObjectDate',

						# Post fields
						'media' => array(
							# TableName
							'tableName' => 'tbl_OG_media',
							# Normal fields
							'search_id' => 'id_OG_wonen',

							# Post fields
							'object_keys' => array(
								'objectTiara' => '_id',
								'objectVestiging' => 'ObjectKantoor',
							)
						),
						# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
						'mapping' => array(
							# TableName
							'tableName' => 'og_mappingwonen',
						)
					),
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
						# TableName
						'tableName' => 'ppog_databog',
						# Normal fields
						'ID' => '_id',
						'post_title' => 'straat;huisnummer;plaats',
						'post_content' => 'aanbiedingstekst',
						'datum_gewijzigd' => 'ObjectUpdated',
						'datum_toegevoegd' => 'ObjectDate',

						# Post fields
						'media' => array(
							# TableName
							'tableName' => 'tbl_OG_media',
							# Normal fields
							'search_id' => 'id_OG_bog',

							# Post fields
							'object_keys' => array(
								'objectTiara' => '_id',
								'objectVestiging' => 'ObjectKantoor',
							)
						),
						# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
						'mapping' => array(
							# TableName
							'tableName' => 'og_mappingbedrijven',
						),
					),
				)
			),
			// Post Type 3
			/* post_type */'nieuwbouw' => array(
				'post_type_args' => array(
					'labels' => array(
						'name' => 'OG Nieuwbouw Objecten',
						'singular_name' => 'OG Nieuwbouw Object',
						'add_new' => 'Nieuwe toevoegen',
						'add_new_item' => 'Nieuw OG Nieuwbouw Object toevoegen',
						'edit_item' => 'OG Nieuwbouw Object bewerken',
						'new_item' => 'Nieuw OG Nieuwbouw Object',
						'view_item' => 'Bekijk OG Nieuwbouw Object',
						'search_items' => 'Zoek naar OG Nieuwbouw Objecten',
						'not_found' => 'Geen OG Nieuwbouw Objecten gevonden',
						'not_found_in_trash' => 'Geen OG Nieuwbouw Objecten gevonden in de prullenbak',
						'parent_item_colon' => '',
						'menu_name' => 'Nieuwbouw'
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
					'projecten' => array(
						# TableName
						'tableName' => 'tbl_og_nieuwbouw_projecten',
						# Normal fields
						'ID' => '_id',
						'post_title' => 'project_ProjectDetails_Projectnaam',
						'post_content' => 'project_ProjectDetails_Presentatie_Aanbiedingstekst',
						'ObjectStatus_database' => 'project_ProjectDetails_Status_ObjectStatus',
						'datum_gewijzigd' => 'ObjectUpdated',
						'datum_toegevoegd' => 'ObjectDate',

						# Post fields
						'media' => array(
							# TableName
							'tableName' => 'tbl_og_media',
							# Normal fields
							'search_id' => 'id_OG_nieuwbouw_projecten',

							# Post fields
							'object_keys' => array(
								'objectTiara' => '_id',
								'objectVestiging' => 'ObjectKantoor',
							)
						),
						# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
						'mapping' => array(
							# TableName
							'tableName' => 'og_mappingNieuwbouwProjecten',
						)
					),
					'bouwTypes' => array(
						# TableName
						'tableName' => 'tbl_og_nieuwbouw_bouwtypes',
						# Normal fields
						'ID' => '_id',
						'id_projecten' => 'id_OG_nieuwbouw_projecten',
						'post_title' => 'bouwType_BouwTypeDetails_Naam',
						'post_content' => 'bouwType_BouwTypeDetails_Aanbiedingstekst',
						'datum_gewijzigd' => 'ObjectUpdated',
						'datum_toegevoegd' => 'ObjectDate',

						# Post fields
						'media' => array(
							# TableName
							'tableName' => 'tbl_og_media',
							# Normal fields
							'search_id' => 'id_OG_nieuwbouw_bouwtypes',

							# Post fields
							'object_keys' => array(
								'objectTiara' => '_id',
								'objectVestiging' => 'ObjectKantoor',
							)
						),
						# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
						'mapping' => array(
							# TableName
							'tableName' => 'og_mappingNieuwbouwBouwTypes',
						)
					),
					'bouwNummers' => array(
						# TableName
						'tableName' => 'tbl_og_nieuwbouw_bouwnummers',
						# Normal fields
						'ID' => '_id',
						'post_title' => 'Adres_Straatnaam;Adres_Huisnummer;Adres_Postcode;Adres_Woonplaats',
						'post_content' => 'Aanbiedingstekst',
						'datum_gewijzigd' => 'ObjectUpdated',
						'datum_toegevoegd' => 'ObjectDate',

						# Post fields
						'media' => array(
							'tableName' => 'tbl_og_media',
							'search_id' => 'id_OG_nieuwbouw_bouwnummers',
							'object_keys' => array(
								'objectTiara' => '_id',
								'objectVestiging' => 'ObjectKantoor',
							)
						),
						# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
						'mapping' => array(
							'tableName' => 'og_mappingNieuwbouwBouwNummers',
						)
					),
				)
			)
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
    public $settingPrefix = 'ppOG_'; // This is the prefix for all the settings used within the OG Plugin.
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
		        if ($OGTableRecordValue == '' or $OGTableRecordValue == NULL or $OGTableRecordValue == 'NULL') {
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
        $postWonen_columns = $ogTableMapping->getPostColumns('nieuwbouw');
        $tableWonen_columns = $ogTableMapping->getTableColumns('tbl_og_nieuwbouw_bouwtypes');

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
                        if (!empty($tableWonen_columns)) {
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
	// ================ Start of Class ================
	function __construct() {
		# Use this one if it is going to be run on the site itself.
		// add_action('admin_init', array($this, 'examinePosts'));

		# Use this one if it is going to be a cronjob.
		$this->examinePosts();
	}

    // ================ Functions ================
	function getNames($post_data, $object, $databaseKey) {
		// ======== Declaring Variables ========
		$postTitle = explode(';', $databaseKey['post_title']);

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
		$post_data['post_content'] = $object->{$databaseKey['post_content']};

		return $post_data;
	}

	function updateMedia($postID, $postTypeName, $OGobject, $databaseKeysMedia): void {
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

		$results = $wpdb->get_results("SELECT * FROM `".$databaseKeysMedia['tableName']."` WHERE `".$databaseKeysMedia['search_id']."` = ".$OGobject->id."");

		// ================ Start of Function ================
		$media_data = array();
		foreach ($results as $result) {
			// ======== Declaring Variables ========
			# Vars
			$post_title = "".$result->media_Id."-".$result->bestandsnaam."";
			$post_mime_type = $mime_type_map[$result->{'bestands_extensie'}];

			$media_url = "og_media/{$postTypeName}_{$OGobject->{$databaseKeysMedia['object_keys']['objectVestiging']}}_{$OGobject->{$databaseKeysMedia['object_keys']['objectTiara']}}/{$OGobject->{$databaseKeysMedia['object_keys']['objectTiara']}}_{$result->media_Id}.{$result->bestands_extensie}";

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
				print('Creating MediaID: '.$mediaID.'<br>');
				foreach ($media['post_meta'] as $key => $value) {
					wp_set_object_terms($mediaID, $value, $key);
				}
			}
			else {
				$post_data = $media['post_data'];
				$post_data['ID'] = $query[0]->ID;
				wp_update_post($post_data);

				foreach ($media['post_meta'] as $key => $value) {
					wp_set_object_terms($query[0]->ID, $value, $key);
				}
			}
		}
	}
    function createPost($postTypeName, $OGobject, $databaseKey, $parentPostID='') {
        // ============ Declaring Variables ===========
        # Classes

        # Variables
        $post_data = [
	        'post_type' => $postTypeName,
	        'post_parent' => $parentPostID,
	        'post_title' => '',
	        'post_content' => '',
	        'post_status' => 'draft'
        ];
        $post_data = $this->getNames($post_data, $OGobject, $databaseKey);

        // ============ Start of Function ============
	    # Creating the post
	    $postID = wp_insert_post($post_data);
	    foreach ($OGobject as $key => $value) {
		    add_post_meta($postID, $key, $value);
	    }

	    # Adding meta data for images
	    $this->updateMedia($postID, $postTypeName, $OGobject, $databaseKey['media']);

	    # Publishing the post
	    wp_publish_post($postID);

	    # Returning the postID
	    return $postID;
    }
	function updatePost($postTypeName, $postID, $OGobject, $databaseKey, $parentPostID=''): void {
		// ======== Declaring Variables ========
		# Classes
		$ogMapping = new OGMapping();

		# Vars
		$post_data = [
			'ID' => $postID,
			'post_title' => '',
			'post_parent' => $parentPostID,
			'post_content' => ''
		];
		$post_data = $this->getNames($post_data, $OGobject, $databaseKey);
		$OGobject = $ogMapping->mapMetaData($postTypeName, $OGobject, $databaseKey['mapping']);

		// ======== Start of Function ========
		# Overwriting the post
		wp_update_post($post_data);

		$this->updateMedia($postID, $postTypeName, $OGobject, $databaseKey['media']);

		# Updating the post meta
		foreach ($OGobject as $key => $value) {
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
		}
	}
    function checkNieuwbouwPosts($postTypeName, $databaseKeys) {
        # ============ Declaring Variables ============
        # Classes
        global $wpdb;
        $OGMapping = new OGMapping();
        # Variables

	    $OGProjects = $wpdb->get_results("SELECT * FROM {$databaseKeys[0]['tableName']}");
	    # Removing every null out of the objects so Wordpress won't get crazy.
	    foreach ($OGProjects as $key => $object) {
		    foreach ($object as $key2 => $value) {
			    if ($value == 'null' or $value == 'NULL' or $value == null) {
				    $OGProjects[$key]->{$key2} = '';
			    }
		    }
	    }

	    # ============ Start of Function ============
        # Looping through the objects
        foreach ($OGProjects as $OGProject) {
	        # Checking if this OG project is valid and if not just skip it.
	        if (isset($OGProject->{$databaseKeys[0]['ObjectStatus_database']}) AND $OGProject->{$databaseKeys[0]['ObjectStatus_database']} == '') {
		        continue;
	        }

            // ======== Declaring Variables ========
	        $objectIDs = [];
	        # Remapping the object
	        $OGProject = $OGMapping->mapMetaData($postTypeName, $OGProject, $databaseKeys[0]['mapping']);

            # Post - Project
            $postData = new WP_Query([
	            'post_type' => $postTypeName,
	            'meta_key' => $databaseKeys[0]['ID'],
	            'meta_value' => $OGProject->{$databaseKeys[0]['ID']},
            ]);
            $projectExisted = $postData->have_posts();


            # Database - Project
	        $dateUpdatedObject = $OGProject->{$databaseKeys[0]['datum_gewijzigd']} ?? $OGProject->{$databaseKeys[0]['datum_toegevoegd']};

            if ($projectExisted) {
                $postID = $postData->posts[0]->ID;
                $dateUpdatedPost = get_post_meta($postID, $databaseKeys[0]['datum_gewijzigd'], true) ?? get_post_meta($postID, $databaseKeys[0]['datum_toegevoegd'], true);
            }
            // ======== Start of Function ========
            # Checking if the project exists
	        if ($projectExisted) {
		        // Checking if the post is updated
		        if ($dateUpdatedPost != $dateUpdatedObject) {
			        print('Object updated:'.$dateUpdatedObject . '<br>');
			        print($OGProject->{$databaseKeys[0]['ID']} . ' is updated<br>');
			        // Updating/overwriting the post
			        print('Updating post: ' . $postData->posts[0]->ID . '<br>');
			        print('Post updated:'.$dateUpdatedPost . '<br>');
			        $this->updatePost($postTypeName, $postData->posts[0]->ID, $OGProject, $databaseKeys[0]);
		        }
	        }
            else {
	            // Creating the post
	            $postID = $this->createPost($postTypeName, $OGProject, $databaseKeys[0]);
	            print('Creating PostID: '.$postID.'<br>');
            }

            # Adding the postID to the array
	        $objectIDs[] = $OGProject->{$databaseKeys[0]['ID']};

            # Checking the childposts

        }

	    $this->deleteUnneededPosts($postTypeName, $databaseKeys[0], $objectIDs);


    }
	function checkNormalPosts($postTypeName, $OGobjects, $databaseKey): void {
        // ============ Declaring Variables ============
        # Classes
        global $wpdb;
        $OGMapping = new OGMapping();
        # Variables
        $objectIDs = [];

        // ============ Start of Function ============
        # Creating/Updating the posts
        echo("<h1>".$postTypeName."</h1>");
        foreach ($OGobjects as $OGobject) {
            // ======== Declaring Variables ========
	        # ==== Variables ====
            # Remapping the object
	        $OGobject = $OGMapping->mapMetaData($postTypeName, $OGobject, $databaseKey['mapping']);

            $postData = new WP_Query([
	            'post_type' => $postTypeName,
	            'meta_key' => $databaseKey['ID'],
	            'meta_value' => $OGobject->{$databaseKey['ID']},
            ]);
            $postExists = $postData->have_posts();

            if ($postExists) {
	            $dateUpdatedPost = $postData->posts[0]->{$databaseKey['datum_gewijzigd']};
            }
            # Database dateUpdated
	        $dateUpdatedObject = $OGobject->{$databaseKey['datum_gewijzigd']} ?? $OGobject->{$databaseKey['datum_toegevoegd']};


            // ======== Start of Function ========
            if ($postExists) {
	            // Checking if the post is updated
	            if ($dateUpdatedPost != $dateUpdatedObject) {
                    // Echo the fact that this is happening
                    echo("Updating post with ID: ".$postData->posts[0]->ID."<br>");
                    echo("Post date: ".$dateUpdatedPost."<br>");
                    echo("Object date: ".$dateUpdatedObject."<br>");
		            // Updating/overwriting the post
		            $this->updatePost($postTypeName, $postData->posts[0]->ID, $OGobject, $databaseKey);
	            }
            }
            else {
	            // Creating the post
                echo("Creating post with ID: ".$OGobject->{$databaseKey['ID']}."<br>");
	            echo("Object date: ".$dateUpdatedObject."<br>");
	            $this->createPost($postTypeName, $OGobject, $databaseKey);
            }

            # Adding the object ID to the array
            $objectIDs[] = $OGobject->{$databaseKey['ID']};
            br();
        }
    }
	function examinePosts(): void {
		// ============ Declaring Variables ============
		# Classes
		global $wpdb;
		$postTypeData = new OGPostTypeData();

		# Variables
		$beginTime = time();
		$postTypeData = $postTypeData->customPostTypes();

		// ============ Start of Function ============
		foreach ($postTypeData as $postTypeName => $postTypeArray) {
			if ($postTypeName == 'nieuwbouw' or $postTypeName == 'bedrijven') {
				continue;
			}
			// ======== Declaring Variables ========
			$boolIsNieuwbouw = !isset($postTypeArray['database_tables']['object']);

			if ($boolIsNieuwbouw) {
				# OG objects
				$databaseKeys[0] = $postTypeArray['database_tables']['projecten'];
				$databaseKeys[1] = $postTypeArray['database_tables']['bouwTypes'];
				$databaseKeys[2] = $postTypeArray['database_tables']['bouwNummers'];
			}
			else {
				# OG objects
				$databaseKeys[0] = $postTypeArray['database_tables']['object'];
			}

			// ======== Start of Loop ========
			if ($boolIsNieuwbouw) {
				print('Checking Nieuwbouw'.'<br>');
				$this->checkNieuwbouwPosts($postTypeName, $databaseKeys);
			}
			else {
				foreach ($databaseKeys as $databaseKey) {
					$OGobjects = $wpdb->get_results("SELECT * FROM {$databaseKey['tableName']}");

					# Removing every null out of the objects so Wordpress won't get crazy.
					foreach ($OGobjects as $key => $object) {
						foreach ($object as $key2 => $value) {
							if ($value == 'null' or $value == 'NULL' or $value == null) {
								$OGobjects[$key]->{$key2} = '';
							}
						}
					}

					if (!empty($OGobjects)) {
						$this->checkNormalPosts($postTypeName, $OGobjects, $databaseKey);
					}
				}
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

class OGLookieLookie {
    function __construct() {
//        add_action('init', [$this, 'lookieBouwtypes']);
//        add_action('init', [$this, 'lookieBouwnummers']);
    }

    function lookieBouwtypes() {
        // ================ Declaring Variables ================
        # Classes
        global $wpdb;

        # Variables
	    $arraya = [];
        // Connecting to database
        $db = new $wpdb('admin_og-wp', '6OMaa8GpC', 'admin_vhbackup', 'orel.pixelplus.nl');

        $results = $db->get_results("SELECT ID FROM `wp_posts` WHERE `post_parent` = 0 AND `post_type` = 'nieuwbouw'");

        // ================ Start of Function ================
        foreach ($results as $result) {
            $id = $result->ID;

            $results2 = $db->get_results("SELECT ID FROM `wp_posts` WHERE `post_parent` = {$id} AND `post_type` = 'nieuwbouw'");

            foreach($results2 as $result2) {
	            $id = $result2->ID;
                array_push($arraya, $id);
            }
        }
        # Getting all the DISTINCT meta_keys from the post meta of all those records with post_id
        $results3 = $db->get_results("SELECT DISTINCT meta_key, meta_value FROM `wp_postmeta` WHERE `post_id` IN (" . implode(',', $arraya) . ")");
        pre($results3);
    }

    function lookieBouwnummers() {
	    // ================ Declaring Variables ================
	    # Classes
	    global $wpdb;

	    # Variables
	    $arraya = [];
	    // Connecting to database
	    $db = new $wpdb('admin_og-wp', '6OMaa8GpC', 'admin_vhbackup', 'orel.pixelplus.nl');

	    $results = $db->get_results("SELECT ID FROM `wp_posts` WHERE `post_parent` = 0 AND `post_type` = 'nieuwbouw'");

	    // ================ Start of Function ================
	    foreach ($results as $result) {
		    $id = $result->ID;

		    $results2 = $db->get_results("SELECT ID FROM `wp_posts` WHERE `post_parent` = {$id} AND `post_type` = 'nieuwbouw'");

		    foreach($results2 as $result2) {
			    $id = $result2->ID;
			    $results3 = $db->get_results("SELECT ID FROM `wp_posts` WHERE `post_parent` = {$id} AND `post_type` = 'nieuwbouw'");
                foreach($results3 as $result3) {
                    $id = $result3->ID;
                    array_push($arraya, $id);
                }
		    }
	    }
        # Getting all the DISTINCT meta_keys from the post meta of all those records with post_id
        $results4 = $db->get_results("SELECT DISTINCT meta_key, meta_value FROM `wp_postmeta` WHERE `post_id` IN (" . implode(',', $arraya) . ")");
        pre($results4);
    }
}
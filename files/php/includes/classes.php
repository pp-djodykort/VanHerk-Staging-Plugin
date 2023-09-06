<?php
// ========= Imports =========
include_once("functions.php");

// ========= Activation, Deactivation and Uninstall =========
class OGVanHerkActivationAndDeactivation {
	// ======== Activation ========
	public static function activate(): void {
		self::registerSettings();
		self::createCacheFiles();
	}

	// ======== Deactivation ========
	public static function deactivate(): void {

	}

    // ======== Uninstall ========
    public static function uninstall(): void {
	    // ================ Start of Function ================
	    // ======== Deleting Settings/Options ========
	    // Check which settings are registered
	    $OGoptions = wp_load_alloptions();

	    // only get settings that start with ppOG_
	    $OGoptions = array_filter($OGoptions, function($key) {
		    return str_starts_with( $key, OGVanHerkSettingsData::$settingPrefix);
	    }, ARRAY_FILTER_USE_KEY);

	    // Deleting all settings in database
	    foreach ($OGoptions as $option => $value) {
		    delete_option($option);
	    }
    }

    // ============ Functions ============
	// A function for registering base settings of the unactivated plugin as activation hook.
	static function registerSettings(): void {
		// ==== Start of Function ====
		// Registering settings
		foreach (OGVanHerkSettingsData::arrOptions() as $settingName => $settingValue) {
			add_option( OGVanHerkSettingsData::$settingPrefix . $settingName, $settingValue);
		}
	}

	static function createCacheFiles(): void {
		// ==== Declaring Variables ====
		# Variables
		$cacheFolder = plugin_dir_path(dirname(__DIR__)) . OGVanHerkSettingsData::$cacheFolder;

		// ==== Start of Function ====
		// Creating the cache files
		foreach (OGVanHerkSettingsData::cacheFiles() as $cacheFile) {
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

// ========= Data Classes =========
class OGVanHerkPostTypeData {
	// ============ Begin of Class ============
	public static function customPostTypes(): array {
		// ===== Start of Construct =====
		return array(
			// Custom Post Type: 'wonen'
			'wonen' => array(
				'post_type_args' => array(
					// This is just all the data / instructions that WordPress needs to know about the custom post type so that it can work correctly.
					'labels' => array(
						// Labels for the custom post type in the WordPress admin
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
                    'rewrite' => false,             // Mapped value
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
						'tableName' => 'tbl_OG_wonen',                                      // NON Mapped - Name of the table
						# Normal fields
						'ID' => '_id',                                                      // Mapped value - ALWAYS Use the TiaraID
						/*
                        Warning: You can only use one of the separators at the same time.
                        post_title Separators:
                            ; (Semicolon)   - The semi-colon is used to separate the values from each other with ' '
                            | (Pipe)        - The pipe is used as an if statement, if the first value is empty, then the second value will be used if it exists
                            Nothing       - If there is no separator, it will just use the first value. The only variable given in
                        */
                        'post_title' => 'straat;huisnummer;huisnummertoevoeging;plaats',    // Mapped value - Default: Straat;Huisnummer;Huisnummertoevoeging;Woonplaats
						/*
						Warning: You can only use one of the separators at the same time.
						post_name Separators:
							- (Dash)      - The dash is used to separate the values from each other with '-'
							| (Pipe)      - The pipe is used as an if statement, if the first value is empty, then the second value will be used if it exists
							Nothing       - If there is no separator, it will just use the first value. The only variable given in
						 */
                        'post_name' => 'straat-huisnummer-huisnummertoevoeging-plaats',     // Mapped value - Default: Straat-Huisnummer-Huisnummertoevoeging-Woonplaats
						'post_content' => 'aanbiedingstekst',                               // Mapped value - Default: De aanbiedingstekst
						'datum_gewijzigd' => 'ObjectUpdated',                               // Mapped value - Default: datum_gewijzigd      ; Default value is only for objects without a mapping table within the database
						'datum_gewijzigd_unmapped' => 'datum_gewijzigd',                    // NON Mapped value - Default: datum_gewijzigd ; The extra field is needed so the plugin can filter on the date for less memory usage
                        'datum_toegevoegd' => 'ObjectDate',                                 // Mapped value - Default: datum_toegevoegd     ; Default value is only for objects without a mapping table within the database
						'objectCode' => 'ObjectCode',                                       // Mapped value - Default: object_ObjectCode    ; Default value is only for objects without a mapping table within the database

						# Post fields
						'media' => array(
							# TableName
							'tableName' => 'tbl_OG_media',          // NON Mapped - Name of the table
							# Normal fields
							'folderRedirect' => '',                 // FTP Folder name of media from OG Feeds - ALLOWED TO BE EMPTY
							'search_id' => 'id_OG_wonen',           // NON Mapped value - Default: Can found in OG Feeds media table > Id of Post Type / OG Type
                            'mediaID' => 'media_Id',                // NON Mapped value - Default: media_Id; Can found in OG Feeds media table > Post Type / OG Type
							'datum_toegevoegd' => 'MediaDate',      // Mapped value     - Default: datum_toegevoegd ; Default value is only for objects without a mapping table within the database
							'datum_gewijzigd' => 'MediaUpdated',    // Mapped value     - Default: datum_gewijzigd  ; Default value is only for objects without a mapping table within the database
							'mediaName' => 'MediaName',             // Mapped value     - Default: mediaName        ; This one is special. Even in the normal plugin I still have this one mapped within the database in a mapping table. Default value is only for objects without a mapping table within the database
							'media_Groep' => 'MediaType',           // Mapped value     - Default: media_Groep      ; Default value is only for objects without a mapping table within the database

							# Post fields
							'object_keys' => array(
								'objectTiara' => '_id',                         // Mapped value - ALWAYS Use the TiaraID
								'objectVestiging' => 'ObjectVerstigingsNummer', // Mapped value - USE the Vestigingsnummer of the OG Object, NOT The media objects.
							),

							# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
							'mapping' => array(/* TableName */ 'tableName' => 'og_mappingmedia')
						),
						# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
						'mapping' => array( /* TableName */ 'tableName' => 'og_mappingwonen')
					),
				)
			),
			// Custom Post Type: 'bedrijven'
			'bedrijven' => array(
				'post_type_args' => array(
					// This is just all the data / instructions that WordPress needs to know about the custom post type so that it can work correctly.
					'labels' => array(
						// Labels for the custom post type in the WordPress admin
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
					'rewrite' => false,             // Mapped value
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
						'tableName' => 'tbl_OG_bog',
						# Normal fields
						'ID' => '_id',
						'post_title' => 'straat;huisnummer;huisnummertoevoeging;plaats', // Mapped value
						'post_name' => 'straat-huisnummer-huisnummertoevoeging-plaats',  // Mapped value
						'post_content' => 'aanbiedingstekst',       // Mapped value
						'datum_toegevoegd' => 'ObjectDate',         // Mapped value
                        'datum_gewijzigd' => 'ObjectUpdated',       // Mapped value
						'datum_gewijzigd_unmapped' => 'datum_gewijzigd',                    // NON Mapped value - Default: datum_gewijzigd ; The extra field is needed so the plugin can filter on the date for less memory usage
                        'objectCode' => 'ObjectCode',               // Mapped value

						# Post fields
						'media' => array(
							# TableName
							'tableName' => 'tbl_OG_media',
							# Normal fields
                            'folderRedirect' => 'bog',              // Mapped value CAN BE EMPTY
							'search_id' => 'id_OG_bog',             // NON Mapped value
							'mediaID' => 'media_Id',                // NON Mapped value
							'datum_toegevoegd' => 'MediaDate',     // Mapped value
							'datum_gewijzigd' => 'MediaUpdated',    // Mapped value
							'mediaName' => 'MediaName',             // Mapped value
							'media_Groep' => 'MediaType',           // Mapped value

							# Post fields
							'object_keys' => array(
								'objectTiara' => '_id',
								'objectVestiging' => 'ObjectVerstigingsNummer',
							),

							# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
							'mapping' => array(/* TableName */ 'tableName' => 'og_mappingmedia')
						),
						# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
						'mapping' => array(/* TableName */ 'tableName' => 'og_mappingbedrijven')
					),
				)
			),
			// Custom Post Type: 'nieuwbouw'
			'nieuwbouw' => array(
				'post_type_args' => array(
					// This is just all the data / instructions that WordPress needs to know about the custom post type so that it can work correctly.
					'labels' => array(
						// Labels for the custom post type in the WordPress admin
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
					'rewrite' => false,             // Mapped value
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
						'tableName' => 'tbl_OG_nieuwbouw_projecten',
						# Normal fields
						'ID' => '_id',                                                              // Mapped value
						'post_title' => 'sold_title',                       // Mapped value if needed
						'post_name' => 'sold_title',                        // Mapped value
						'post_content' => 'omschrijving',    // Mapped value
						'ObjectStatus_database' => 'status',    // Mapped value
						'datum_gewijzigd' => 'ObjectUpdated',                                       // Mapped value
						'datum_gewijzigd_unmapped' => 'datum_gewijzigd',                            // NON Mapped value - Default: datum_gewijzigd ; The extra field is needed so the plugin can filter on the date for less memory usage
                        'datum_toegevoegd' => 'ObjectDate',                                         // Mapped value
						'objectCode' => 'ObjectCode',                                               // Mapped value
						'type' => 'project',                                                        // Standard value don't change

						# Post fields
						'media' => array(
							# TableName
							'tableName' => 'tbl_OG_media',
							# Normal fields
							'folderRedirect' => '',                         // Mapped value CAN BE EMPTY
							'search_id' => 'id_OG_nieuwbouw_projecten',     // NON Mapped value
							'mediaID' => 'media_Id',                        // NON Mapped value
							'datum_toegevoegd' => 'MediaDate',             // Mapped value
							'datum_gewijzigd' => 'MediaUpdated',            // Mapped value
							'mediaName' => 'MediaName',                     // Mapped value
							'media_Groep' => 'MediaType',                   // Mapped value
							'mediaTiaraID' => '',                           // Mapped value CAN BE EMPTY

							# Post fields
							'object_keys' => array(
								'objectTiara' => '_id',
								'objectVestiging' => 'ObjectVerstigingsNummer',
							),

							# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
							'mapping' => array(/* TableName */ 'tableName' => 'og_mappingmedia')
						),
						# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
						'mapping' => array(/* TableName */ 'tableName' => 'og_mappingnieuwbouwprojecten')
					),
					'bouwTypes' => array(
						# TableName
						'tableName' => 'tbl_OG_nieuwbouw_bouwTypes',
						# Normal fields
						'ID' => '_id',                                                              // Mapped value
						'id_projecten' => 'id_OG_nieuwbouw_projecten',                              // Mapped value
						'post_title' => 'bouwType_BouwTypeDetails_Naam|ObjectCode',                 // Mapped value if needed | is for seperating values (OR statement)
						'post_name' => 'bouwType_BouwTypeDetails_Naam|ObjectCode',                  // Mapped value
						'post_content' => 'omschrijving',                                           // Mapped value
						'ObjectStatus_database' => 'bouwType_BouwTypeDetails_Status_ObjectStatus',  // Mapped value
						'datum_gewijzigd' => 'ObjectUpdated',                                       // Mapped value
						'datum_gewijzigd_unmapped' => 'datum_gewijzigd',                    // NON Mapped value - Default: datum_gewijzigd ; The extra field is needed so the plugin can filter on the date for less memory usage
                        'datum_toegevoegd' => 'ObjectDate',                                         // Mapped value
						'objectCode' => 'ObjectCode',                                               // Mapped value
						'type' => 'bouwtype',                                                       // Standard value don't change

						# Post fields
						'media' => array(
							# TableName
							'tableName' => 'tbl_OG_media',
							# Normal fields
							'folderRedirect' => 'bouwtypen',                         // Mapped value CAN BE EMPTY
							'search_id' => 'id_OG_nieuwbouw_bouwtypes',     // NON Mapped value
							'mediaID' => 'media_Id',                        // NON Mapped value
							'datum_toegevoegd' => 'MediaDate',             // Mapped value
							'datum_gewijzigd' => 'MediaUpdated',            // Mapped value
							'mediaName' => 'MediaName',                     // Mapped value
							'media_Groep' => 'MediaType',                   // Mapped value
							'mediaTiaraID' => '',                           // Mapped value CAN BE EMPTY

							# Post fields
							'object_keys' => array(
								'objectTiara' => '_id',
								'objectVestiging' => 'ObjectVerstigingsNummer',
							),

							# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
							'mapping' => array(/* TableName */ 'tableName' => 'og_mappingmedia')
						),
						# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
						'mapping' => array(/* TableName */ 'tableName' => 'og_mappingnieuwbouwbouwtypes')
					),
					'bouwNummers' => array(
						# TableName
						'tableName' => 'tbl_OG_nieuwbouw_bouwNummers',
						# Normal fields
						'ID' => '_id',                                                                          // Mapped value
						'id_bouwtypes' => 'id_OG_nieuwbouw_bouwTypes',                                          // Mapped value
						'post_title' => 'straat;huisnummer;postcode;plaats;huisnummertoevoeging;ObjectCode',    // Mapped value if needed | is for seperating values (OR statement)
						'post_name' => 'straat-huisnummer-postcode-plaats-huisnummertoevoeging-ObjectCode',  // Mapped value
                        'post_content' => 'Aanbiedingstekst',                                                   // Mapped value
						'ObjectStatus_database' => 'bouwNummer_ObjectCode',                                     // Mapped value
						'datum_gewijzigd' => 'ObjectUpdated',                                                   // Mapped value
						'datum_gewijzigd_unmapped' => 'datum_gewijzigd',                                        // NON Mapped value - Default: datum_gewijzigd ; The extra field is needed so the plugin can filter on the date for less memory usage
                        'datum_toegevoegd' => 'ObjectDate',                                                     // Mapped value
						'objectCode' => 'ObjectCode',                                                           // Mapped value
						'type' => 'bouwnummer',                                                                 // Standard value don't change

						# Post fields
						'media' => array(
							# TableName
							'tableName' => 'tbl_OG_media',
							# Normal fields
							'folderRedirect' => 'bouwnummers',                         // Mapped value CAN BE EMPTY
							'search_id' => 'id_OG_nieuwbouw_bouwnummers',   // NON Mapped value
							'mediaID' => 'media_Id',                        // NON Mapped value
							'datum_toegevoegd' => 'MediaDate',             // Mapped value
							'datum_gewijzigd' => 'MediaUpdated',            // Mapped value
							'mediaName' => 'MediaName',                     // Mapped value
							'media_Groep' => 'MediaType',                   // Mapped value
                            'mediaTiaraID' => '',                           // Mapped value CAN BE EMPTY

							# Post fields
							'object_keys' => array(
								'objectTiara' => '_id',
								'objectVestiging' => 'ObjectVerstigingsNummer',
							),

							# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
							'mapping' => array(/* TableName */ 'tableName' => 'og_mappingmedia')
						),
						# Only if mapping is neccesary uncomment the following lines and fill in the correct table name
						'mapping' => array(/* TableName */ 'tableName' => 'og_mappingnieuwbouwbouwnummers')
					),
				)
			)
		);
	}
}
class OGVanHerkSettingsData {
	// ======== Declare Variables ========
	# Strings
	public static string $settingPrefix = 'ppOG_'; // This is the prefix for all the settings used within the OG Plugin.
	public static string $cacheFolder = 'caches/'; // This is the folder where all the cache files are stored within the server/ftp

    # Arrays
	private static array $apiURLs = [
		'license' => 'https://og-feeds2.pixelplus.nl/api/validate.php',
		'syncTimes' => 'https://og-feeds2.pixelplus.nl/api/latest.php'
	];
	private static array $cacheFiles = [
		'licenseCache' => 'licenseCache.json', // This is the cache file for the checking the Licence key
	];
	private static array $arrOptions = [
		/* Setting Name */'licenseKey' => /* Default Value */   '',     // License Key
	];
	private static array $adminSettings = [
		// Settings 1
		/* Option Group= */ 'ppOG_adminOptions' => [
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

	# Bools
	public static bool $boolGiveLastCron = True;

	# Ints
	public static int $intObjectsCreated = 0;
	public static int $intObjectsUpdated = 0;

    // ==== Getters ====
    public static function apiURLs(): array {
        return self::$apiURLs;
    }
    public static function cacheFiles(): array {
        return self::$cacheFiles;
    }
    public static function arrOptions(): array {
        return self::$arrOptions;
    }
    public static function adminSettings(): array {
        return self::$adminSettings;
    }
	// ======== HTML Functions ========
	// Sections
	function htmlLicenceSection(): void { ?>
        <p>De licentiesleutel die de plugin activeert</p>
	<?php }
	// Fields
	function htmlLicenceKeyField(): void {
		// ===== Declaring Variables =====
		// Vars
		$licenseKey = get_option(self::$settingPrefix.'licenseKey');

		// ===== Start of Function =====
		// Check if licenseKey is empty
		if ($licenseKey == '') {
			// Display a message
			echo('De licentiesleutel is nog niet ingevuld.');
		}
		echo(" <input type='text' name='".self::$settingPrefix."licenseKey' value='".esc_attr($licenseKey)."' ");
	}
}
class OGVanHerkMapping {
	// ================ Constructor ================
	function __construct() {

	}

	// ================ Begin of Class ================
	private static function cleanupObjects($OGTableRecord): mixed {
		foreach ($OGTableRecord as $OGTableRecordKey => $OGTableRecordValue) {
			# Check if the value is empty and if so remove the whole key from the OBJECT
			if ($OGTableRecordValue == '' or $OGTableRecordValue == NULL or $OGTableRecordValue == 'NULL' or $OGTableRecordValue == 'null') {
				unset($OGTableRecord->{$OGTableRecordKey});
			}
		}

		# Return the cleaned up OBJECT
		return $OGTableRecord;
	}
	public static function mapMetaData($OGTableRecord, $databaseKeysMapping, $locationCodes=[], $databaseKeys=[]) {
		if (!empty($databaseKeysMapping)) {
			// ======== Declaring Variables ========
			# Classes
			global $wpdb;

			# Vars
			$mappingTable = $wpdb->get_results("SELECT * FROM `{$databaseKeysMapping['tableName']}`", ARRAY_A);
			// ========================= Start of Function =========================
			// ================ Cleaning the Tables/Records ================
			# Getting rid of all the useless and empty values in the OBJECT
			$OGTableRecord = self::cleanupObjects($OGTableRecord);
			# Getting rid of all the useless and empty values in the MAPPING TABLE
			foreach ($mappingTable as $mappingKey => $mappingTableValue) {
				# Check if the value is empty and if so remove the whole key from the OBJECT
				if (is_null($mappingTableValue['pixelplus']) or empty($mappingTableValue['pixelplus'])) {
					unset($mappingTable[$mappingKey]);
				}
			}

			// ================ Mapping the Data ================
            # Looping through all the keys in the mapping table
			foreach ($mappingTable as $mappingKey => $mappingValue) {
				/*
                Placeholders:

                () = If-Else Statement: If statement with unlimited statements that can go in it
                     Separator between values:
				        1. | (Pipe) - The pipe is used as an if statement, if the first value is empty, then the second value will be used if it exists

				    Examples:
				        1. (straat|adres) => If straat is empty, use adres instead
                        2. (straat|adres|plaats) => If straat is empty, use adres instead, if adres is empty, use plaats instead

				----------------------------
                [] = Array Extraction: Instead of using the array it converts it to a string with an comma as separator.
				    Input:
                    String “[value1, value2, value3, etc.]”

				    Example: [1,2,3] => '1, 2, 3'

				----------------------------
                {} = Concatenation: Join the values together.
                    Separator between values:
				        1. + (Plus) - The plus is used to separate the values from each other with ' '
				        2. - (Dash) - The dash is used to separate the values from each other with '-'

				    Placeholders within concatenation:
				    ~ (Tilde) - Remove all the spaces from the value

				    Examples:
				        1. {straat+huisnummer+huisnummertoevoeging} => 'Vroedelstroefe 48 15 B'
                        2. {straat+huisnummer+~huisnummertoevoeging~} => 'Vroedelstroefe 48 15B'
				        3. {straat-huisnummer-huisnummertoevoeging} => 'Vroedelstroefe-48-15-B'
                        4. {straat-huisnummer-~huisnummertoevoeging~} => 'Vroedelstroefe-48-15B'

				----------------------------
                $  = Status Handling: Transform values based on specific statuses.
				    Separator between values:
				        1. | (Pipe) - The pipe is used as an if statement, if the first value is empty, then the second value will be used if it exists

				    Options:
				        1. $status|sold$ => If status is "sold", set pixelplus to 1, otherwise 0.
				        2. $price|prijs$ => If price is greater than 0, set pixelplus to 1, otherwise 0.
				        3. $rating|onderhoudswaardering$ => Setting everything to lowercase besides the first letter and removing all the spaces.

				----------------------------
                <> = Location Codes: Map numeric codes to values. Convert date-like values to datetime.
                     Options:
				        1. <city_code> = Numeric Office Code (If value is in array city codes, convert to corresponding city name)
                        2. <date_like_value> => Convert date-like value to Unix timestamp

                     Examples:
                        <bouwNummer_NVMVestigingNR> = 551235 => "Amsterdam"
				        <datum_toegevoegd> = '2021-01-01' => 1609459200

				----------------------------
                ^  = Counting: Calculate and store counts.
                     Options:
                        1. ^bouwtypes^ => Calculate based off and store the count of build types for a project.
                        2. ^bouwnummers^ => Calculate and store the count of build numbers for a project.

				----------------------------
                *  = Object Types: Conditionally set values based on conditions. !(Only works with 2 values)!
                    Options:
                        1. *property_type|Residential* => If property type exists, set to "Residential".
                        2. *property_type|Commercial* => If property type doesn't exist, set to "Commercial".

                    Examples:
                        objecttype = *wonen_Appartement_KenmerkAppartement|woonhuis*
                */
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
				// ==== Checking concatenations ====
				if (str_starts_with($mappingValue['pixelplus'], '{') and str_ends_with($mappingValue['pixelplus'], '}')) {
					// ==== Declaring Variables ====
					# Vars
					$strTrimmedKey = trim($mappingValue['pixelplus'], '{}');
					$arrExplodedKey = explode('+', $strTrimmedKey);
					$arrExplodedKeyMinus = explode('-', $strTrimmedKey);
					$strResult = '';

					// ==== Start of Function ====
					# Looping through the plus keys
					foreach($arrExplodedKey as $arrExplodedKeyValue) {
						// ==== Declaring Variables ====
						# Bools
						$boolTrimSpaces = False;

						// ==== Start of Function ====
						# Step 1: Checking if there are any special character at the beginning and or end of the key
						if (str_starts_with($arrExplodedKeyValue, '~') and str_ends_with($arrExplodedKeyValue, '~')) {
							# Step 2: Remove the ~ from the value and all the spaces. And then adding it to strResult
							$boolTrimSpaces = True;

							# Step 3: Removing the ~ from the value
							$arrExplodedKeyValue = trim($arrExplodedKeyValue, '~');
						}

						# Step 4: Check if the key even isset or empty in OG Record
						if (isset($OGTableRecord->{$arrExplodedKeyValue}) and !empty($OGTableRecord->{$arrExplodedKeyValue})) {
							# Step 5: Adding it to strResult
							$strResult .= $boolTrimSpaces ? str_replace(' ', '', $OGTableRecord->{$arrExplodedKeyValue}).' ' : $OGTableRecord->{$arrExplodedKeyValue}.' ';
						}
					}
					# Looping through the minus keys
					foreach($arrExplodedKeyMinus as $arrExplodedKeyValue) {
						# Step 2: Check if the key even isset or empty in OG Record
						if (isset($OGTableRecord->{$arrExplodedKeyValue}) and !empty($OGTableRecord->{$arrExplodedKeyValue})) {
							# Step 3: Add the value to the result string
							$strResult .= $OGTableRecord->{$arrExplodedKeyValue}.'-';
						}
					}

					# Putting it in the mapping table as a default value
					$mappingTable[$mappingKey]['pixelplus'] = "'".rtrim($strResult, ' -')."'";
				}
				// ==== Checking the statuses ====
				if (str_starts_with($mappingValue['pixelplus'], '$') and str_ends_with($mappingValue['pixelplus'], '$')) {
					// ==== Declaring Variables ====
                    # Vars
                    $strTrimmedKey = trim($mappingValue['pixelplus'], '$');
                    $arrExplodedKey = explode('|', $strTrimmedKey);

                    // ==== Start of Function ====
                    // if has more than 1 key
                    if (count($arrExplodedKey) > 1) {
                        # Step 1: Checking the value
                        if (isset($OGTableRecord->{$arrExplodedKey[0]}) and !empty($OGTableRecord->{$arrExplodedKey[0]})) {
                            switch (strtolower(end($arrExplodedKey))) {
                                case 'sold': {
                                    // ==== Start of Function ====
                                    # If the value is verkocht then put it to 1
	                                $mappingTable[$mappingKey]['pixelplus'] = (strtolower($OGTableRecord->{$arrExplodedKey[0]} == 'verkocht') ? "'1'" : "'0'");
                                    break;
                                }
                                case 'prijs': {
                                    // ==== Start of Function ====
                                    $mappingTable[$mappingKey]['pixelplus'] = ($OGTableRecord->{$arrExplodedKey[0]} > 0) ? "'1'" : "'0'";
                                    break;
                                }
                                case 'onderhoudswaardering': {
                                    # Remove all weird characters, change to space then to lowercase and then UpperCase the first letter
                                    $mappingTable[$mappingKey]['pixelplus'] = "'".ucfirst(strtolower(preg_replace('/[^A-Za-z0-9\-]/', ' ', $OGTableRecord->{$arrExplodedKey[0]})))."'";

                                    # Removing the old record
                                    unset($OGTableRecord->{$arrExplodedKey[0]});
                                    break;
                                }
                            }
                        }
                    }
				}
				// ==== Checking arrays ====
				if (str_starts_with($mappingValue['pixelplus'], '[') and str_ends_with($mappingValue['pixelplus'], ']')) {
					// ==== Declaring Variables ====
					# Vars
					$strTrimmedKey = trim($mappingValue['pixelplus'], '[]');
					$arrExplodedKey = explode(',', $strTrimmedKey);
					$strResult = '';

					// ==== Start of Function ====
					if (!empty($arrExplodedKey)) {
						# Step 1: Looping through all the keys
						foreach($arrExplodedKey as $arrExplodedKeyValue) {
							# Step 2: Check if the key even isset or empty in OG Record
							if (isset($OGTableRecord->{$arrExplodedKeyValue}) and !empty($OGTableRecord->{$arrExplodedKeyValue})) {
								# Getting all the value's from that record
								$explodedRecord = explode(',', $OGTableRecord->{$arrExplodedKeyValue});

								# Step 3: Looping through all the values
								foreach ($explodedRecord as $explodedRecordValue) {
									# Step 4: Removing the brackets from the value
									$explodedRecordValue = trim($explodedRecordValue, '[]');
									$strResult .= $explodedRecordValue.', ';
								}
								# Step 5: Removing the old key
								if ($strResult != '') {
									unset($OGTableRecord->{$arrExplodedKeyValue});
								}
							}
						}
						# Step 6: Putting it in the mapping table as a default value
						$mappingTable[$mappingKey]['pixelplus'] = "'".ucfirst(strtolower(rtrim($strResult, ', ')."'"));

					}
				}
				// ==== Checking location codes ====
				if (str_starts_with($mappingValue['pixelplus'], '<') and str_ends_with($mappingValue['pixelplus'], '>')) {
					// ==== Declaring Variables ====
					# Vars
					$strTrimmedKey = trim($mappingValue['pixelplus'], '<>');

					// ==== Start of Function ====
					if (!empty($strTrimmedKey)) {
						# Step 2: Checking if the value is NOT empty
						if (isset($OGTableRecord->{$strTrimmedKey}) and !empty($OGTableRecord->{$strTrimmedKey})) {
							# Step 3: If value is a numeric
                            # Converting numeric value to Location Code
							if (is_numeric($OGTableRecord->{$strTrimmedKey})) {
								# Step 4: Checking if the value is in the locationCodes array
								$key = array_search($OGTableRecord->{$strTrimmedKey}, $locationCodes[0]);
								if ($key !== false) {
									# Step 5: setting the key as the value
									$OGTableRecord->{$mappingTable[$mappingKey]['vanherk']} = $locationCodes[1][$key];
								}
							}

                            # Converting the value to Unix Timestamp
							else {
								# Step 4: Checking if the value is can be converted to a datetime
								$datetime = strtotime($OGTableRecord->{$strTrimmedKey});
								if ($datetime !== false) {
									# Step 5: Adding it to the OG Record
									$OGTableRecord->{$mappingTable[$mappingKey]['vanherk']} = $datetime;
								}
							}
						}
					}
				}
                // ==== Checking the total buildnumbers/buildtypes ====
				if (str_starts_with($mappingValue['pixelplus'], '^') and str_ends_with($mappingValue['pixelplus'], '^')) {
                    // ==== Declaring Variables ====
                    # Vars
                    $strTrimmedKey = trim($mappingValue['pixelplus'], '^');

                    // ==== Start of Function ====
					if (!empty($strTrimmedKey)) {
						// ==== Declaring Variables ====
						# Vars
						$projectID = $OGTableRecord->{$databaseKeys[0]['media']['search_id']} ?? $OGTableRecord->id ?? '0';

                        // ==== Start of Function ====
                        if ($strTrimmedKey == 'bouwtypes') {
                            # Step 1: Getting the count of bouwtypes in the database
                            $count = $wpdb->get_var("SELECT COUNT(*) FROM {$databaseKeys[1]['tableName']} WHERE {$databaseKeys[0]['media']['search_id']} = $projectID");

                            # Step 2: Adding the count to the OG Record
                            $OGTableRecord->{$mappingTable[$mappingKey]['vanherk']} = $count;
                        }
                        if ($strTrimmedKey == 'bouwnummers') {
                            # Step 1: Getting the count of bouwnummers in the database
                            $count = $wpdb->get_var("SELECT COUNT(*) FROM {$databaseKeys[2]['tableName']} WHERE {$databaseKeys[0]['media']['search_id']} = $projectID");

                            # Step 2: Adding the count to the OG Record
                            $OGTableRecord->{$mappingTable[$mappingKey]['vanherk']} = $count;
                        }
                    }
                }
                // ==== Checking the objecttype (basically a conditional) ====
                if (str_starts_with($mappingValue['pixelplus'], '*') and str_ends_with($mappingValue['pixelplus'], '*')) {
                    // ==== Declaring Variables ====
                    # Vars
                    $strTrimmedKey = trim($mappingValue['pixelplus'], '*');
                    $arrExplodedKey = explode('|', $strTrimmedKey);

                    // ==== Start of Function ====
                    if (!empty($arrExplodedKey)) {
                        # Step 1: Checking if the key isset in the OG Record
                        if (isset($OGTableRecord->{$arrExplodedKey[0]}) and !empty($OGTableRecord->{$arrExplodedKey[0]})) {
                            # Step 2: Set it as the value
                            $OGTableRecord->{$mappingTable[$mappingKey]['vanherk']} = $arrExplodedKey[1];

                            # Step 3: Removing the old key
                            unset($OGTableRecord->{$arrExplodedKey[0]});
                        }
                        else {
                            # Setting the value to the second key
                            $OGTableRecord->{$mappingTable[$mappingKey]['vanherk']} = end($arrExplodedKey);
                        }
                    }
                }
			}

			# Looping through the mapping table with the updated values
			foreach ($mappingTable as $mappingValue) {
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
				foreach ($mappingTable as $mappingValue) {
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
		else {
			// ================ Cleaning the Tables/Records ================
			# Getting rid of all the useless and empty values in the OBJECT
			$OGTableRecord = self::cleanupObjects($OGTableRecord);
		}

		// ================ Returning the Object ================
		# Return the object
		return $OGTableRecord;
	}
}

// ========= Start of Classes =========
class OGVanHerkMenus {
	// ============ Constructor ============
	function __construct() {
		// ========== Start of Function ==========
		add_action('admin_menu', [__CLASS__, 'addMenus']);
	}

	// ================ Begin of Class ================
	# ==== Functions ====
	# This function is for adding the menu to the admin panel
	public static function addMenus(): void {
		// ======== Start of Function ========
		// add_menu_page(
		//	'Pixelplus OG Plugin',
		//	'Pixelplus OG Plugin',
		//	'manage_options',
		//	'pixelplus-og-plugin',
		//	[__CLASS__, 'htmlMenu'],
		//	'dashicons-admin-generic',
		//	100
		// );

		// ==== Items OG Aanbod ====
		// Menu Item: OG Aanbod Dashboard
		add_menu_page(
			'OG Aanbod',
			'OG Aanbod',
			'manage_options',
			'pixelplus-og-plugin-aanbod',
			[__CLASS__, 'HTMLOGAanbodDashboard'],
			'dashicons-admin-multisite',
			40);

		// add_submenu_page(
		//	'pixelplus-og-plugin-aanbod',
		//	'Aanbod Dashboard',
		//	'Dashboard',
		//	'manage_options',
		//	'pixelplus-og-plugin-aanbod',
		//	[__CLASS__, 'HTMLOGAanbodDashboard'],
		//	0
		// );
	}

	# ==== HTML ====
	// OG Aanbod
	public static function HTMLOGAanbodDashboard(): void { htmlHeader('OG Aanbod Dashboard'); ?>
        <p>dingdong bishass</p>
		<?php htmlFooter('OG Aanbod Dashboard');}
}
class OGVanHerkPostTypes {
	// ==== Start of Class ====
	function __construct() {
        # Creating the post types
		add_action('init', [__CLASS__, 'createPostTypes']);

        # Checking the post migration
		// add_action('init', [__CLASS__, 'checkMigrationPostTypes']);
	}

	// =========== Functions ===========
	public static function createPostTypes(): void {
		// ==== Start of Function ====
		# Create the OG Custom Post Types (if the user has access to it)
		foreach(OGVanHerkPostTypeData::customPostTypes() as $postType => $postTypeArray) {
			register_post_type($postType, $postTypeArray['post_type_args']);
		}
	}
	# This function is for checking if the post types are migrated to different tables / metadata tables
	public static function checkMigrationPostTypes(): void {
		// ==== Declaring Variables ====
		# Classes
		global $wpdb;
		$postTypeData = OGVanHerkPostTypeData::customPostTypes();

		# Variables
		$defaultPrefix = "wp_cpt_";
		$sqlCheck = "SHOW TABLES LIKE '{$defaultPrefix}";

		// ==== Start of Function ====
		// Checking
		foreach ($postTypeData as $postType => $postTypeArray) {
			// Preparing the statement
			$result = $wpdb->get_results("{$sqlCheck}{$postType}'");

			if (empty($result)) {
				// Migrating the data
				adminNotice('error', 'Please migrate the '.strtoupper($postType).' custom post type to the new table structure using the CPT Tables Plugin.');
			}
		}
	}
}
class OGVanHerkOffers {
    // ============ Constructor ============
    public function __construct() {
	    # Use this one if it is going to be run on the site itself. SMALl NOTE: There will be no input to the cronjobs table
	    // add_action('admin_init', [__CLASS__, 'examinePosts']);

	    # Use this one if it is going to be a cronjob.
	    self::examinePosts();
    }

    // ============ Declaring Variables ============
    # ==== Getters ====
	private static function lastCronjob() {
		// ==== Declaring Variables ====
		# Classes
		global $wpdb;

		// ==== Start of Function ====
		# Checking if the cronjob table exists
		$cronjobTableExists = $wpdb->get_results("SHOW TABLES LIKE 'cronjobs'");
		if (empty($cronjobTableExists)) {
            $wpdb->query("CREATE TABLE `cronjobs` (
                `cronjob_count` int(5) NOT NULL AUTO_INCREMENT,
                `name` varchar(60) DEFAULT NULL,
                `boolGiveLastCron` tinyint(1) DEFAULT NULL,
                `MemoryUsageMax` float NOT NULL,
                `memoryUsage` float NOT NULL,
                `datetime` datetime NOT NULL,
                `objectsCreated` int(5) DEFAULT NULL,
                `objectsUpdated` int(5) DEFAULT NULL,
                `duration` float NOT NULL,
                `boolDone` tinyint(1) DEFAULT NULL,
                PRIMARY KEY (`cronjob_count`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=COMPRESSED
            ");
		}

		return OGVanHerkSettingsData::$boolGiveLastCron ? ($wpdb->get_results("SELECT datetime FROM cronjobs ORDER BY datetime DESC LIMIT 1")[0]->datetime ?? 0) : 0;
	}
    public static function boolFirstInit(): bool {
        // ==== Start of Function ====
        return self::lastCronjob() == 0;
    }

    // ============ Functions ============
	# Kantoornummer conversion
	private static function getLocationCodes(): array {
		// ================ Declaring Variables ================
		# ==== Variables ====
		# Shit
		$strColumnName = 'location_afdelingscode';
		$strAfdelingName = 'location_api_name';
		$arrAfdelingcodes = [];
		$arrAfdelingNames = [];

		# Query
		$locationPosts = new WP_Query([
			'post_type' => 'location',
			'posts_per_page' => -1,
			'post_status' => 'any',
		]);
		$locationsExist = $locationPosts->have_posts();

		// ================ Start of Function ================
		if ($locationsExist) {
			# Getting the afdelingscodes and shoving them in an array
			foreach ($locationPosts->posts as $locationPost) {
				if (!isset($locationPost->{$strColumnName})) {continue;}
				$arrAfdelingcodes[] = $locationPost->{$strColumnName};
				$arrAfdelingNames[] = $locationPost->{$strAfdelingName};
			}
		}

		// Return it back
		return [$arrAfdelingcodes, $arrAfdelingNames];
	}

	# Post CUD (Create, Update, Delete)
	private static function getNames($post_data, $object, $databaseKey) {
		# ======== Post Title ========
		// Check if the post_title contains '|' or ';' to determine if to concatenate or just use one
		if (str_contains($databaseKey['post_title'], '|' ) ) {
			$postTitle = explode('|', $databaseKey['post_title']);
			$title = $postTitle[0];

			# Check the first one if it is empty, if it is, use the second one
			if (!empty($object->{$title})) {
				$post_data['post_title'] = $object->{$title};
			}
			else {
				$post_data['post_title'] = $object->{$postTitle[1]};
			}
		}
        elseif (str_contains($databaseKey['post_title'], ';')) {
			$postTitle = explode(';', $databaseKey['post_title']);
			$processedTitles = [];

			# Loop through the titles and check if they are empty, if they are, skip them
			foreach ($postTitle as $title) {
				$objectTitle = $object->{$title} ?? '';

				# Check if the title is uppercase, if it is, make it lowercase
				if (!empty($objectTitle)) {
					if ($objectTitle == strtoupper($objectTitle)) {
						$objectTitle = ucfirst(strtolower($objectTitle));
					}
					$processedTitles[] = $objectTitle;
				}
			}
			$post_data['post_title'] = implode(' ', $processedTitles);
		}
		else {
			# If there are no separators just think of it as one title and one variable
			$post_data['post_title'] = ucfirst(strtolower($object->{$databaseKey['post_title']} ?? ''));
		}

		# ======== Post Name ========
		if (str_contains($databaseKey['post_name'], '-')) {
			$arrPostNames = explode('-', $databaseKey['post_name']);
			$arrProcessedPostNames = [];

			# Loop through the post names and check if they are empty, if they are, skip them
			foreach ($arrPostNames as $postName) {
				$objectPostName = $object->{$postName} ?? '';

				# Check if the post name is uppercase, if it is, make it lowercase
				if (!empty($objectPostName)) {
					if ($objectPostName == strtoupper($objectPostName)) {
						$objectPostName = ucfirst(strtolower($objectPostName));
					}
					$arrProcessedPostNames[] = $objectPostName;
				}
			}
			$post_data['post_name'] = implode('-', $arrProcessedPostNames);
		}
        elseif (str_contains($databaseKey['post_name'], '|')) {
			$postTitle = explode('|', $databaseKey['post_name']);
			$title = $postTitle[0];

			# Check the first one if it is empty, if it is, use the second one
			if (!empty($object->{$title})) {
				$post_data['post_name'] = strtolower($object->{$title});
			}
			else {
				$post_data['post_name'] = strtolower($object->{$postTitle[1]});
			}
		}
		else {
			$post_data['post_name'] = strtolower($object->{$databaseKey['post_name'] ?? ''});
		}

		$post_data['post_name'] = sanitize_title($post_data['post_name']);

		# ======== Post Content ========
		$post_data['post_content'] = $object->{$databaseKey['post_content']} ?? '';

		# Return the post_data
		return $post_data;
	}
	private static function createPost($postTypeName, $OGobject, $databaseKey, $parentPostID=''): WP_Error|int {
		// ============ Declaring Variables ===========
		# Variables
		$post_data = [
			'post_type' => $postTypeName,
			'post_parent' => $parentPostID,
			'post_title' => '',
			'post_name' => '',
			'post_content' => '',
			'post_status' => 'draft',
		];
		$post_data = self::getNames($post_data, $OGobject, $databaseKey);

		// ============ Start of Function ============
		# Creating the post
		$postID = wp_insert_post($post_data);
		foreach ($OGobject as $key => $value) {
			add_post_meta($postID, $key, $value);
		}

		# Adding meta data for images
		self::updateMedia($postID, $postTypeName, $OGobject, $databaseKey);

		# Publishing the post
		wp_publish_post($postID);

		# Returning the postID
		return $postID;
	}
	private static function updatePost($postTypeName, $postID, $OGobject, $databaseKey, $parentPostID=''): void {
		// ======== Declaring Variables ========
		# Classes

		# Vars
		$post_data = [
			'ID' => $postID,
			'post_title' => '',
			'post_parent' => $parentPostID,
			'post_content' => ''
		];
		$post_data = self::getNames($post_data, $OGobject, $databaseKey);

		// ======== Start of Function ========
		# Overwriting the post
		wp_update_post($post_data);

		self::updateMedia($postID, $postTypeName, $OGobject, $databaseKey);

		# Updating the post meta
		foreach ($OGobject as $key => $value) {
			update_post_meta($postID, $key, $value);
		}
	}
	public static function deleteUnneededPosts($postTypeName, $databaseKeysObject, $objectIDs, $type=''): void {
		if (empty($objectIDs)) {return;}
		// ======== Declaring Variables ========
		# Variables
		$posts = new WP_Query([
			'post_type' => $postTypeName,
			'posts_per_page' => -1,
			'post_status' => 'any',
			'meta_key' => 'type',
			'meta_value' => $type
		]);
		// ======== Start of Function ========
		# Getting all the post IDs from the meta data
		foreach ($posts->posts as $post) {
			// ==== Declaring Variables ====
			# Getting the post ID
			$postTiara = $post->{$databaseKeysObject['ID']};

			// ==== Rest of loop ====
			# Checking if the post is in the database
			if (!in_array($postTiara, $objectIDs)) {
				# Delete the post
				wp_delete_post($post->ID, true);

				# Deleting every post with this as parent post
				$childPosts = new WP_Query(([
					'post_type' => $postTypeName,
					'posts_per_page' => -1,
					'post_parent' => $post->ID,
					'post_status' => 'any',
				]));
				foreach ($childPosts->posts as $childPost) {
					wp_delete_post($childPost->ID, true);

					# Deleting every post with this as parent post
					$childchildPosts = new WP_Query(([
						'post_type' => $postTypeName,
						'posts_per_page' => -1,
						'post_parent' => $post->ID,
						'post_status' => 'any',
					]));
					foreach ($childchildPosts->posts as $childchildPost) {
						wp_delete_post($childchildPost->ID, true);
					}
				}
				echo('Deleted post: ' . $post->ID . '<br/>');
			}
		}
	}

    # Media
	private static function updateMedia($postID, $postTypeName, $OGobject, $databaseKey): void {
		// ============ Declaring Variables ============
		# Classes
		global $wpdb;

		# Variables
		$databaseKeysMedia = $databaseKey['media'];
		$mediaTiaraIDName = !empty($databaseKeysMedia['mediaTiaraID']) ? $databaseKeysMedia['mediaTiaraID'] : $databaseKey['ID'];
		$postTypeName = !empty($databaseKeysMedia['folderRedirect']) ? $databaseKeysMedia['folderRedirect'] : $postTypeName;
		$mime_type_map = [
			'jpg' => 'image/jpeg',
			'png' => 'image/png',
			'pdf' => 'application/pdf',
			'mp4' => 'video/mp4',
		];
		$mime_type_map2 = [
			'Video' => 'video/mp4',
		];
		$guid_url = get_site_url();

		$mediaObjects = $wpdb->get_results("SELECT * FROM `{$databaseKeysMedia['tableName']}` WHERE `{$databaseKeysMedia['search_id']}` = $OGobject->id");

		// ============ Start of Function ============
		foreach ($mediaObjects as $mediaObject) {
			// ======== Declaring Variables ========
			# Mapping the data
			$mediaObject = OGVanHerkMapping::mapMetaData($mediaObject, ($databaseKeysMedia['mapping'] ?? []));
			$mediaQuery = new WP_Query([
				'post_type' => 'attachment',
				'meta_key' => $databaseKeysMedia['mediaName'],
				'meta_value' => $mediaObject->{$databaseKeysMedia['mediaName']},
				'posts_per_page' => -1,
				'post_status' => 'any',
			]);
			$mediaExists = $mediaQuery->have_posts();

			// Object last updated
			$objectLastUpdated = $OGobject->{$databaseKey['datum_gewijzigd']} ?? $OGobject->{$databaseKey['datum_toegevoegd']};

			# Vars
			$mediaTiaraID = $mediaObject->{$mediaTiaraIDName};
			$boolIsConnectedPartner = $mediaObject->{$databaseKeysMedia['media_Groep']} == 'Connected_partner';
			$post_mime_type = $mime_type_map[$mediaObject->{'bestands_extensie'}] ?? $mime_type_map2[$mediaObject->{$databaseKeysMedia['media_Groep']}] ?? 'unknown';
			$media_url = "og_media/{$postTypeName}_{$OGobject->{$databaseKeysMedia['object_keys']['objectVestiging']}}_{$OGobject->{$databaseKeysMedia['object_keys']['objectTiara']}}/{$OGobject->{$databaseKeysMedia['object_keys']['objectTiara']}}_{$mediaTiaraID}.$mediaObject->bestands_extensie";
			$post_data = [
				'post_content' => '',
				'post_title' => "{$mediaObject->{$mediaTiaraIDName}}-$mediaObject->bestandsnaam",
				'post_excerpt' => strtoupper($mediaObject->{$databaseKeysMedia['media_Groep']}),
				'post_status' => 'inherit',
				'comment_status' => 'open',
				'ping_status' => 'closed',
				'post_name' => "{$mediaObject->{$mediaTiaraIDName}}-$mediaObject->bestandsnaam",
				'post_parent' => $postID,
				'guid' => $boolIsConnectedPartner ? $mediaObject->media_URL : "$guid_url/$media_url",
				'menu_order' => $mediaObject->{'media_volgorde'},
				'post_type' => 'attachment',
				'post_mime_type' => $post_mime_type,
			];
			$post_meta = [
				'_wp_attached_file' => $boolIsConnectedPartner ? $mediaObject->media_URL : $media_url,
				'file_url' => $boolIsConnectedPartner ? $mediaObject->media_URL : $media_url,
				'_wp_attachment_metadata' => '',
				$databaseKey['objectCode'] => $OGobject->{$databaseKey['objectCode']},
				$databaseKeysMedia['media_Groep'] => strtoupper($mediaObject->{$databaseKeysMedia['media_Groep']}),
				$databaseKeysMedia['mediaName'] => $mediaObject->{$databaseKeysMedia['mediaName']},
				$databaseKeysMedia['datum_gewijzigd'] => $mediaObject->{$databaseKeysMedia['datum_gewijzigd']},
				$databaseKeysMedia['datum_toegevoegd'] => $mediaObject->{$databaseKeysMedia['datum_toegevoegd']},
				'_wp_attachment_image_alt' => '',
				$mediaTiaraIDName => $mediaObject->{$mediaTiaraIDName},
			];
			// ======== Start of Function ========
			# Checking if the media exists
			if ($mediaExists) {
				// ==== Declaring Variables ====
				# Getting post meta
				$postLastUpdated = $mediaQuery->post->MediaUpdated;

				// ==== Start of Function ====
				if ($postLastUpdated != $objectLastUpdated) {
					// Updating the media
					$post_data['ID'] = $mediaQuery->post->ID;
					wp_update_post($post_data);

					// Updating the meta data
					foreach ($post_meta as $key => $value) {
						update_post_meta($mediaQuery->post->ID, $key, $value);
						wp_set_object_terms($mediaQuery->post->ID, $value, $key);
					}
				}
			}
			else {
				// Creating the media
				$mediaID = wp_insert_post($post_data);

				// Adding the meta data
				foreach ($post_meta as $key => $value) {
					add_post_meta($mediaID, $key, $value);
					wp_set_object_terms($mediaID, $value, $key);
				}
			}
		}
	}
	public static function checkMedia($mediaDatabaseKeys): void {
		// ============ Declaring Variables ============
		# Classes
		global $wpdb;

		# Variables
		$mediaObjects = $wpdb->get_results("SELECT * FROM {$mediaDatabaseKeys['tableName']}");

		// ============ Start of Function ============
		# Looping through the media objects
		foreach($mediaObjects as $mediaObject) {
			# Checking if the media exists
			$mediaQuery = new WP_Query([
				'post_type' => 'attachment',
				'posts_per_page' => -1,
				'post_status' => 'any',
				'meta_key' => $mediaDatabaseKeys['id'],
				'meta_value' => $mediaObject->id
			]);

		}

	}

    # Nieuwbouw
	private static function checkBouwnummersPosts($postTypeName, $parentPostID, $OGBouwtype, $databaseKeys): array {
		// ======== Declaring Variables ========
		# Classes
		global $wpdb;

		# Variables
		$OGBouwtypeID = $OGBouwtype->id;
		$objectIDs = [];

		$OGBouwnummers = $wpdb->get_results("SELECT * FROM {$databaseKeys[2]['tableName']} WHERE {$databaseKeys[2]['id_bouwtypes']} = $OGBouwtypeID");

		// ======== Start of Function ========
		# Looping through the bouwnummers
		foreach ($OGBouwnummers as $OGBouwnummer) {
			# Checking if this OG bouwnummer is valid and if not just skip it.
			if (isset( $OGBouwnummer->{$databaseKeys[2]['ObjectStatus_database']} ) and $OGBouwnummer->{$databaseKeys[2]['ObjectStatus_database']} == '' ) {
				continue;
			}

			// ======== Declaring Variables ========
			# Variables
			$OGBouwnummer = OGVanHerkMapping::mapMetaData($OGBouwnummer, ($databaseKeys[2]['mapping'] ?? []), self::getLocationCodes(), $databaseKeys);

			# Adding the 'type' meta data
			$OGBouwnummer->type = $databaseKeys[1]['type'];

			# Post - Bouwnummer
			$postData = new WP_Query([
				'post_type' => $postTypeName,
				'meta_key' => $databaseKeys[2]['ID'],
				'meta_value' => $OGBouwnummer->{$databaseKeys[2]['ID']},
				'post_parent' => $parentPostID,
				'posts_per_page' => -1,
				'post_status' => 'any',
			]);
			$bouwNummerExisted = $postData->have_posts();

			if ($bouwNummerExisted) {
				$postID = $postData->post->ID;
				$dateUpdatedPost = $postData->post->{$databaseKeys[2]['datum_gewijzigd']} ?? $postData->post->{$databaseKeys[2]['datum_toegevoegd']};
			}

			# Database - Bouwnummer
			$dateUpdatedDatabase = $OGBouwnummer->{$databaseKeys[2]['datum_gewijzigd']} ?? $OGBouwnummer->{$databaseKeys[2]['datum_toegevoegd']};

			// ======== Rest of loop ========
			# Checking if post exists
			if ($bouwNummerExisted) {
				// Checking if the bouwtype is updated
				if ($dateUpdatedPost != $dateUpdatedDatabase) {
					self::updatePost($postTypeName, $postID, $OGBouwnummer, $databaseKeys[2], $parentPostID);
					echo("Updated Nieuwbouw bouwnummer: $postID<br/>");
				}
			}
			else {
				// Creating the post
				$postID = self::createPost($postTypeName, $OGBouwnummer, $databaseKeys[2], $parentPostID);
				echo("Created Nieuwbouw bouwnummer: $postID<br/>");
			}

			# Adding the post ID to the array
			$objectIDs[] = $OGBouwnummer->{$databaseKeys[2]['ID']};

			# Freeing memory
			unset($OGBouwnummer);
		}

		// Returning the objectIDs
		return $objectIDs;
	}
	private static function checkBouwtypesPosts($postTypeName, $parentPostID, $OGProject, $databaseKeys): array {
		// ======== Declaring Variables ========
		# Classes
		global $wpdb;

		# Variables
		$OGProjectID = $OGProject->id;
		$objectIDs = [];
		$bouwnummerIds = [];

		$OGBouwtypes = $wpdb->get_results("SELECT * FROM {$databaseKeys[1]['tableName']} WHERE {$databaseKeys[1]['id_projecten']} = $OGProjectID");

		// ======== Start of Function ========
		# Looping through the bouwtypes
		foreach ($OGBouwtypes as $OGBouwtype) {
			# Checking if this OG bouwtype is valid and if not just skip it.
			if ( isset( $OGBouwtype->{$databaseKeys[1]['ObjectStatus_database']} ) and $OGBouwtype->{$databaseKeys[1]['ObjectStatus_database']} == '' ) {
				continue;
			}

			// ======== Declaring Variables ========
			$OGBouwtype = OGVanHerkMapping::mapMetaData($OGBouwtype, ($databaseKeys[1]['mapping'] ?? []), self::getLocationCodes(), $databaseKeys);
			# Adding the 'type' meta data
			$OGBouwtype->type = $databaseKeys[1]['type'];

			# Post - Bouwtype
			$postData = new WP_Query([
				'post_type' => $postTypeName,
				'meta_key' => $databaseKeys[1]['ID'],
				'meta_value' => $OGBouwtype->{$databaseKeys[1]['ID']},
				'post_parent' => $parentPostID,
				'posts_per_page' => -1,
				'post_status' => 'any'
			]);
			$bouwTypeExisted = $postData->have_posts();

			if ($bouwTypeExisted) {
				$postID = $postData->post->ID;
				$dateUpdatedPost = $postData->post->{$databaseKeys[1]['datum_gewijzigd']} ?? $postData->post->{$databaseKeys[1]['datum_toegevoegd']};
			}

			# Database - Bouwtype
			$dateUpdatedObject = $OGBouwtype->{$databaseKeys[1]['datum_gewijzigd']} ?? $OGBouwtype->{$databaseKeys[1]['datum_toegevoegd']};

			// ======== Rest of loop ========
			# Checking if the post exists
			if ($bouwTypeExisted) {
				// Checking if the post is updated
				if ($dateUpdatedPost != $dateUpdatedObject) {
					// Updating/overwriting the post
					self::updatePost($postTypeName, $postID, $OGBouwtype, $databaseKeys[1], $parentPostID);
					echo("Updated Nieuwbouw bouwtype: {$postID}<br/>");
				}
			}
			else {
				// Creating the post
				$postID = self::createPost($postTypeName, $OGBouwtype, $databaseKeys[1], $parentPostID);
				echo("Created Nieuwbouw bouwtype: {$postID}<br/>");
			}

			# Adding the postID to the array
			$objectIDs = array_merge($objectIDs, [$OGBouwtype->{$databaseKeys[1]['ID']}]);
			# Checking the children (bouwnummers)
			$bouwnummerIds = array_merge($bouwnummerIds, self::checkBouwnummersPosts($postTypeName, $postID, $OGBouwtype, $databaseKeys));

			# Freeing memory
			unset($OGBouwtype);
		}

		# Returning the objectIDs
		return [$objectIDs, $bouwnummerIds];
	}
	private static function checkNieuwbouwPosts($postTypeName, $databaseKeys): void {
		# ============ Declaring Variables ============
        # Classes
        global $wpdb;

        # Variables
        $projectIds = [];
		$OGProjects = $wpdb->get_results("SELECT * FROM {$databaseKeys[0]['tableName']} WHERE {$databaseKeys[0]['datum_gewijzigd_unmapped']} >= '".self::lastCronjob()."'");

        // ============ Start of Function ============
		# Creating/Updating the posts based off if it's the first initation or not
        if (self::boolFirstInit()) {
            # Creating the posts
            foreach ($OGProjects as $OGProject) {
	            // ======== Declaring Variables ========
	            # Remapping the object
	            $OGProject = OGVanHerkMapping::mapMetaData($OGProject, ($databaseKeys[0]['mapping'] ?? []), self::getLocationCodes());

                # Adding the 'type' meta data
                $OGProject->type = $databaseKeys[0]['type'];

	            // ======== Rest of loop ========
                # Creating the post
                $postID = self::createPost($postTypeName, $OGProject, $databaseKeys[0]);
                echo("Created Nieuwbouw project: {$postID}<br/>");

                # Updating the count
	            OGVanHerkSettingsData::$intObjectsCreated++;

                # Adding the postID to the array
                $projectIds[] = $OGProject->{$databaseKeys[0]['ID']};

	            # Checking the child-posts
	            $arrayIds = self::checkBouwtypesPosts($postTypeName, $postID, $OGProject, $databaseKeys);
            }
        }
        else {
            // ======== Declaring Variables ========
            # Vars
	        $projectPosts = new WP_Query([
		        'post_type' => $postTypeName,
		        'meta_key' => 'type',
		        'meta_value' => 'project',
		        'posts_per_page' => -1,
		        'post_status' => 'any',
	        ]);

            // ======== Rest of ELSE ========
            foreach ($OGProjects as $OGProject) {
                // ==== Declaring Variables ====
                # Remapping the object
                $OGProject = OGVanHerkMapping::mapMetaData($OGProject, ($databaseKeys[0]['mapping'] ?? []), self::getLocationCodes());
	            # Adding the 'type' meta data
	            $OGProject->type = $databaseKeys[0]['type'];

                $postKey = array_search($OGProject->{$databaseKeys[0]['ID']}, array_column($projectPosts->posts, $databaseKeys[0]['ID']));

                if (!$postKey) {
                    // ==== Creating the post ====
                    $postID = self::createPost($postTypeName, $OGProject, $databaseKeys[0]);
                    echo("Created Nieuwbouw project: {$postID}<br/>");

                    # Updating the count
	                OGVanHerkSettingsData::$intObjectsCreated++;
                }
                else {
                    // == Declaring Variables ==
                    $postID = $projectPosts->posts[$postKey]->ID;
                    $dateUpdatedPost = $projectPosts->posts[$postKey]->{$databaseKeys[0]['datum_gewijzigd']} ?? $projectPosts->posts[$postKey]->{$databaseKeys[0]['datum_toegevoegd']};
                    $dateUpdatedObject = $OGProject->{$databaseKeys[0]['datum_gewijzigd']} ?? $OGProject->{$databaseKeys[0]['datum_toegevoegd']};

                    // == Rest of ELSE ==
                    if ($dateUpdatedPost != $dateUpdatedObject) {
                        // ==== Updating the post ====
                        self::updatePost($postTypeName, $projectPosts->posts[$postKey]->ID, $OGProject, $databaseKeys[0]);
                        echo("Updated Nieuwbouw project: {$projectPosts->posts[$postKey]->ID}<br/>");

                        # Updating the count
	                    OGVanHerkSettingsData::$intObjectsUpdated++;
                    }
                }

                # Adding the postID to the array
                $projectIds[] = $OGProject->{$databaseKeys[0]['ID']};

	            # Checking the child-posts
	            $arrayIds = self::checkBouwtypesPosts($postTypeName, $postID, $OGProject, $databaseKeys);
            }
        }
	}

	# Wonen / BOG
	private static function checkNormalPosts($postTypeName, $OGobjects, $databaseKey): void {
		// ============ Declaring Variables ============
		# Variables
		$objectIDs = [];

        // ============ Start of Function ============
        # Creating/Updating the posts based off if it's the first initation or not
        if (self::boolFirstInit()) {
            # Creating the posts
	        foreach ($OGobjects as $OGobject) {
                // ======== Declaring Variables ========
                # Remapping the object
                $OGobject = OGVanHerkMapping::mapMetaData($OGobject, ($databaseKey['mapping'] ?? []), self::getLocationCodes());

                // ======== Rest of loop ========
		        # Creating the post
		        $postID = self::createPost($postTypeName, $OGobject, $databaseKey);
		        echo("Created {$postTypeName} object: {$postID}<br/>");

		        # Updating the count
		        OGVanHerkSettingsData::$intObjectsCreated++;

		        # Adding the object ID to the array
		        $objectIDs[] = $OGobject->{$databaseKey['ID']};
            }
        }
        else {
	        // ======== Declaring Variables ========
            # Vars
            $postData = new WP_Query([
                'post_type' => $postTypeName,
                'posts_per_page' => -1,
                'post_status' => 'any',
            ]);

            // ======== Rest of ELSE ========
            foreach ($OGobjects as $OGobject) {
                // ==== Declaring Variables ====
	            # Remapping the object
	            $OGobject = OGVanHerkMapping::mapMetaData($OGobject, ($databaseKey['mapping'] ?? []), self::getLocationCodes());
                $postKey = array_search($OGobject->{$databaseKey['ID']}, array_column($postData->posts, $databaseKey['ID']));

                if (!$postKey) {
                    // ==== Creating the post ====
                    $postID = self::createPost($postTypeName, $OGobject, $databaseKey);
                    echo("Created {$postTypeName} object: {$postID}<br/>");

                    # Updating the count
	                OGVanHerkSettingsData::$intObjectsCreated++;
                }
                else {
                    // == Declaring Variables ==
                    $dateUpdatedPost = $postData->posts[$postKey]->{$databaseKey['datum_gewijzigd']} ?? $postData->posts[$postKey]->{$databaseKey['datum_toegevoegd']};
                    $dateUpdatedObject = $OGobject->{$databaseKey['datum_gewijzigd']} ?? $OGobject->{$databaseKey['datum_toegevoegd']};

                    // == Rest of ELSE ==
                    if ($dateUpdatedPost != $dateUpdatedObject) {
                        // ==== Updating the post ====
                        self::updatePost($postTypeName, $postData->posts[$postKey]->ID, $OGobject, $databaseKey);
                        echo("Updated {$postTypeName} object: {$postData->posts[$postKey]->ID}<br/>");

                        # Updating the count
	                    OGVanHerkSettingsData::$intObjectsUpdated++;
                    }
                }

                # Adding the object ID to the array
                $objectIDs[] = $OGobject->{$databaseKey['ID']};
            }
        }
	}

	# Main
	public static function examinePosts(): void {
		// ============ Declaring Variables ============
		# Classes
		global $wpdb;

		// ============ Start of Function ============
		# ==== Checking all the post types ====
		foreach (OGVanHerkPostTypeData::customPostTypes() as $postTypeName => $postTypeArray) {
            # If statement to filter which ones we want to try out or not. Basically not needed overall
			// if ($postTypeName == 'wonen' or $postTypeName == 'bedrijven') {continue;}

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
			echo("<h1>".$postTypeName."</h1>");
			if ($boolIsNieuwbouw) {
				self::checkNieuwbouwPosts($postTypeName, $databaseKeys);
			}
			else {
				foreach ($databaseKeys as $databaseKey) {
					$OGobjects = $wpdb->get_results("SELECT * FROM {$databaseKey['tableName']} WHERE {$postTypeArray['database_tables']['object']['datum_gewijzigd_unmapped']} >= '".self::lastCronjob()."'");

					# Removing every null out of the objects so Wordpress won't get crazy.
					foreach ($OGobjects as $key => $object) {
						foreach ($object as $key2 => $value) {
							if ($value == 'null' or $value == 'NULL' or $value == null) {
								$OGobjects[$key]->{$key2} = '';
							}
						}
					}

					if (!empty($OGobjects)) {
						self::checkNormalPosts($postTypeName, $OGobjects, $databaseKey);
					}
				}
			}
		}
	}
}
<?php
function UPCP_Add_New_Catalogue_Style() {
	global $Extension;

	if ( ! function_exists( 'wp_handle_upload' ) ) {
    	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}
	$CSS_File = $_FILES['catalogue_new_css'];
	$JS_File = $_FILES['catalogue_new_js'];

	$upload_overrides = array(
		'test_form' => false, 
		'test_type' => false,
		'unique_filename_callback' => 'UPCP_Overwrite_Files'
	);

	$Extension = 'css';
	add_filter( 'upload_dir', 'UPCP_Modify_Upload_Dir' );
	$movefile = wp_handle_upload( $CSS_File, $upload_overrides );
	remove_filter( 'upload_dir', 'UPCP_Modify_Upload_Dir' );

	$Extension = 'js';
	add_filter( 'upload_dir', 'UPCP_Modify_Upload_Dir' );
	$movefile = wp_handle_upload( $JS_File, $upload_overrides );
	remove_filter( 'upload_dir', 'UPCP_Modify_Upload_Dir' );

	if ( $movefile && ! isset( $movefile['error'] ) ) {
	    /*echo "File is valid, and was successfully uploaded.\n";
	    var_dump( $movefile );*/
	} else {
	    /**
	     * Error generated by _wp_handle_upload()
	     * @see _wp_handle_upload() in wp-admin/includes/file.php
	     */
	    echo $movefile['error'];
	}

	UPCP_Move_Additional_Catalogue_Styles();

	$update = __("New style has been successfully added.", 'ultimate-product-catalogue');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	
	return $user_update;
}

function UPCP_Move_Additional_Catalogue_Styles() {
	$WP_Directory = wp_upload_dir();

	$Style_Information = array();

	$CSS_Files = scandir($WP_Directory['basedir'] . "/upcp-addtl-styles/css");
	if ($CSS_Files) {
		foreach ($CSS_Files as $File) {
			$from = $WP_Directory['basedir'] . "/upcp-addtl-styles/css/" . $File; 
			$to = UPCP_CD_PLUGIN_PATH . "/css/" . $File;
			
			if (file_exists($from) and $File != "." and $File != "..") {
				copy($from, $to);
	
				$File_Contents = file_get_contents($to);
	
				if (strpos($File_Contents, 'Style Name:') !== false) {
					$Style_Name = substr($File_Contents, strpos($File_Contents, 'Style Name:') + 11);
					$Style_Name = trim(substr($Style_Name, 0, strpos($Style_Name, PHP_EOL)));
				}
				else {$Style_Name = "";}
	
				$Style_Slug = substr($File_Contents, strpos($File_Contents, 'Style Slug:') + 11);
				$Style_Slug = trim(substr($Style_Slug, 0, strpos($Style_Slug, PHP_EOL)));
	
				$Style_Description = substr($File_Contents, strpos($File_Contents, 'Description:') + 12);
				$Style_Description = trim(substr($Style_Description, 0, strpos($Style_Description, PHP_EOL)));
	
				if ($Style_Name != "") {
					$Style_Info = array('Name' => $Style_Name, 'Slug' => $Style_Slug, 'Description' => $Style_Description);
					$Style_Information[] = $Style_Info;
				}
			}
		}
	}

	if (is_dir($WP_Directory['basedir'] . '/upcp-addtl-styles/js')) {
		$JS_Files = scandir($WP_Directory['basedir'] . "/upcp-addtl-styles/js");
	}
	else {
		$JS_Files = false;
	}

	if ($JS_Files) {
		foreach ($JS_Files as $File) {
			$from = $WP_Directory['basedir'] . "/upcp-addtl-styles/js/" . $File; 
			$to = UPCP_CD_PLUGIN_PATH . "/js/" . $File;
			
			if (file_exists($from) and $File != "." and $File != "..") {
				copy($from, $to);
			}
		}
	}

	update_option('UPCP_Installed_Skins', $Style_Information);
}

function UPCP_Modify_Upload_Dir($Directory) {
    global $Extension;

    if (!is_dir($Directory['basedir'] . '/upcp-addtl-styles/')) {mkdir($Directory['basedir'] . '/upcp-addtl-styles/');}
    if (!is_dir($Directory['basedir'] . '/upcp-addtl-styles/' . $Extension)) {mkdir($Directory['basedir'] . '/upcp-addtl-styles/' . $Extension);}

    return array(
        'path'   => $Directory['basedir'] . '/upcp-addtl-styles/' . $Extension,
        'url'    => $Directory['baseurl'] . '/upcp-addtl-styles/' . $Extension,
        'subdir' => '/upcp-addtl-styles/' . $Extension,
    ) + $Directory;
}

function UPCP_Overwrite_Files($dir, $name, $ext){
    return $name;
}

?>
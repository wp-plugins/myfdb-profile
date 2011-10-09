<?php
/**
 * Enqueue style-file, if it exists.
 * This will work with WordPress 2.7
 */

function MyFDB_Profile_Add_Stylesheet() {
	if (is_admin()) {
		// Add CSS for FlexBOX js plugin
		$StyleUrl = WP_PLUGIN_URL.'/myfdb-profile/css/jquery.flexbox.css';
		$StyleFile = WP_PLUGIN_DIR.'/myfdb-profile/css/jquery.flexbox.css';
		if ( file_exists($StyleFile) ) {
			wp_register_style('MyFDB_FlexBOXStyleSheets', $StyleUrl);
			wp_enqueue_style( 'MyFDB_FlexBOXStyleSheets');
		}
	} else {
		// Load MyFDB CSS
		$StyleUrl = WP_PLUGIN_URL.'/myfdb-profile/css/myfdb_style.css';
		$StyleFile = WP_PLUGIN_DIR.'/myfdb-profile/css/myfdb_style.css';
		if ( file_exists($StyleFile) ) {
			wp_register_style('MyFDB_StyleSheets', $StyleUrl);
			wp_enqueue_style( 'MyFDB_StyleSheets');
		}
	}
}

// Register with hook 'wp_print_styles'
add_action('wp_print_styles', 'MyFDB_Profile_Add_Stylesheet');
add_action('admin_init', 'MyFDB_Profile_Add_Stylesheet'); // Load CSS for FlexBOX Plugin


/**
 * Add Flex box and other java scripts
 * This will work with WordPress 2.7
 */

function MyFDB_Profile_Add_Scripts() {
	if (is_admin()) {
		/*
		 // Add Jquery v 1.4.2
		 $ScriptUrl = WP_PLUGIN_URL.'/myfdb-profile/js/jquery.min.js';
		 $ScriptFile = WP_PLUGIN_DIR.'/myfdb-profile/js/jquery.min.js';
		 if ( file_exists($ScriptFile) ) {
			wp_register_script('MyFDB_Jquery_1.4.2', $ScriptUrl);
			wp_enqueue_script('MyFDB_Jquery_1.4.2');
			}
			*/

		// Add js Flex box full
		$ScriptUrl = WP_PLUGIN_URL.'/myfdb-profile/js/jquery.flexbox.js';
		$ScriptFile = WP_PLUGIN_DIR.'/myfdb-profile/js/jquery.flexbox.js';
		if ( file_exists($ScriptFile) ) {
			wp_register_script('MyFDB_ScriptFlexBOX', $ScriptUrl);
			wp_enqueue_script('MyFDB_ScriptFlexBOX');
		}

		/*
		 // Add js Flex Box min
		 $ScriptUrl = WP_PLUGIN_URL.'/myfdb-profile/js/jquery.flexbox.min.js';
		 $ScriptFile = WP_PLUGIN_DIR.'/myfdb-profile/js/jquery.flexbox.min.js';
		 if ( file_exists($ScriptFile) ) {
			wp_register_script('MyFDB_ScriptFlexBOXmin', $ScriptUrl);
			wp_enqueue_script('MyFDB_ScriptFlexBOXmin');
			}
			*/

		// Add MyFDB java scripts
		$ScriptUrl = WP_PLUGIN_URL.'/myfdb-profile/js/myfdb.js';
		$ScriptFile = WP_PLUGIN_DIR.'/myfdb-profile/js/myfdb.js';
		if ( file_exists($ScriptFile) ) {
			wp_register_script('MyFDB_Scripts', $ScriptUrl);
			wp_enqueue_script('MyFDB_Scripts');
		}
	}
}

// Register with hook 'wp_print_scripts'
add_action('wp_print_scripts', 'MyFDB_Profile_Add_Scripts');


/**
 * Get all template(s) name(s)
 */

function MyFDB_Get_All_template_names() {
	$names = array();

	$dir_iter = new DirectoryIterator(WP_PLUGIN_DIR.'/myfdb-profile/template/');

	foreach ($dir_iter as $file) {
		$t_name = $file->getFilename();

		$fileExt_start = strrpos($t_name, ".php", 1);
		if ($fileExt_start != false) $names[] = substr_replace($t_name, '', $fileExt_start);
	}
	return $names;
}


/**
 * Get lowest priority of 'the_content' filter
 */

function wp_get_lowest_priority() {
	global $wp_filter;

	$priority = 10;

	foreach ($wp_filter['the_content'] as $key => $filters) {
		if ($key > $priority) $priority = $key;
	}

	$priority++;

	return $priority;
}


/**
 * Function for auto output MyFDB profiles on post/page
 */
function wp_myfdb_auto_output($content) {
	global $post;

	$way_use = get_option('myfdb_way_use');

	if (isset($way_use) && $way_use === 'Auto') $content .= wp_myfdb_post_profiles($post, '', false, false);

	return $content;
}

// Hook to content - plugin auto output profiles
add_action('the_content', 'wp_myfdb_auto_output', wp_get_lowest_priority());


/**
 * Function for output on WP page
 */

function wp_myfdb_post_profiles($post, $class = '', $echo = true, $manual = true) {
	$render = '';

	// way to output
	$way_use = get_option('myfdb_way_use');

	if (($manual && $way_use === 'Manual') || (!$manual && $way_use === 'Auto'))
	if (is_object($post) && $post->ID>0) {
		if ($post->post_type == 'page') $myfdb_enable = get_option('myfdb_enable_page');
		else if ($post->post_type == 'post') $myfdb_enable = get_option('myfdb_enable_post');

		if (isset($myfdb_enable) && $myfdb_enable === 'Y') {
			$t_post_meta = get_post_meta($post->ID, 'myfdb_post_profiles', true);
			$t_myfdb_post_profiles = (is_array($t_post_meta['profiles'])?$t_post_meta['profiles']:array());
				
			if(count($t_myfdb_post_profiles)>0) {
					
				// Load default template
				if ($post->post_type == 'page') $default_template = get_option('myfdb_default_template_page');
				else if ($post->post_type == 'post') $default_template = get_option('myfdb_default_template_post');
					
				// Load selected template
				$template = $t_post_meta['template'];
					
				if ($template === '' && isset($default_template)) $template = $default_template;

				// Render plugin
				$render = '<div class="myfdb_profiles_space"></div>';
				$render .= '<div id="myfdb_profiles_plugin" '.($class!=''?'class="'.$class.'"':'').'>';
				$render .= '<ul class="myfdb_profiles">';

				// Count profiles
				$prof_count = 1;
				$prof_total = count($t_myfdb_post_profiles);

				foreach ($t_myfdb_post_profiles as $myfdb_post_profile) {
					if ($myfdb_post_profile['category'] == 'people') {
						$t_myfdb_profile = new MyFDB_People($myfdb_post_profile['tag_name']);
					} elseif ($myfdb_post_profile['category'] == 'companies') {
						$t_myfdb_profile = new MyFDB_Companies($myfdb_post_profile['tag_name']);
					}

					if(isset($t_myfdb_profile->IDTagName) && $t_myfdb_profile->IDTagName>0){
						// <li> or with separator

						$render .= '<li'.($prof_total>1 && ($prof_count % 2)!=0?' class="myfdb_widget_separated"':'').'>';
						$render .= $t_myfdb_profile->Render($template);
						$render .= '</li>';
						$prof_count++;
					}
				}

				$render .= '</ul>';
				$render .= '<!--<img src="'.WP_PLUGIN_URL.'/myfdb-profile/img/myfdb_widget_logo.png" class="myfdb_widget_logo" alt="MyFDB.com - The web\'s largest credited fashion database." />-->';
				$render .= '</div>';
				$render .= '<div class="myfdb_profiles_space"></div>';
			}
		}
	}

	if ($echo) echo $render;
	else return $render;
}


/**
 * Template For render profiles
 */

function myfdb_output_template($profiles = array(), $template = 'myfdb_full', $main_class = ''){
	$render = '';
	if(is_array($profiles) && count($profiles)>0) {
		$render = '<div class="myfdb_profiles_space"></div>';
		$render .= '<div id="myfdb_profiles_plugin" '.($main_class!=''?'class="'.$main_class.'"':'').'>';
		$render .= '<ul class="myfdb_profiles">';

		// Count profiles
		$prof_count = 1;
		$prof_total = count($profiles);

		foreach ($profiles as $myfdb_profile) {
			// <li>
			$render .= '<li'.($prof_total>1 && ($prof_count % 2)!=0?' class="myfdb_widget_separated"':'').'>';
			$render .= $myfdb_profile->Render($template);
				
			$prof_count++;
			$render .= '</li>';
		}

		$render .= '</ul>';
		$render .= '<!--<img src="'.WP_PLUGIN_URL.'/myfdb-profile/img/myfdb_widget_logo.png" class="myfdb_widget_logo" alt="MyFDB.com - The web\'s largest credited fashion database." />-->';
		$render .= '</div>';
		$render .= '<div class="myfdb_profiles_space"></div>';
	}
	return $render;
}


/**
 * Function for outputting profile not or assigned to post
 */
// myfdb_profile_lists(array(array('cat'=>'p', 'tag'=>'some_tag_name'), array('cat'=>'c', 'tag'=>'some_tag_name')), template)
function myfdb_profile_lists($profiles = array(), $template = 'myfdb_full') {

	$result_profiles = array();

	if(is_array($profiles) && count($profiles)>0) {
		foreach ($profiles as $profile) {
			if ($profile['cat'] == 'p') {
				$t_myfdb_profile = new MyFDB_People($profile['tag']);
				if($t_myfdb_profile) $result_profiles[] = $t_myfdb_profile;
			} elseif ($profile['cat'] == 'c') {
				$t_myfdb_profile = new MyFDB_Companies($profile['tag']);
				if($t_myfdb_profile) $result_profiles[] = $t_myfdb_profile;
			}
		}
	}

	// Output profile(s)
	if (count($result_profiles)>0) { // output profile(s)
		return myfdb_output_template($result_profiles, $template); // return rendered profiles
	}
}


/**
 * Shortcode function for post output
 */

// [myfdbprofile category="ProfileCategory" tagname="ProfileTagName" [template="template"]]
function myfdb_profile($atts) {
	if(!isset($atts['category'])) $atts['category'] = '';
	if(!isset($atts['tagname'])) $atts['tagname'] = '';
	if(!isset($atts['template'])) $atts['template'] = 'myfdb_full';

	$output = '';

	if($atts['category'] != '' && $atts['tagname'] != '') {
		if ($atts['category'] == 'people') {
			$myfdb_profile = new MyFDB_People($atts['tagname']);
		} elseif ($atts['category'] == 'companies') {
			$myfdb_profile = new MyFDB_Companies($atts['tagname']);
		}

		if($myfdb_profile) {
			$output = myfdb_output_template(array($myfdb_profile), $atts['template']);
		}
	}

	return $output;
}

add_shortcode('myfdbprofile', 'myfdb_profile');
?>
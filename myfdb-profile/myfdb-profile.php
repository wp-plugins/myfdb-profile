<?php
/**
 * Plugin Name: MyFDB Profile
 * Plugin URI: http://blog.myfdb.com/myfdb-plugin/
 * Description: This plugin provides detailed information about fashion models, companies, and brands from MyFDB (My Fashion Database)
 * Author: Anton Ruchkin at Sound Strategies
 * Version: 2.9
 * Author URI: http://www.soundst.com/
 */

/**
 * Main Plugin section (plugin install, plugin uninstall, plugin main menu etc.)
 */

// Includes
include 'class/MyFDB_Companies.php';
include 'class/MyFDB_People.php';
include 'class/MyFDB_Search.php';

include 'lib/functions.php';
include 'lib/widget.php'; // No use - because deprecated

// Main menu
function myfdb_main_menu() {
	// Register in admin menu
	if ( current_user_can('edit_posts') && function_exists('add_submenu_page') ) {
		add_options_page('MyFDB Profiles', 'MyFDB Profiles', 8, basename(__FILE__), 'myfdb_get_option_form');
	}
}

function myfdb_get_option_form() {
	// Load all template(s) name(s)
	$templates = MyFDB_Get_All_template_names();

	if (isset($_POST['myfdb_update']) && $_POST['myfdb_update'] === 'Save all options') {

		// save plugin options (if send)
		// Caching ?
		/*if (isset($_POST['myfdb_caching'])&&($_POST['myfdb_caching'] != get_option('myfdb_caching'))){
			update_option('myfdb_caching', 'Y');
		} else if(!isset($_POST['myfdb_caching'])) {
			update_option('myfdb_caching', '');
		}*/

		// Cache time
		if (isset($_POST['myfdb_cache_time'])&&($_POST['myfdb_cache_time'] != get_option('myfdb_cache_time'))){
			if (empty($_POST['myfdb_cache_time']) || $_POST['myfdb_cache_time']<=0 || !is_numeric($_POST['myfdb_cache_time'])) $_POST['myfdb_cache_time'] = 0;
			update_option('myfdb_cache_time', $_POST['myfdb_cache_time']);
			echo '<div id="message" class="updated fade"><p><strong>Cache time was changed.</strong></p></div>';
		}

		// Output methods
		if (isset($_POST['myfdb_way_use'])&&($_POST['myfdb_way_use'] != get_option('myfdb_way_use'))){
			update_option('myfdb_way_use', $_POST['myfdb_way_use']);
			echo '<div id="message" class="updated fade"><p><strong>Output methods was changed.</strong></p></div>';
		}

		// Enable for page ?
		if (isset($_POST['myfdb_enable_page'])&&($_POST['myfdb_enable_page'] != get_option('myfdb_enable_page'))){
			update_option('myfdb_enable_page', 'Y');
		} else if(!isset($_POST['myfdb_enable_page'])) {
			update_option('myfdb_enable_page', '');
		}

		// Enable for post ?
		if (isset($_POST['myfdb_enable_post'])&&($_POST['myfdb_enable_post'] != get_option('myfdb_enable_post'))){
			update_option('myfdb_enable_post', 'Y');
		} else if(!isset($_POST['myfdb_enable_post'])) {
			update_option('myfdb_enable_post', '');
		}

		// Set default template for page
		if (isset($_POST['myfdb_default_template_page'])&&($_POST['myfdb_default_template_page'] != get_option('myfdb_default_template_page'))){
			if (empty($_POST['myfdb_default_template_page']) || !in_array($_POST['myfdb_default_template_page'], $templates)) $_POST['myfdb_default_template_page'] = 'myfdb_full';
			update_option('myfdb_default_template_page', $_POST['myfdb_default_template_page']);
			echo '<div id="message" class="updated fade"><p><strong>Default template for page was changed (selected).</strong></p></div>';
		}

		// for post
		if (isset($_POST['myfdb_default_template_post'])&&($_POST['myfdb_default_template_post'] != get_option('myfdb_default_template_post'))){
			if (empty($_POST['myfdb_default_template_post']) || !in_array($_POST['myfdb_default_template_post'], $templates)) $_POST['myfdb_default_template_post'] = 'myfdb_full';
			update_option('myfdb_default_template_post', $_POST['myfdb_default_template_post']);
			echo '<div id="message" class="updated fade"><p><strong>Default template for post was changed (selected).</strong></p></div>';
		}
	}

	// Clean cache
	if (isset($_POST['myfdb_clean_cache'])&&($_POST['myfdb_clean_cache'] == 'Y')){
		if (myfdb_clean_cache()) echo '<div id="message" class="updated fade"><p><strong>Cached MyFDB profiles was cleaned.</strong></p></div>';
		else echo '<div id="message" class="updated fade"><p><strong>ERROR: can\'t delete all myfdb profiles right now.</strong></p></div>';
	}

	// Reset plugin options
	if (isset($_POST['myfdb_reset_options'])&&($_POST['myfdb_reset_options'] == 'Y')){
		// Cache
		// update_option('myfdb_caching', 'Y'); // Cache file?
		update_option('myfdb_cache_time', 24); // Cache time in hours
		// Other options
		update_option('myfdb_way_use', 'Auto'); // Plugin use method
		update_option('myfdb_enable_page', 'Y'); // Enable plugin for page
		update_option('myfdb_enable_post', 'Y'); // Enable plugin for post
		update_option('myfdb_default_template_page', 'myfdb_full'); // Default template for MyFDB profiles on post
		update_option('myfdb_default_template_post', 'myfdb_full'); // Default template for MyFDB profiles on page

		echo '<div id="message" class="updated fade"><p><strong>Plugin options was reset to default.</strong></p></div>';
	}

	// Load plugin options
	// $caching = get_option('myfdb_caching');
	$cache_time = get_option('myfdb_cache_time');
	$way_use = get_option('myfdb_way_use');
	$enable_page = get_option('myfdb_enable_page');
	$enable_post = get_option('myfdb_enable_post');
	$default_template_page = get_option('myfdb_default_template_page');
	$default_template_post = get_option('myfdb_default_template_post');

	// Options form's
	echo '
	<div class="wrap">
		<h2>MyFDB Profiles</h2><br />';	
	echo '
		<form id="myfdb_options" name="myfdb_options" method="post" action="">';

	// Cache options
	/*echo '
				<b>Cache options:</b><br />
				<input type="checkbox" id="myfdb_caching" name="myfdb_caching" value="Y"'.($caching=='Y'?' checked="checked"':'').'" />
				<label for="myfdb_caching">Cache MyFDB profiles?</label><br />';
	*/
	// Cache time
	echo '
				<label for="myfdb_cache_time">Cache refresh time (in hours):</label>
				<select id="myfdb_cache_time" name="myfdb_cache_time" style="width: 46px;">';
	for ($count = 1; $count<=24; $count++) {
		echo '<option value="'.$count.'" '.($count==$cache_time?'selected':'').'>'.$count.'</option>';
	}
	echo '
				</select><br />
				<br />';

	// Output methods
	echo '
				<b>MyFDB profile output methods:</b><br />
				<input type="radio" id="myfdb_way_use_Auto" name="myfdb_way_use" value="Auto" '.($way_use=='Auto'?' checked="checked"':'').'>
				<label for="myfdb_way_use_Auto">Automatically add MyFDB Widget</label><br />
				<input type="radio" id="myfdb_way_use_Manual" name="myfdb_way_use" value="Manual" '.($way_use=='Manual'?' checked="checked"':'').'> 
				<label for="myfdb_way_use_Manual">I will edit my own templates</label><br />
				<b>If you are editing your own templates you must add the following code in the template: </b><br />
				&nbsp;<b>wp_myfdb_post_profiles ($post, $class)</b> - in WP theme<br />
				&nbsp;&nbsp;$post - the post object (current post/page);<br />
				&nbsp;&nbsp;$class - CSS class for design of &lt;div&gt;.<br />
				<br />';

	// Enable plugin for
	echo '
				<input type="checkbox" id="myfdb_enable_page" name="myfdb_enable_page" value="Y"'.($enable_page=='Y'?' checked="checked"':'').'" />
				<label for="myfdb_enable_page">Enable MyFDB Plugin for pages.</label><br />
				<input type="checkbox" id="myfdb_enable_post" name="myfdb_enable_post" value="Y"'.($enable_post=='Y'?' checked="checked"':'').'" />
				<label for="myfdb_enable_post">Enable MyFDB Plugin for posts.</label><br />
				<br />';

	// Default template for page
	if (count($templates)>0) {
		echo '<b>Default template for page:</b><br />
					<select id="myfdb_default_template_page" name="myfdb_default_template_page">';
		foreach ($templates as $template) {
			echo '<option value="'.$template.'" '.($template==$default_template_page?'selected':'').'>'.$template.'</option>';
		}
		echo '</select><br />';
	}

	// Default template for post
	if (count($templates)>0) {
		echo '<b>Default template for post:</b><br />
					<select id="myfdb_default_template_post" name="myfdb_default_template_post">';
		foreach ($templates as $template) {
			echo '<option value="'.$template.'" '.($template==$default_template_post?'selected':'').'>'.$template.'</option>';
		}
		echo '</select><br />';
	}
	echo '<br />
				<input type="submit" id="myfdb_update" name="myfdb_update" value="Save all options">
		</form>';

	// Clean cache
	echo '<br />
		<form id="myfdb_clean_form" name="myfdb_clean_form" method="post" action="">
				<input type="hidden" name="myfdb_clean_cache" value="Y" />
				<input type="submit" id="myfdb_clean_cache" value="Clean cached profiles">
		</form>
	</div>';
	
	// Reset plugin options
	echo '<br />
		<form id="myfdb_reset_form" name="myfdb_reset_form" method="post" action="">
				<input type="hidden" name="myfdb_reset_options" value="Y" />
				<input type="submit" id="myfdb_reset_options" value="Reset plugin options to default">
		</form>
	</div>';
}

// Install plugin
function myfdb_install() {
	// Cache
	// if(get_option('myfdb_caching')===false) add_option('myfdb_caching', 'Y'); // Cache file?
	if(get_option('myfdb_caching')!==false) delete_option('myfdb_caching'); // Cache option was delete
	if(get_option('myfdb_cache_time')===false) add_option('myfdb_cache_time', 24); // Cache time in hours
	// Other options
	if(get_option('myfdb_way_use')===false) add_option('myfdb_way_use', 'Auto'); // Plugin use method
	if(get_option('myfdb_enable_page')===false) add_option('myfdb_enable_page', 'Y'); // Enable plugin for page
	if(get_option('myfdb_enable_post')===false) add_option('myfdb_enable_post', 'Y'); // Enable plugin for post
	if(get_option('myfdb_default_template_page')===false) add_option('myfdb_default_template_page', 'myfdb_full'); // Default template for MyFDB profiles on post
	if(get_option('myfdb_default_template_post')===false) add_option('myfdb_default_template_post', 'myfdb_full'); // Default template for MyFDB profiles on page
}

// Uninstall plugin
function myfdb_uninstall() {
	myfdb_clean_cache();

	// Turn off settings clean
	/*
	 delete_option('myfdb_caching'); // cache file?
	 delete_option('myfdb_cache_time'); // cache time in hours
	 // Other options
	 delete_option('myfdb_way_use'); // Plugin use method
	 delete_option('myfdb_enable_page'); // Enable plugin for page
	 delete_option('myfdb_enable_post'); // Enable plugin for post
	 delete_option('myfdb_default_template_page'); // Default template for MyFDB profiles on post
	 delete_option('myfdb_default_template_post'); // Default template for MyFDB profiles on page
	 */
}

// Clean cache
function myfdb_clean_cache(){
	global $wpdb;
	// Delete all people profiles
	$delete_people_query = "DELETE FROM $wpdb->options WHERE option_name LIKE 'myfdb_people_c_%'";
	// Delete all companie profiles
	$delete_companie_query = "DELETE FROM $wpdb->options WHERE option_name LIKE 'myfdb_companie_c_%'";

	if ($wpdb->query($delete_people_query)!==false && $wpdb->query($delete_companie_query)!==false) return true;
	else return false;
}

// Hooks for plugin menu
register_activation_hook(__FILE__, "myfdb_install"); // install plugin
register_deactivation_hook(__FILE__, "myfdb_uninstall"); // uninstall plugin
add_action('admin_menu', 'myfdb_main_menu'); // plugin option


/**
 * Edit Page/Post section
 */

// Init meta box
function myfdb_meta_init() {
	// Add meta box in Post and Page edit
	foreach (array('post','page') as $type) {
		add_meta_box('myfdb_all_meta', 'MyFDB Profiles', 'myfdb_meta_edit', $type, 'normal', 'high');
	}

	// Save entered MyFDB profiles
	add_action('save_post','myfdb_meta_save');
}

// Edit Post/Page menu meta box Options form
function myfdb_meta_edit() {
	global $post;

	// Load pervius value
	$meta = get_post_meta($post->ID,'myfdb_post_profiles',true);

	// Load profiles
	if(isset($meta['profiles'])) $profiles = $meta['profiles'];
	else $profiles = array();

	// Load all template(s) name(s)
	$templates = MyFDB_Get_All_template_names();

	// Load main default template name
	if ($post->post_type == 'page') $plugin_default_template = get_option('myfdb_default_template_page');
	elseif ($post->post_type == 'post') $plugin_default_template = get_option('myfdb_default_template_post');

	// Load post/page default template
	if(isset($meta['template'])) $default_template = $meta['template'];
	else $default_template = '';

	$output = '<p>Enter the profile(s):<br />
	<div id="myfdb_meta-profile_myfdb">';

	if(count($profiles)>0) {
		foreach ($profiles as $key => $val) {
			if ($val['category']=='people') $t_profile = new MyFDB_People($val['tag_name']);
			else if ($val['category']=='companies') $t_profile = new MyFDB_Companies($val['tag_name']);
				
			if ($t_profile->IDTagName!==0) {
				$output .= '<div id="myfdb_meta-profiles-'.$key.'" >Profile:<br />';
				$output .= '<label for="myfdb_meta-profiles-'.$key.'-category">category:</label>
					<select onchange="myfdb_search(\'myfdb_meta-profiles-'.$key.'-\')" id="myfdb_meta-profiles-'.$key.'-category"
					name="myfdb_meta[profiles]['.$key.'][category]">
					<option value="people" '.($val['category']=='people'?'selected':'').'>people</option>
					<option value="companies" '.($val['category']=='companies'?'selected':'').'>company</option>
					</select><br />
					<label for="myfdb_meta-profiles-'.$key.'-tag_name">Name:</label>
					<div id="myfdb_meta-profiles-'.$key.'-tag_name" style="width: 232px;position: relative; z-index: 0; height: 28px;">
					<input class="widefat" id="myfdb_meta-profiles-'.$key.'-tag_name_hidden" type="hidden" name="myfdb_meta-profiles-'.$key.'-tag_name" value="'.$val['tag_name'].'">
					<input class="widefat" onfocus="myfdb_search(\'myfdb_meta-profiles-'.$key.'-\', \''.$t_profile->Name.'\')" id="myfdb_meta-profiles-'.$key.'-tag_name_input" class="ffb-input" style="width: 232px;" value="'.$t_profile->Name.'">
					</div><input type="button" onclick="myfdb_delete(\'myfdb_meta-profiles-'.$key.'\')" value="Delete"/><br /><br />';
				$output .= '</div>';
			}
		}
	} else {
		$output .= '<div id="myfdb_meta-profiles-0" >Profile:<br />';
		$output .= '<label for="myfdb_meta-profiles-0-category">category:</label>
			<select onchange="myfdb_search(\'myfdb_meta-profiles-0-\')" id="myfdb_meta-profiles-0-category"
			name="myfdb_meta[profiles][0][category]">
			<option value="people">people</option>
			<option value="companies">companies</option>
			</select><br />
			<label for="myfdb_meta-profiles-0-tag_name">Name:</label>
			<div id="myfdb_meta-profiles-0-tag_name"><script type="text/javascript">
				$MyFDBjs(document).ready(function() { 
					myfdb_search(\'myfdb_meta-profiles-0-\');
				});
			</script></div><input type="button" onclick="myfdb_delete(\'myfdb_meta-profiles-0\')" value="Delete"/><br />';
		$output .= '</div>';
	}

	$output .= '
	</div>
	</p>
	<br />'; // Insert new profiles after that
	$output .= '<input type="button" id="myfdb_meta-profiles-add_button" onclick="myfdb_add_field(\'myfdb_meta-\', \'myfdb_meta\')" value="Add"/>';
	// Select default template
	if (count($templates)>0) {
		$output .= '<br /><br /><b>Default template for MyFDB profile(s):</b><br />
		<select id="myfdb_meta-default_template" name="myfdb_meta-default_template">';
		foreach ($templates as $template) {
			$output .= '<option value="'.$template.'" '.($template==$default_template?'selected':'').'>'.$template.'</option>';
		}
		$output .= '<option value="" '.($default_template==''?'selected':'').'>default</option>';
		$output .= '</select><br />';
	}
	// Create a custom nonce for submit verification later
	$output .= '<input type="hidden" name="myfdb_meta_noncename" value="'.wp_create_nonce(__FILE__).'" />';

	echo ($output);
}

function myfdb_meta_save() {
	$post_id = $_POST['post_ID'];
	$post_type = $_POST['post_type'];

	// Authentication checks for meta box
	if (!wp_verify_nonce($_POST['myfdb_meta_noncename'], __FILE__)) return $post_id;

	// Check user permissions
	if ($_POST['post_type'] == 'page') {
		if (!current_user_can('edit_page', $post_id)) return $post_id;
	} else {
		if (!current_user_can('edit_post', $post_id)) return $post_id;
	}

	$current_instance = get_post_meta($post_id, 'myfdb_post_profiles', true);
	$new_instance = (isset($_POST['myfdb_meta'])?$_POST['myfdb_meta']:array());

	// Clean up new instance paramiters
	if (isset($new_instance['profiles'])) {
		foreach ($new_instance['profiles'] as $key => $profile) {
			if (!isset($_POST['myfdb_meta-profiles-'.$key.'-tag_name']) || !in_array($profile['category'], array('people', 'companies'))) unset($new_instance['profiles'][$key]);
			else $new_instance['profiles'][$key]['tag_name'] = esc_attr($_POST['myfdb_meta-profiles-'.$key.'-tag_name']);
		}
	}
	myfdb_meta_clean($new_instance);

	// Save MyFDB Profiles
	if(isset($new_instance['profiles'])) {
		if(count($new_instance['profiles'])>0) {
			foreach ($new_instance['profiles'] as $key => $profile) {
				if ($profile['category'] == 'people') {
					$t_myfdb_profile = new MyFDB_People($profile['tag_name']);
				} elseif ($profile['category'] == 'companies') {
					$t_myfdb_profile = new MyFDB_Companies($profile['tag_name']);
				}

				if (isset($t_myfdb_profile->IDTagName))
				if ($t_myfdb_profile->IDTagName>0) {
					if($new_instance['profiles'][$key]['tag_name'] != $t_myfdb_profile->IDTagName) $new_instance['profiles'][$key]['tag_name'] = $t_myfdb_profile->IDTagName;
				} else {
					unset($new_instance['profiles'][$key]);
				}
			}
		}
	}

	// Set default MyFDB profile template to post/page
	// Load all template(s) name(s)
	$templates = MyFDB_Get_All_template_names();

	// Load main default template name
	if ($post_type == 'page') $plugin_default_template = get_option('myfdb_default_template_page');
	elseif ($post_type == 'post') $plugin_default_template = get_option('myfdb_default_template_post');

	if (isset($_POST['myfdb_meta-default_template'])) {
		$new_instance['template'] = $_POST['myfdb_meta-default_template'];
	} else if (isset($plugin_default_template) && in_array($plugin_default_template, $templates)) {
		$new_instance['template'] = $plugin_default_template;
	} else {
		$new_instance['template'] = 'myfdb_full';
	}

	// Save Post meta
	if ($current_instance!='') {
		if (count($new_instance)===0) delete_post_meta($post_id,'myfdb_post_profiles');
		else update_post_meta($post_id,'myfdb_post_profiles',$new_instance);
	} else if (count($new_instance)>0) {
		add_post_meta($post_id,'myfdb_post_profiles',$new_instance,true);
	}

	return $post_id;
}

// Recursion clean array of meta box parameters
function myfdb_meta_clean(&$arr) {
	if (is_array($arr)) {
		foreach ($arr as $key => $val) {
			if (is_array($val)) {
				myfdb_meta_clean($val);
				if (count($val)===0) {
					unset($arr[$key]);
				}
			} else {
				if (trim($val) == '') {
					unset($arr[$key]);
				}
			}
		}
		if (count($arr)===0) {
			unset($arr);
		}
	}
}

// Init MyFDB meta box
add_action('admin_init', 'myfdb_meta_init');


// JSON FlexBOX search request
function myfdb_flex_box_search() {
	if($_GET['myfdb-fb-action']=='search') {
		$MyFDB_Search = new MyFDB_Search($_GET['q'], $_GET['category'], $_GET['p']);

		$output = $MyFDB_Search->JSON_output($_GET['q'], $_GET['tc']);
		header("Content-length: ".strlen($output));
		header("Content-type: application/json");
		header("Content-Disposition: inline;");

		echo ($output);
		exit(0);
	}
}

add_action('admin_init', 'myfdb_flex_box_search', 1);
?>

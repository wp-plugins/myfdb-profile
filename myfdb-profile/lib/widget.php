<?php
/**
 * Widget section
 */

class MyFDB_Profile_Widget extends WP_Widget {
	function MyFDB_Profile_Widget() {
		parent::WP_Widget(false, $name = 'MyFDB Profile', array(), array('width'=>251));
	}

	function widget($args, $instance) {
		// Output profile
		if(isset($instance['tag_name'])) {
			if ($instance['category'] == 'people') {
				$myfdb_profile = new MyFDB_People($instance['tag_name']);
			} elseif ($instance['category'] == 'companies') {
				$myfdb_profile = new MyFDB_Companies($instance['tag_name']);
			}
			
			if (isset($myfdb_profile->IDTagName) && $myfdb_profile->IDTagName>0) {
				// Render plugin
				$output = '<div class="myfdb_profiles_space"></div>';
				$output .= '<div id="myfdb_profiles_plugin" class="'.$this->id.'">';
				$output .= '<ul class="myfdb_profiles">';
				
				$output .= '<li>';
				$output .= $myfdb_profile->Render($instance['template']);
				$output .= '</li>';
				
				$output .= '</ul>';
				$output .= '<!--<img src="'.WP_PLUGIN_URL.'/myfdb-profile/img/myfdb_widget_logo.png" class="myfdb_widget_logo" alt="MyFDB.com - The web\'s largest credited fashion database." />-->';
				$output .= '</div>';
				$output .= '<div class="myfdb_profiles_space"></div>';
				
				echo $output;
			}
		}
	}

	function update($new_instance, $old_instance) {
		$new_instance['tag_name'] = (isset($_POST['widget-'.$this->id.'-tag_name'])?$_POST['widget-'.$this->id.'-tag_name']:'');

		if ($new_instance['category'] == 'people') {
			$t_myfdb_profile = new MyFDB_People($new_instance['tag_name']);
		} elseif ($new_instance['category'] == 'companies') {
			$t_myfdb_profile = new MyFDB_Companies($new_instance['tag_name']);
		}

		if (isset($t_myfdb_profile->IDTagName))
		if ($t_myfdb_profile->IDTagName>0) {
			$t_myfdb_profile->save();
		} else {
			unset($new_instance['tag_name']);
		}

		return $new_instance;
	}

	function form($instance) {
		// Load all template(s) name(s)
		$templates = MyFDB_Get_All_template_names();

		// Load category
		if(isset($instance['category'])) $category = $instance['category'];
		else $category = '';

		// Load tag name
		if(isset($instance['tag_name'])) $tag_name = $instance['tag_name'];
		else $tag_name = '';

		// Load template
		if(isset($instance['template'])) $default_template = $instance['template'];
		else $default_template = 'myfdb_full';

		echo '<p>Enter profile info:<br />
		<div style="height: 160px;" id="'.$this->get_field_id('profile_myfdb').'">';

		// Select category
		echo '<p>
				<label for="'.$this->get_field_id('category').'">category:</label>
				<select onchange="myfdb_search(\''.$this->get_field_id().'\')" class="widefat" id="'.$this->get_field_id('category').'"
				name="'.$this->get_field_name('category').'">
				<option value="people" '.($category=='people'?'selected':'').'>people</option>
				<option value="companies" '.($category=='companies'?'selected':'').'>companies</option>
				</select><br />';
			
		// Tag name
		if ($category=='people') $t_profile = new MyFDB_People($tag_name);
		else if ($category=='companies') $t_profile = new MyFDB_Companies($tag_name);
		// TODO if click input - set widget height 300px after select profile height get back to 160px
		echo '
				<label for="'.$this->get_field_id('tag_name').'">tag name:</label>
				<div id="'.$this->get_field_id('tag_name').'" onclick="document.getElementById(\''.$this->get_field_id('profile_myfdb').'\').style.height=\'300px\';" style="width: 218px;position: relative; z-index: 1000; height: 28px;">
					<input class="widefat" id="'.$this->get_field_id('tag_name').'_hidden" type="hidden" name="'.$this->get_field_id('tag_name').'" value="'.$t_profile->IDTagName.'">
					<input class="widefat" onfocus="myfdb_search(\''.$this->get_field_id().'\', \''.$t_profile->Name.'\');" id="'.$this->get_field_id('tag_name').'_input" class="ffb-input" style="width: 232px;" value="'.($t_profile->Name!=''?$t_profile->Name:'Enter profile name').'">
				</div><br />';
			
		// Template
		if (count($templates)>0) {
			echo '
				<label for="'.$this->get_field_id('template').'">template:</label>
				<select class="widefat" id="'.$this->get_field_id('template').'" name="'.$this->get_field_name('template').'">';
			foreach ($templates as $template) {
				echo '<option value="'.$template.'" '.($template==$default_template?'selected':'').'>'.$template.'</option>';
			}
			echo '</select><br />';
		}
		echo '</p>
		</div>
		</p>';
	}
}

/**
 * Register our widget class.
 */

function MyFDB_Profile_Load_Widgets() {
	register_widget( 'MyFDB_Profile_Widget' );
}

/**
 * Register hooks
 */

//Add function to widgets_init that register our widget class.
add_action('widgets_init', create_function('', 'return register_widget("MyFDB_Profile_Widget");'));
?>
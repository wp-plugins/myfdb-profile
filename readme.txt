=== MyFDB Profiles ===
Contributors: SoundStrategies
Donate link: None
Tags: myfdb, fashion
Requires at least: 2.0.2
Tested up to: 3.2
Stable tag: 3.0.0

This plugin provides detailed information about fashion models, companies, and brands from MyFDB (My Fashion Database)


== Description ==

This plugin provides detailed information about fashion models, companies, and brands from MyFDB (My Fashion Database)

The data presented by the plugin is displayed on individual posts or on pages.  It also be implemented as a widget (which will appear on your site based on your theme).  The MyFDB profile widget can be dragged and dropped into any widget container.

The plugin includes two pre-defined styles (presentation templates, but you can also create your own custom styles. 

== Installation ==

1. Upload the myfdb-profile folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Adjust the plugin settings 
4. Please refer to the plugin home page at http://blog.myfdb.com/myfdb-plugin/ for detailed instructions

== Frequently Asked Questions ==

Please refer to the plugin home page at http://blog.myfdb.com/myfdb-plugin/

== Screenshots ==

1. **Settings** - The following settings can be modified:  
   **Cache refresh time (in hours)** Data requested from the MyFDB database is stored on your host server until it expires.  This greatly reduces page load times.  You can adjust the cache refresh time in the MyFDB plugin settings.  
   **MyFDB profile output methods** Controls the positioning of the content on single posts and pages – The default setting automatically adds the widget to the bottom of the post or page. However, this can be disabled and you can control the position by manually adding it to your templates.  This is done by adding the  following in the desired position within the template where “$post” is the post object (current post/page) and “$class” is CSS class for design of div element: *wp_myfdb_post_profiles ($post, $class)*   
   **Enable MyFDB plugin for pages** This setting is only relevant if you are automatically adding the MyFDB widget (see above).  It allows you disable the widget on pages.  
   **Enable MyFDB plugin for posts** This setting is only relevant if you are automatically adding the MyFDB widget (see above).  It allows you disbale the widget on posts.  
   **Default template for page** This setting controls the default template for the page.  If you have not specified a custom template you are limited to the myfdb_long or myfdb_short formats.  
   **Default template for post** This setting controls the default template for the single post.  If you have not specified a custom template you are limited to the myfdb_long or myfdb_short formats.  You can also create your own custom styles.  Custom CSS must be placed in a folder named *myfdb-profile* within your theme (/wp-content/themes/<your theme>/myfdb-profile).  To build your own style sheet you can start by copying the contents of */wp-content/plugins/myfdb-profile/css/myfdb_style.css*.  

2. **Full format Presentation** (template: myfdb_full) includes profile name, number of credits, and link to MyFDB profile 

3. **Short format presentation** (template: myfdb_short) includes only profile name, number of credits, link to MyFDB profile, and three recent credits with images and titles (see example below)


4. **Editing Pages and Posts** - To add a profile to a page post scroll down on the editor page and locate the MyFDB Profiles section.  If the plugin has been enabled and this section does not appear, you must edit your Screen Options.  Use the dropdown box to select either a person or a company and start typing in the name.  If you pause during typing, a list of suggestions will appear and you can select from that list.  Click the “Add” button to add the profile.  Repeat the process to select additional profiles.  You can also specify a different template (see Style/Templates above) to alter the presentation just for that post or page.

5. **Widget** -  The MyFDB profile can also be implemented as a widget (which will appear on your site based on your theme).  The MyFDB profile widget can be dragged and dropped into any widget container.  

Please refer to the plugin home page at http://blog.myfdb.com/myfdb-plugin/ for more detailed instructions

== Changelog ==

= 2.9 =
This is the initial build that was released to the WordPress community

== Upgrade Notice ==

= 3.0 =
This version includes two additional display formats which are myfdb_plus_short and myfdb_plus_full



== Advanced ==

This Plugin has two additional functions:  

1. Short code function: [myfdbprofile category="ProfileCategory" tagname="ProfileTagName" [template="template"]] - can be used to output the profile in post/page text.  
2. PHP function for output a profile(s) assigned to any post: myfdb_profile_lists(myfdb_profile_lists(array(array('cat'=>'p', 'tag'=>'some_tag_name'), array('cat'=>'c', 'tag'=>'some_tag_name'))), template).  The profiles array consists of mini arrays that have 'cat' (category) and 'tag' (profile tag name) parameters.
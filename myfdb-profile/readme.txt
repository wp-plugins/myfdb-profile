//-------------------------------------------------------------------------//
// Plugin: MyFDB Profile                                                   //
// Description: Insert MyFDB fashion profile information into posts        //
// Author: Anton Ruchkin from Sound Strategies                             //
// Version: 2.9                                                            //
// Author URI: http://www.soundst.com/                                     //
//-------------------------------------------------------------------------//

Plugin installation:
	1 Unpack myfdb-profile_2.9.zip the file to the /wp-content/plugins/ folder.
	2 Activate the plugin through the 'Plugins' menu in WordPress.

Plugin can:
	Cache profiles in WP DB (for fast load), for editing cache settings, use admin panel "Settings" -> "MyFDB Profiles" (settings page).
	Enabling plugin for page/post (see in "MyFDB Profiles" settings page).
	Use different MyFDB profile template separate for every post/page.
	Output widget(s) (with MyFDB profile) through sidebar.

How to use:
	Plugin add's metabox "MyFDB Profiles" on page/post edit page.
	In that metabox you can enter profile name or ID (for people profile - added name searching), also you can add new or remove old MyFDB profile. And besides all - select overall template for profiles.
	For entering ID profile. need to enter it and wait until search try to find profile with ID name (for make shure that MyFDB don't have profile name with digital name like entered ID),search show: "No profiles were found" if in MyFDB no such profile, after that just press enter for confirm entering. At current moment MyFDB don't have search for company profiles, so for that profiles need enter ID or tag name.
	Here is example: For "Jed Root Inc." need enter "687" or "687-jed-root-inc".

	To output profiles on page/post need to use php function: wp_myfdb_post_profiles ($post, $class) - in WP theme
		$post - the post object;
		$class - CSS clas of output <div>.
		
	Plugin can use different template, select needable template on post/page edit page, or (if nothing was selected) plugin used default template (set up this template on "MyFDB Profiles" settings page).
	Now plugin have two template: "myfdb_full" and "myfdb_short". Name of the template it's a name of file in "wp-content/plugins/myfdb-profile/template/" folder, so you can edit current, or add new template;
	Also plugin have CSS for profile displaying, it's located at "wp-content/plugins/myfdb-profile/css/myfdb_style.css" file.

P.S. Plugin have two extra functions:
	1. Short code function: [myfdbprofile category="ProfileCategory" tagname="ProfileTagName" [template="template"]] - it can help to output profile in post/page text.
	2. PHP function for output profile(s) not(or is) assigned to any post: myfdb_profile_lists(myfdb_profile_lists(array(array('cat'=>'p', 'tag'=>'some_tag_name'), array('cat'=>'c', 'tag'=>'some_tag_name'))), template).
		Profiles array consist of mini arrays that have 'cat' (category) and 'tag' (profile tag name) parameters.
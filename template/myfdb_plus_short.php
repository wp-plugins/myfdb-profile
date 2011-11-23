<?php if(isset($myfdb_profile)) {

$render .= '<div class="short-plus-main">';
	
// title of MyFDB with link to http://www.myfdb.com
$render .= '<div class="login-full-plus"><a href="http://www.myfdb.com" alt="MyFDB.com - The web\'s largest credited fashion database." target="_blank"><img src="/wp-content/plugins/myfdb-profile/img/myfdb-logo-plus.png" alt="" /></a></div>';

// container main
$render .= '<div class="myfdb_main_info">';
	
$render .= '<h3 class="title-shortplus"><a href="'.$myfdb_profile->ProfileURL.'" alt="'.$myfdb_profile->Name.'" target="_blank">'.$myfdb_profile->Name.'</a></h3>';

$render .= '<div class="short-info full-plus-info">';

$render .= '<div class="credits-fullplus"><a alt="Fashion credits" href="'.$myfdb_profile->CreditsURL.'" target="_blank">'.($myfdb_profile->TotalCredits>0?$myfdb_profile->TotalCredits:'0').' Credits</a></div>';

$render .= '</div>';

// view full profile
$render .= '<a class="short-plus-link fplus-profile-link" alt="'.$myfdb_profile->Name.' - MyFDB Profile" href="'.$myfdb_profile->ProfileURL.'" target="_blank">View Full Profile</a>';

$render .= '</div>';
}?>

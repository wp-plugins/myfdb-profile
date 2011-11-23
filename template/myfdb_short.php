<?php if(isset($myfdb_profile)) {

// title of MyFDB with link to http://www.myfdb.com
$render .= '<h3><a href="'.$myfdb_profile->ProfileURL.'" alt="'.$myfdb_profile->Name.' profile on MyFDB.com" target="_blank">'.$myfdb_profile->Name.'</a> on <span class="myfdb_title"><a href="http://www.myfdb.com" alt="MyFDB.com - The web\'s largest credited fashion database." target="_blank">MyFDB.com</a></span></h3>';
// container main information
$render .= '<div class="myfdb_main_info">';
	// credits
	$render .= '<a class="myfdb_credit_count" alt="Fashion credits" href="'.$myfdb_profile->CreditsURL.'" target="_blank">'.($myfdb_profile->TotalCredits>0?$myfdb_profile->TotalCredits:'0').' credits</a>';
	// profile link
	$render .= '<a class="myfdb_profile_link" alt="'.$myfdb_profile->Name.' - MyFDB Profile" href="'.$myfdb_profile->ProfileURL.'" target="_blank">View profile >></a>';
$render .= '</div>';
}?>
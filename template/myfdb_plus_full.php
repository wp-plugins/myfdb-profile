<?php if(isset($myfdb_profile)) {

// title of MyFDB with link to http://www.myfdb.com
$render .= '<div class="login-full-plus"><a href="http://www.myfdb.com" alt="MyFDB.com - The web\'s largest credited fashion database." target="_blank"><img src="/wp-content/plugins/myfdb-profile/img/myfdb-logo-plus.png" alt="" /></a></div>';

$render .= '<h3 class="title-full-plus"><a href="'.$myfdb_profile->ProfileURL.'" alt="'.$myfdb_profile->Name.'" target="_blank">'.$myfdb_profile->Name.'</a></h3>';

$render .= '<div class="full-plus-info">';

// model & credits
$render .= '<div class="credits-fullplus"><a alt="Fashion credits" href="'.$myfdb_profile->CreditsURL.'" target="_blank">'.($myfdb_profile->TotalCredits>0?$myfdb_profile->TotalCredits:'0').' Credits</a></div>';
	
	// About
		if (!empty($myfdb_profile->About)) {
		$render .= '
		<div class="about-fplus">
		<h4 class="title-about-fplus">About:</h4>
		<p class="content-about-fplus">'.$myfdb_profile->About.'<a href="'.$myfdb_profile->AboutURL.'" target="_blank"> Read more >></a></p></div>';
	} 
	// recent credits
	if (count($myfdb_profile->RecentCredits)>0) {
		$render .= '<div class="full-plus-bg"><h4 class="pfull-rcredits">Recent credits:</h4></div>
		
	 <ul class="main-full-plus">';
		foreach ($myfdb_profile->RecentCredits as $key => $RecentCr){
			$render .= '
		 <li class="text-block-fplus'.(($key+1)==count($myfdb_profile->RecentCredits)?' lastborder':'').'">
			 <a href="'.$RecentCr->credit_url.'" target="_blank"><img src="'.$RecentCr->credit_image_url.'" alt="'.$RecentCr->credit_name.'" align="left" /></a>
			 
			 <div class="blockr-fullpluss">
				<a class="read-more-fullplus" href="'.$RecentCr->credit_url.'" target="_blank"></a>
			 	<span class="title-fpluss">'.$myfdb_profile->Name.'</span>
			 	<span class="text-fpluss">'.$RecentCr->credit_name.'</span>
				
				
			 </div>
		 
		 </li>';
		}
		$render .= '</ul>';
	}
$render .= '</div>';

$render .= '<a class="fplus-profile-link" alt="'.$myfdb_profile->Name.' - MyFDB Profile" href="'.$myfdb_profile->ProfileURL.'" target="_blank">View Full Profile</a>';

}?>
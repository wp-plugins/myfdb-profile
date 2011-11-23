<?php
class MyFDB_People {
	const WP_Source = 'myfdb_people_c_';
	/*
	 * name: Name of the person
	 * profile_url: Url to the person profile page on myfdb.com
	 * history: The history of the person truncated to 275 characters
	 * history_url: Url to the person details page on MyFDB.com
	 * credits_url: Url to credits page on Myfdb.com
	 * total_credits: Total credits as listed on MyFDB.com
	 * recent_credits: List of 3 most recent credits
	 * credit_name: Name of credited on
	 * credit_url: Url to credited on
	 * credit_image_url: Url to 80px wide image
	 * credit_type: Credited as type
	 * */
	var $IDTagName = 0; // id[-tagname]

	var $Name = ''; //
	var $ProfileURL = ''; //
	var $About = ''; //
	var $AboutURL = ''; //
	var $TotalCredits = 0; //
	var $CreditsURL = ''; //
	var $RecentCredits = array(); //

	var $UpdateTime = '0000-00-00 00:00:00'; // Last update date and time
	
	function MyFDB_People($IDTagName = 0) {
		$IDTagName = $this->getID($IDTagName);
		
		if ($IDTagName>0) {
			$this->read($IDTagName);
		}
	}
	
	function read($IDTagName = 0, $from_MyFDB = false) {
		$IDTagName = $this->getID($IDTagName);
		if ($IDTagName>0) {
			
			if (!($this->isSaved($IDTagName) && $this->isCached($IDTagName) /*&& $this->isCacheON()*/) || $from_MyFDB) {
				ini_set('user_agent', 'styleite.com api');
				$result = @file_get_contents('http://www.myfdb.com/people/'.$IDTagName.'/summary.json');

				if($result !== false) {
					$result = html_entity_decode($result, null, 'utf-8');
					$result = json_decode($result);
					
					$this->IDTagName = $IDTagName;
					$this->Name = $result->name; //
					$this->ProfileURL = $result->profile_url; //
					$result->biography = strip_tags($result->biography); //
					if (strlen($result->biography)>0 && $result->biography[strlen($result->biography)-1] != '.') $result->biography .= '...';
					$this->About = $result->biography; //
					$this->AboutURL = $result->biography_url; //
					$this->TotalCredits = $result->total_credits; //
					$this->CreditsURL = $result->credits_url; //
					$this->RecentCredits = $result->recent_credits; //
						
					//$this->UpdateTime = date('Y-m-d H:i:s'); // Now date time stamp
					
					// if ($this->isCacheON()) 
					$this->save();

					return true;
				}
			} else {
				$t_profile = get_option(self::WP_Source.$IDTagName);
				if ($t_profile!==false) {
					if (is_object($t_profile)) {
						
						$this->IDTagName = $IDTagName;
						$this->Name = $t_profile->Name; //
						$this->ProfileURL = $t_profile->ProfileURL; //
						$this->About = $t_profile->About; //
						$this->AboutURL = $t_profile->AboutURL; //
						$this->TotalCredits = $t_profile->TotalCredits; //
						$this->CreditsURL = $t_profile->CreditsURL; //
						$this->RecentCredits = $t_profile->RecentCredits; //

						$this->UpdateTime = $t_profile->UpdateTime; // 
						
						return true;
					}
				}
			}
		}

		return false;
	}
	
	function delete() {
		if (delete_option(self::WP_Source.$this->IDTagName)) return true;
		else return false;
	}
	
	function save(){
		$this->UpdateTime = date('Y-m-d H:i:s'); // Now date time stamp
		if (update_option(self::WP_Source.$this->IDTagName, $this)) return true;
		else return false;
	}
	
	function Render($template = 'myfdb_full', $IDTagName = 0){
		$render = '';
		$myfdb_profile = $this; // Check it

		$template_source = WP_PLUGIN_DIR.'/myfdb-profile/template/'.$template.'.php';
			
		if (file_exists($template_source)) include $template_source;
		else include WP_PLUGIN_DIR.'/myfdb-profile/template/myfdb_full.php';

		return $render;
	}
	
	function isCached($IDTagName = 0) {
		if ($IDTagName==0) $IDTagName = $this->IDTagName;

		$IDTagName = $this->getID($IDTagName);
			
		$MyFDB_profile = get_option(self::WP_Source.$IDTagName);
		$Cache_time = get_option('myfdb_cache_time');

		if ($MyFDB_profile!==false && $Cache_time!==false) {
			$Cache_time = $Cache_time*3600; // Convert to seconds
			
			if (isset($MyFDB_profile->UpdateTime)) {
				$Profile_cache_limit = strtotime($MyFDB_profile->UpdateTime) + $Cache_time;
				if ($Profile_cache_limit<0) return false;
					
				$Local_time = strtotime(date('Y-m-d H:i:s')); // In seconds

				if ($Profile_cache_limit-$Local_time>0) return true;
			}
		}
		return false;
	}

	/*function isCacheON() {
		if(get_option('myfdb_caching')==='Y') return true;
		return false;
	}*/

	function isSaved($IDTagName = 0){
		if ($IDTagName==0) $IDTagName = $this->IDTagName;
		
		if (get_option(self::WP_Source.$IDTagName)!==false) return true;
		else return false;
	}

	function getID($IDTagName = 0) {
		$cut_start = strpos($IDTagName, '-');
		if(!$cut_start===false) $IDTagName = substr_replace($IDTagName, '', $cut_start);
		if (!$IDTagName=='' && is_numeric($IDTagName)) return $IDTagName;
		else return 0;
	}
}
?>
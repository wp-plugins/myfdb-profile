<?php
class MyFDB_Search {
	/*
	* current_page: Current page # of results
    * total_entries: Total number of results for the given query
    * total_pages: Total number of pages of results
    * people: List of people
    * * id: Id of the record on MyFDB.com
    * * name: Name of the person
    * * occupation: Occupation of the person
    * * summary_url: Url to retrieve the summary via summary api
    */
	
	var $CurrentPage = 0;
	var $TotalEntries = 0; // 30
	var $TotalPages = 0;
	var $FromProfile = 0;
	var $ToProfile = 0;
	
	var $Profile = array();

	function __construct($keyword = '', $category = '', $Wp_page = 1) {
		if ($keyword!='' && $category!='') {
			ini_set('user_agent', 'styleite.com api');
			$MyFDB_page = round(($Wp_page/3)+0.2);
			
			$result = @file_get_contents('http://www.myfdb.com/'.$category.'/search.json?q='.urlencode($keyword).'&page='.$MyFDB_page);
			
			if($result !== false) {
				$range = $MyFDB_page-($Wp_page/3);
				if ($range>0.6) {
					$this->FromProfile = 0;
					$this->ToProfile = 10;
				} else if($range>0.3 && $range<0.6) {
					$this->FromProfile = 10;
					$this->ToProfile = 20;
				} else if($range<0.3) {
					$this->FromProfile = 20;
					$this->ToProfile = 30;
				}
				
				$result = json_decode($result);
				$this->CurrentPage = $result->current_page; //
				$this->TotalEntries = $result->total_entries; //
				$this->TotalPages = $result->total_pages; //
				$this->Profile = $result->people; //
			}
		}
	}
	
	function JSON_output($q = '', $timecheck = null) {
		$data = array();
		if (count($this->Profile)>0) {
			$count = 0;
			
			foreach ($this->Profile as $key => $profile) {
				if($count>=$this->FromProfile && $count<$this->ToProfile) {
					$data['results'][] = array('id'=>$profile->id, 'name'=>$profile->name);
				}
				$count++;
			}
			$data['total'] = $this->TotalEntries;
		} else {
			$data['results'][] = array('id'=>$q, 'name'=>"No profiles were found");
			$data['total'] = 1;
		}
		$data['timecheck'] = $timecheck;
		$data['q'] = $q;
		
		return json_encode($data);
	}
}
?>
<?php
namespace XoopsModules\Spotifyapi;

use XoopsModules\Spotifyapi;
use XoopsModules\Spotifyapi\Constants;

class db extends \XoopsPersistableObjectHandler
{
	public $db;
	public $configname;
	public $configvalue;
	public $helper;
	
	public $artist;
	public $artisturl;
	public $title;
	public $albumtitle;
	public $release_year;
	public $times;
	public $image;
	public $userplaylist;
	public $popularity;
	public $numtoshow;
	
	public $firstentry;
	public $lastentry;
	
	public $today;
	public $lastweek;
	public $thisweek_start;
	public $thisweek_end;
	public $lastweek_start;
	public $lastweek_end;
	public $selecttoplimit;
	
	public $chart_day_count;
	
	public $code;
	public $AccessToken;
	public $RefreshToken;
	
    /**
     * constructor
     *
     * @param mixed $id
     **/
    public function __construct(\XoopsDatabase $db = null, $helper = null)
    {
		if (null === $helper) {
            $helper = Helper::getInstance();
        }
		$this->helper = $helper;
		
		if (null == $this->chart_day_count) {
			$this->chart_day_count = $this->chartdaycounter($helper->getConfig('spotifyapidaytostartc'));
		}
		
		$this->today = date('d-m-Y', strtotime("last " . $this->chart_day_count . ""));
		
		$this->thisweek_start = date('d-m-Y', strtotime("last " . $this->chart_day_count . " - 1 week"));
		$this->thisweek_end = date("d-m-Y", strtotime("last " . $this->chart_day_count . ""));
		
		$this->lastweek_start = date('d-m-Y', strtotime($this->thisweek_start." -1 week"));
		$this->lastweek_end = $this->thisweek_start;
		
		$this->lastweek = date("d-m-Y", strtotime($this->today." -1 week"));
		$this->selecttoplimit = $helper->getConfig('spotifyapinumshowcharts');
		
		$this->numtoshow = $helper->getConfig('spotifyapinumbertoshow');
		
		
		if (null === $db) {
            $db = \XoopsDatabaseFactory::getDatabaseConnection();
        }
		
        $this->db = $db;
		
		$this->firstentry = $this->getMinMaxDate($type='min');
		$this->lastentry = $this->getMinMaxDate($type='max');
    }
	
	/**
	 * Insert config values to table
	 * @param string $name	The configname
	 * @param string $value	The configvalue
	 * @return bool $result
	 */
	public function loadSave($type='save')
	{
		if ($type == 'save') {
			$sql = 	'INSERT INTO ' . $this->db->prefix('spotifyapi_music') . 
					' (id, times, image, artist, title, album, releaseyear, artistlink, playlistlink, popularity) VALUES '.
					'(null,"' . addslashes($this->times) . '", "' . addslashes($this->image) . '", "'.addslashes($this->artist) . 
						'", "' . addslashes($this->title) . '", "' . addslashes($this->albumtitle) . '", "' . addslashes($this->release_year) . 
						'", "' . addslashes($this->artisturl) . '", "' . addslashes($this->userplaylist) . '", "' . addslashes($this->popularity) .
					'")';
		} 
		if ($type == 'update'){
		}
		if ($type == 'delete') {
		}
		
		if (!$result = $this->db->queryF($sql)) {
			return false;
		}
		//$numrows = $this->db->getRowsNum($result);
		return $result;
	}
	
	public function songexists()
	{
		$sql = "Select * From " . $this->db->prefix('spotifyapi_music') . " where artist = '" . addslashes($this->artist) . "' AND title = '" . addslashes($this->title) . "' AND times = '" . addslashes($this->times) . "'";
		$result = $this->db->queryF($sql);
		$numrows = $this->db->getRowsNum($result);
		if ($numrows > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function songdublicate()
	{
		$sql = "Select * From " . $this->db->prefix('spotifyapi_music') . " ORDER BY id DESC LIMIT 0, 1";
		$result = $this->db->queryF($sql);
		$numrows = $this->db->getRowsNum($result);
		while ($row = $this->db->fetchArray($result)) {
			$arr[] = $row;
        }
		if (addslashes($arr[0]['artist']) == addslashes($this->artist) AND addslashes($arr[0]['title']) == addslashes($this->title)) {
			return true;
		} else {
			return false;
		}
	}

	
	public function updateurls ()
	{
		$sql = "UPDATE " . $this->db->prefix('spotifyapi_music') . 
			" SET artistlink = '".addslashes($this->artisturl) . "', playlistlink = '" . addslashes($this->userplaylist) . "', popularity = '" . addslashes($this->popularity) .
			"' WHERE artist = '" . addslashes($this->artist) . "' AND title = '" . addslashes($this->title) . "' AND times = '" . addslashes($this->times) . "'";
		$result = $this->db->queryF($sql);
	}

	public function getSongs()
	{
		$sql = "Select * From " . $this->db->prefix('spotifyapi_music') . " order by STR_TO_DATE(times, '%d-%m-%Y %H:%i:%s') DESC limit 0,".$this->numtoshow ;
		$result = $this->db->queryF($sql);
		while ($row = $this->db->fetchArray($result)) {
			$arr[] = $row;
        }
		return $arr;
	}
	
	public function getLatestTimeStamp()
	{
		$sql = "Select times from ".$this->db->prefix('spotifyapi_music'). " order by times DESC limit 0,1";
		$result = $this->db->queryF($sql);
		while($row = $this->db->fetchArray($result)) {
			$arr[] = $row;
        }
		return $arr[0]['times'];
	}
	
	
	public function getConfig($value)
	{
		$t = "";
		switch ($value) {
			case 'refreshToken' :
				$type = 'refreshToken';
				break;
			case 'accessToken' :
				$type = 'accessToken';
				break;
			case 'code' :
				$type = 'code';
				break;
		}
		$sql = "Select ".$type." from ".$this->db->prefix('spotifyapi_config'). " order by id DESC limit 0,1";
		$result = $this->db->queryF($sql);
		while($row = $this->db->fetchArray($result)) {
			$arr[] = $row;
        }
		return $arr[0][$type];
	}
	
	
	public function setConfig($value)
	{
		$t = "";
		switch ($value) {
			case 'refreshToken' :
				$v = $this->refreshToken;
				$t = 'refreshToken';
				break;
			case 'accessToken' :
				$v = $this->accessToken;
				$t = 'accessToken';
				break;
			case 'code' :
				$v = $this->code;
				$t = 'code';
				break;
		}
		$r = $this->cExists($t);
		if ($r == false) {
			$sql = "INSERT INTO  ".$this->db->prefix('spotifyapi_config'). " (".$t.") values ('".$v."')";
		} else {
			$sql = "UPDATE ".$this->db->prefix('spotifyapi_config'). " SET ".$t." = '".$v."' where id = 1";
		}		
		$result = $this->db->queryF($sql);
		//$num = $this->db->getRowsNum($result);
		/*
		while($row = $this->db->fetchArray($result)) {
			$arr[] = $row;
        }
		return $arr[0][$t];
		*/
	}
	
	public function cExists($value)
	{
		$t = "";
		switch ($value) {
			case 'refreshToken' :
				$v = $this->refreshToken;
				$t = 'refreshToken';
				break;
			case 'accessToken' :
				$v = $this->accessToken;
				$t = 'accessToken';
				break;
			case 'code' :
				$v = $this->code;
				$t = 'code';
				break;
		}
		$sql = "Select ".$t." From " . $this->db->prefix('spotifyapi_config') . " where id = 1";
		$result = $this->db->queryF($sql);
		$numrows = $this->db->getRowsNum($result);
		if ($numrows > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * Function to get ranked array of top songs up until now
	 * @param string $this->today Date(now)
	 * @param string $this->selecttoplimit How many items to get
	 * @return array $arr
	*/
	public function getTop() {
		$sql  =  "select @rownum:= @rownum + 1 as pos,";
		$sql .=  "prequery.id, ";
		$sql .=  "prequery.times, ";
		$sql .=  "prequery.image, ";
		$sql .=  "prequery.artist, ";
		$sql .=  "prequery.title, ";
		$sql .=  "prequery.album, ";
		$sql .=  "prequery.releaseyear, ";
		$sql .=  "prequery.artistlink, ";
		$sql .=  "prequery.playlistlink, ";
		$sql .=  "prequery.popularity from(select @rownum := 0 ) sqlvars, ";
		$sql .=  "(SELECT count(*) postCount, ";
		$sql .=  "id, ";
		$sql .=  "times, ";
		$sql .=  "image, ";
		$sql .=  "artist, ";
		$sql .=  "title, ";
		$sql .=  "album, ";
		$sql .=  "releaseyear, ";
		$sql .=  "artistlink, ";
		$sql .=  "playlistlink, ";
		$sql .=  "popularity FROM ".$this->db->prefix('spotifyapi_music')." ";
		$sql .=  "WHERE STR_TO_DATE(times, '%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('".$this->today."', '%d-%m-%Y %H:%i:%s') ";
		$sql .=  "group by artist, title ";
		$sql .=  "order by count(*) desc LIMIT 0,".$this->selecttoplimit.") prequery";
		$result = $this->db->queryF($sql);
		
		while ($row = $this->db->fetchArray($result)) {
			$arr[] = $row;
        }
		
		return $arr;
	}
	
	/*
	 * Function to get ranked array of top songs a week before now
	 * @param string $this->lastweek Date(now - 1 week)
	 * @param string $this->selecttoplimit How many items to get
	 * @return array $arr
	*/
	public function getLwTop() {
		$sql  =  "select @rownum:= @rownum + 1 as pos,";
		$sql .=  "prequery.id, ";
		$sql .=  "prequery.times, ";
		$sql .=  "prequery.image, ";
		$sql .=  "prequery.artist, ";
		$sql .=  "prequery.title, ";
		$sql .=  "prequery.album, ";
		$sql .=  "prequery.releaseyear, ";
		$sql .=  "prequery.artistlink, ";
		$sql .=  "prequery.playlistlink, ";
		$sql .=  "prequery.popularity from(select @rownum := 0 ) sqlvars, ";
		$sql .=  "(SELECT count(*) postCount, ";
		$sql .=  "id, ";
		$sql .=  "times, ";
		$sql .=  "image, ";
		$sql .=  "artist, ";
		$sql .=  "title, ";
		$sql .=  "album, ";
		$sql .=  "releaseyear, ";
		$sql .=  "artistlink, ";
		$sql .=  "playlistlink, ";
		$sql .=  "popularity FROM ".$this->db->prefix('spotifyapi_music')." ";
		$sql .=  "WHERE STR_TO_DATE(times, '%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('".$this->lastweek."', '%d-%m-%Y %H:%i:%s') ";
		$sql .=  "group by artist, title ";
		$sql .=  "order by count(*) desc LIMIT 0,".$this->selecttoplimit.") prequery";
		$result = $this->db->queryF($sql);
		while ($row = $this->db->fetchArray($result)) {
			$arr[] = $row;
        }
		return $arr;
	}
	
		/*
	 * Function to get ranked array of top songs up until now
	 * @param string $this->today Date(now)
	 * @param string $this->selecttoplimit How many items to get
	 * @return array $arr
	*/
	public function getTopSingleWeek() {
		$arr = array();
		$sql  =  "select @rownum:= @rownum + 1 as pos,";
		$sql .=  "prequery.id, ";
		$sql .=  "prequery.times, ";
		$sql .=  "prequery.image, ";
		$sql .=  "prequery.artist, ";
		$sql .=  "prequery.title, ";
		$sql .=  "prequery.album, ";
		$sql .=  "prequery.releaseyear, ";
		$sql .=  "prequery.artistlink, ";
		$sql .=  "prequery.playlistlink, ";
		$sql .=  "prequery.popularity from(select @rownum := 0 ) sqlvars, ";
		$sql .=  "(SELECT count(*) postCount, ";
		$sql .=  "id, ";
		$sql .=  "times, ";
		$sql .=  "image, ";
		$sql .=  "artist, ";
		$sql .=  "title, ";
		$sql .=  "album, ";
		$sql .=  "releaseyear, ";
		$sql .=  "artistlink, ";
		$sql .=  "playlistlink, ";
		$sql .=  "popularity FROM ".$this->db->prefix('spotifyapi_music')." ";
		$sql .=  "WHERE STR_TO_DATE(times, '%d-%m-%Y') BETWEEN STR_TO_DATE('".$this->thisweek_start."', '%d-%m-%Y') ";
		$sql .=  "AND STR_TO_DATE('".$this->thisweek_end."', '%d-%m-%Y') ";
		$sql .=  "group by artist, title ";
		$sql .=  "order by count(*) desc LIMIT 0,".$this->selecttoplimit.") prequery";
		$result = $this->db->queryF($sql);

		while ($row = $this->db->fetchArray($result)) {
			$arr[] = $row;
        }
		
		return $arr;
	}
	
	/*
	 * Function to get ranked array of top songs a week before now
	 * @param string $this->lastweek Date(now - 1 week)
	 * @param string $this->selecttoplimit How many items to get
	 * @return array $arr
	*/
	public function getLwTopSingleWeek() {
		$arr = array();
		$sql  =  "select @rownum:= @rownum + 1 as pos,";
		$sql .=  "prequery.id, ";
		$sql .=  "prequery.times, ";
		$sql .=  "prequery.image, ";
		$sql .=  "prequery.artist, ";
		$sql .=  "prequery.title, ";
		$sql .=  "prequery.album, ";
		$sql .=  "prequery.releaseyear, ";
		$sql .=  "prequery.artistlink, ";
		$sql .=  "prequery.playlistlink, ";
		$sql .=  "prequery.popularity from(select @rownum := 0 ) sqlvars, ";
		$sql .=  "(SELECT count(*) postCount, ";
		$sql .=  "id, ";
		$sql .=  "times, ";
		$sql .=  "image, ";
		$sql .=  "artist, ";
		$sql .=  "title, ";
		$sql .=  "album, ";
		$sql .=  "releaseyear, ";
		$sql .=  "artistlink, ";
		$sql .=  "playlistlink, ";
		$sql .=  "popularity FROM ".$this->db->prefix('spotifyapi_music')." ";
		$sql .=  "WHERE STR_TO_DATE(times, '%d-%m-%Y') BETWEEN STR_TO_DATE('".$this->lastweek_start."', '%d-%m-%Y') ";
		$sql .=  "AND STR_TO_DATE('".$this->lastweek_end."', '%d-%m-%Y') ";
		$sql .=  "group by artist, title ";
		$sql .=  "order by count(*) desc LIMIT 0,".$this->selecttoplimit.") prequery";
		//$sql .=  "order by count(*) desc LIMIT 0,1000) prequery";
		$result = $this->db->queryF($sql);
		while ($row = $this->db->fetchArray($result)) {
			$arr[] = $row;
        }
		return $arr;
	}
	
	/* calculates gain of 2 numbers
	 * @param int tw current number
	 * @param int lw last week number
	 * @return int diff
	 *
	 */
	 function gain($tw, $lw) {
		$diff = ( (int) $lw - (int) $tw );
		return $diff;
	 }
	
	/* Method to merge the this week, last week into one 
	 * @param array tw // This week array
	 * @param array lw // Last week array
	 * @return array val // Merged and manipulated chart array
	 */
	 function parseArrayDouble($tw, $lw) {
		$chart = [];
		$i = 0;
		$greatestgainer = 0;
		$greatestgainerid = 0;
		foreach($tw as $tv) {
			$chart[$i]['lw'] = _SPOTIFYAPI_NEWCHARTENTRY;
			foreach ($lw as $yv) {
				$chart[$i]['tw'] = (int) $tv['pos'];
						
				if ($tv['artist'] == $yv['artist'] AND $tv['title'] == $yv['title']){
					$chart[$i]['lw'] = (is_numeric($yv['pos'])) ? (int) $yv['pos'] : ""; 
				} 
				//$chart[$i]['avg'] = $this->gain($chart[$i]['tw'], $chart[$i]['lw']);
				if ($this->gain($chart[$i]['tw'], $chart[$i]['lw']) > $greatestgainer) {
					$greatestgainer = (int) $this->gain($chart[$i]['tw'], $chart[$i]['lw']);
					$greatestgainerid = $i;
				}
				if ($chart[$i]['tw'] > $chart[$i]['lw']) {
					$chart[$i]['dir'] = "&#8595;";
				}
				if ($chart[$i]['tw'] < $chart[$i]['lw']) {
					$chart[$i]['dir'] = "&#8593;";
				}
				if ($chart[$i]['tw'] == $chart[$i]['lw']) {
					$chart[$i]['dir'] = "&#183;";
				}
				$chart[$i]['artist'] = $tv['artist']; 
				$chart[$i]['title'] = $tv['title']; 
				$chart[$i]['image'] = $tv['image']; 
				$chart[$i]['album'] = $tv['album']; 
				$chart[$i]['year'] = (int) $tv['releaseyear']; 
				$chart[$i]['artlink'] = $tv['artistlink'];
				$chart[$i]['pop'] = (int) $tv['popularity']; 
				$chart[$i]['ggn'] = (int) $this->gain($chart[$i]['tw'], $chart[$i]['lw']);
				if ($chart[$i]['ggn'] > $greatestgainer) {
					$greatestgainer = $chart[$i]['ggn'];
					$greatestgainerid = $i;
				}
				
			}
			$chart[$i]['gg'] = false;
			$i += 1;
		}
		$chart[$greatestgainerid]['gg'] = ($chart[$greatestgainerid]['ggn'] > 0) ? true : false;
		return $chart;
	 }
	 
	/* Method to merge the this week, last week into one 
	 * @param array tw // This week array
	 * @return array val // Merged and manipulated chart array
	 */
	 function parseArraySingle($tw) {
		 $chart = [];
		 $i = 0;
		 foreach($tw as $tv) {
			$chart[$i]['tw'] = (int) $tv['pos'];
			$chart[$i]['artist'] = $tv['artist']; 
			$chart[$i]['title'] = $tv['title']; 
			$chart[$i]['image'] = $tv['image']; 
			$chart[$i]['album'] = $tv['album']; 
			$chart[$i]['year'] = (int) $tv['releaseyear']; 
			$chart[$i]['artlink'] = $tv['artistlink'];
			$chart[$i]['pop'] = (int) $tv['popularity']; 		
			$i += 1;
		}
		return $chart;
	 }
	
	/*
	 * Function to get distinct dates from mysql and
	 * return them as date of previous saturday
	 * @return array $res
	*/
	public function getDistinctStartDates()
	{
		$res = array();
		$sql = "SELECT DISTINCT str_to_date(times, '%d-%m-%Y') as dato FROM ".$this->db->prefix('spotifyapi_music')." ORDER BY dato ASC ";
		$result = $this->db->queryF($sql);
		while ($row = $this->db->fetchArray($result)) {
			$res[] = $row['dato'];
		}
		
		return $res;
	}

	/*
	 * Function to get distinct Years from mysql and
	 * @return array $res
	*/
	public function getDistinctYears()
	{
		$res = array();
		$sql = "SELECT DISTINCT YEAR(str_to_date(times,'%d-%m-%Y')) as dato FROM ".$this->db->prefix('spotifyapi_music')." ORDER BY dato ASC ";
		$result = $this->db->queryF($sql);
		while ($row = $this->db->fetchArray($result)) {
			$res[] = $row['dato'];
		}
		return $res;
	}
	
	public function getWeeks($year)
	{
		$res = array();
		$sql = "SELECT DISTINCT WEEK(str_to_date(times, '%d-%m-%Y')) as weeks FROM ".$this->db->prefix('spotifyapi_music')." WHERE YEAR(str_to_date(times, '%d-%m-%Y %H:%i:%s')) = '" . $year . "' ORDER BY weeks ASC";
		$result = $this->db->queryF($sql);
		while ($row = $this->db->fetchArray($result)) {
			$res[] = $row['weeks'];
		}
		return $res;
	}
	
	/*
	 * Function to get distinct reduced dates from mysql where
	 * start date is bigger than $ startand
	 * return them as date
	 * @return array $res
	*/
	public function getDistinctReducedStartDates($start)
	{
		$res = array();
		$sql = "SELECT DISTINCT str_to_date(times, '%d-%m-%Y') as dato FROM ".$this->db->prefix('spotifyapi_music')." WHERE str_to_date(times, '%d-%m-%Y') > str_to_date('" . $start . "', '%d-%m-%Y') ORDER BY dato ASC";
		$result = $this->db->queryF($sql);
		while ($row = $this->db->fetchArray($result)) {
			$res[] = $row['dato'];
		}
		return $res;
	}
	
	/*
	 * Function to get min / max date stanmp from mysql
	 * @param type min or max date
	 * @return array $res
	*/
	public function getMinMaxDate($type)
	{
		$srt = ($type == 'min') ? 'ASC' : 'DESC';
		$res = array();
		$sql = "SELECT times FROM ".$this->db->prefix('spotifyapi_music')." ORDER BY id " . $srt . " LIMIT 1";
		$result = $this->db->queryF($sql);
		while ($row = $this->db->fetchArray($result)) {
			$res[] = $row['times'];
		}
		$val = date_create_from_format("d-m-Y H:i:s", $res[0])->format("d-m-Y");
		return $val;
	}
	
	/*
	 * Function to parse distinct dates from mysql and
	 * return them as date of previous / previous saturday - 7 days 
	 * @param array $array mysql dates
	 * @param string $arg start or end
	 * @return array $d reindexed
	*/
	public function parseDistinctDates($array, $arg='start')
	{
		$d = array();
		$dato = array();
		if ($arg == 'start') {
		}
		$s = 0;
		foreach ($array as $arr) {
			if ($arg == 'start') {
				$d[] = date('d-m-Y', strtotime("last " . $this->chart_day_count . " - 1 week", strtotime($arr)));
			} if ($arg == 'year') {
				$d[] = $arr;
			} else {
				$d[] = date('d-m-Y', strtotime("last " . $this->chart_day_count . "", strtotime($arr)));
			}
		}
		$d = array_unique($d);
		return array_values($d);
	}

	
	public function chartdaycounter($num) {
		switch ($num) {
			case 1:
				return 'Monday';
				break;
			case 2:
				return 'Tuesday';
				break;
			case 3:
				return 'Wednesday';
				break;
			case 4:
				return 'Thursday';
				break;
			case 5:
				return 'Friday';
				break;
			case 6:
				return 'Saturday';
				break;
			case 7:
				return 'Sunday';
				break;
			default:
				return 'Saturday';
		}
	}
	
	/*
	 *
	 *
	 *
	 */
	function getStartAndEndDate($week,$year)
	{
		$dateTime = new \DateTime();
		$dateTime->setISODate($year,$week);
		$result['start_date'] = $dateTime->format('d-m-Y H:i:s');
		$dateTime->modify('+6 days');
		$result['end_date'] = $dateTime->format('d-m-Y H:i:s');
		return $result;
	}

}

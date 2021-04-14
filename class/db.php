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
	
	public $today;
	public $lastweek;
	public $selecttoplimit;
	
    /**
     * constructor
     *
     * @param mixed $id
     **/
    public function __construct(\XoopsDatabase $db = null, $helper = null)
    {
		$this->today = date('d-m-Y 00:00:00');
		$this->lastweek = date("d-m-Y 00:00:00", strtotime("-1 week"));
		$this->selecttoplimit = 10;
		
		if (null === $helper) {
            $helper = Helper::getInstance();
			$this->numtoshow = $helper->getConfig('spotifyapinumbertoshow');
        }
		$this->helper = $helper;
		if (null === $db) {
            $db = \XoopsDatabaseFactory::getDatabaseConnection();
        }
        $this->db = $db;
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
			// INSERT INTO `xoops_spotifyapi_music` (`id`, `artist`, `title`, `album`, `releaseyear`) VALUES (NULL, 'artist', 'titel', 'new album', '2020');
			$sql = 	'INSERT INTO ' . $this->db->prefix('spotifyapi_music') . 
					' (id, times, image, artist, title, album, releaseyear, artistlink, playlistlink, popularity) VALUES '.
					'(null,"' . addslashes($this->times) . '", "' . addslashes($this->image) . '", "'.addslashes($this->artist) . 
						'", "' . addslashes($this->title) . '", "' . addslashes($this->albumtitle) . '", "' . addslashes($this->release_year) . 
						'", "' . addslashes($this->artisturl) . '", "' . addslashes($this->userplaylist) . '", "' . addslashes($this->popularity) .
					'")';
		} 
		if ($type == 'update'){
			//$sql = 'UPDATE ' . $this->db->prefix('lasius_config') . ' SET configname="'.$name.'", configvalue = "'.$value.'" WHERE configname="'.$name.'"';
		}
		if ($type == 'delete') {
			//$sql = 'DELETE FROM ' . $this->db->prefix('lasius_config') . ' WHERE configname="'.$name.'"';
		}
		
		if (!$result = $this->db->queryF($sql)) {
			return false;
		}
		$numrows = $this->db->getRowsNum($result);
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

	
	public function updateurls ()
	{
		//mysql_query("UPDATE blogEntry SET content = '$udcontent', title = '$udtitle' WHERE id = '$id'")
		$sql = "UPDATE " . $this->db->prefix('spotifyapi_music') . 
			" SET artistlink = '".addslashes($this->artisturl) . "', playlistlink = '" . addslashes($this->userplaylist) . "', popularity = '" . addslashes($this->popularity) .
			"' WHERE artist = '" . addslashes($this->artist) . "' AND title = '" . addslashes($this->title) . "' AND times = '" . addslashes($this->times) . "'";
		$result = $this->db->queryF($sql);
	}

	public function getSongs()
	{
		$sql = "Select * From " . $this->db->prefix('spotifyapi_music') . " order by STR_TO_DATE(times, '%d-%m-%Y %H:%i:%s') DESC limit 0,".$this->numtoshow ;
		//$sql = "Select * From " . $this->db->prefix('spotifyapi_music') . " order by times DESC";
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
		//$sql .=  "AND YEAR(STR_TO_DATE(times, '%d-%m-%Y %H:%i:%s')) = '".$this->selectyear."' ";
		$sql .=  "group by artist, title ";
		$sql .=  "order by count(*) desc LIMIT 0,".$this->selecttoplimit.") prequery";
		$result = $this->db->queryF($sql);
		//echo $sql;
		
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
		//$sql .=  "AND STR_TO_DATE(times, '%d-%m-%Y %H:%i:%s') <= '".$this->today."' ";
		$sql .=  "group by artist, title ";
		$sql .=  "order by count(*) desc LIMIT 0,".$this->selecttoplimit.") prequery";
		$result = $this->db->queryF($sql);
		//echo "<br><br>".$sql;
		while ($row = $this->db->fetchArray($result)) {
			$arr[] = $row;
        }
		return $arr;
	}
	

}

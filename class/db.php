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
	public $title;
	public $albumtitle;
	public $release_year;
	public $times;
	public $image;
	public $numtoshow;
	
    /**
     * constructor
     *
     * @param mixed $id
     **/
    public function __construct(\XoopsDatabase $db = null, $helper = null)
    {
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
					' (id, times, image, artist, title, album, releaseyear) VALUES '.
					'(null,"'.addslashes($this->times).'", "'.addslashes($this->image).'", "'.addslashes($this->artist).'", "'.addslashes($this->title).'", "'.addslashes($this->albumtitle).'", "'.addslashes($this->release_year).'")';
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
	
	public function getSongs()
	{
		$sql = "Select * From " . $this->db->prefix('spotifyapi_music') . " order by times DESC limit 0,".$this->numtoshow ;
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
	

}

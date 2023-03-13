<?php

/**
 * Spotify Api module for xoops
 *
 * @package    spotifyapi
 * @subpackage page-level
 * @author     djculex <culex@culex.dk>
 * @copyright  2023 Michael Albertsen (www.culex.dk)
 * @since      1.0
 * @min_xoops  2.5.9
 */

namespace XoopsModules\Spotifyapi;

use XoopsDatabase;
use XoopsModules\Spotifyapi\Constants;

/**
 *
 */
class Spotifyapi_db extends \XoopsPersistableObjectHandler
{
    /**
     * @var XoopsDatabase|null
     */
    public $db;

    /**
     * @var string $configname
     */
    public string $configname;

    /**
     * @var string|int $configvalue
     */
    public string|int $configvalue;

    /**
     * @var mixed|Helper|null $helper
     */
    public mixed $helper;

    /**
     * @var string $artist
     */
    public string $artist;

    /**
     * @var string $artisturl
     */
    public string $artisturl;

    /**
     * @var string $title
     */
    public string $title;

    /**
     * @var string $albumtitle
     */
    public string $albumtitle;

    /**
     * @var int|string $release_year
     */
    public $release_year;

    /**
     * @var int|string $times
     */
    public $times;

    /**
     * @var string $image
     */
    public string $image;

    /**
     * @var int|string
     */
    public string|int $userplaylist;

    /**
     * @var int|string $popularity
     */
    public string|int $popularity;

    /**
     * @var array|mixed $numtoshow
     */
    public mixed $numtoshow;

    /**
     * @var string $firstentry
     */
    public string $firstentry;

    /**
     * @var string $lastentry
     */
    public string $lastentry;

    /**
     * @var string $today
     */
    public string $today;

    /**
     * @var string $lastweek
     */
    public string $lastweek;

    /**
     * @var string $thisweek_start
     */
    public string $thisweek_start;

    /**
     * @var string $thisweek_end
     */
    public string $thisweek_end;

    /**
     * @var string $lastweek_start
     */
    public string $lastweek_start;

    /**
     * @var string $lastweek_end
     */
    public string $lastweek_end;

    /**
     * @var array|mixed
     */
    public mixed $selecttoplimit;

    /**
     * @var string
     */
    public string $chart_day_count;

    /**
     * @var
     */
    public $code;

    /**
     * @var
     */
    public $RefreshToken;
    private $refreshToken;
    private $accessToken;


    /**
     * constructor
     *
     * @param XoopsDatabase|null $db
     * @param null $helper
     */
    public function __construct(XoopsDatabase $db = null, $helper = null)
    {
        if (null === $helper) {
            $helper = Spotifyapi_Helper::getInstance();
        }

        $this->helper = $helper;

        $this->chart_day_count = $this->chartdaycounter($helper->getConfig('spotifyapidaytostartc'));

        $this->today = date('d-m-Y', strtotime('last ' . $this->chart_day_count));

        $this->thisweek_start = date('d-m-Y', strtotime('last ' . $this->chart_day_count . ' - 1 week'));
        $this->thisweek_end = date('d-m-Y', strtotime('last ' . $this->chart_day_count));

        $this->lastweek_start = date('d-m-Y', strtotime($this->thisweek_start . ' -1 week'));
        $this->lastweek_end = $this->thisweek_start;

        $this->lastweek = date('d-m-Y', strtotime($this->today . ' -1 week'));
        $this->selecttoplimit = $helper->getConfig('spotifyapinumshowcharts');

        $this->numtoshow = $helper->getConfig('spotifyapinumbertoshow');

        if (null === $db) {
            $db = \XoopsDatabaseFactory::getDatabaseConnection();
        }

        $this->db = $db;

        $this->firstentry = $this->getMinMaxDate($type = 'min');
        $this->lastentry = $this->getMinMaxDate($type = 'max');
    }

    /**
     * Return int weekday to weekday name
     *
     * @param int $num
     * @return string
     */
    public function chartdaycounter($num): string
    {
        return match ($num) {
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            7 => 'Sunday',
            default => 'Saturday',
        };
    }

    /**
     * Get configs from database
     *
     * @param string $value
     * @return array|string
     */
    public function getConfig($value): array|string
    {
        $arr = [];
        $t = '';
        switch ($value) {
            case 'refreshToken':
                $type = 'refreshToken';
                break;

            case 'accessToken':
                $type = 'accessToken';
                break;

            case 'code':
                $type = 'code';
                break;
        }

        $sql = 'Select ' . $type . ' from ' . $this->db->prefix('spotifyapi_config') . ' order by id DESC limit 0,1';
        $result = $this->db->queryF($sql);
        while ($row = $this->db->fetchArray($result)) {
            $arr[] = $row;
        }
        return $arr[0][$type];
    }

    /**
     * Function to get min / max date stanmp from mysql
     *
     * @param string $type
     * @return string $val
     */
    public function getMinMaxDate($type): string
    {
        $srt = ($type == 'min') ? 'ASC' : 'DESC';
        $res = [];
        $sql = 'SELECT times FROM ' . $this->db->prefix('spotifyapi_music') . ' ORDER BY id ' . $srt . ' LIMIT 1';
        $result = $this->db->queryF($sql);
        while ($row = $this->db->fetchArray($result)) {
            $res[] = $row['times'];
        }

        return date_create_from_format('d-m-Y H:i:s', $res[0])->format('d-m-Y');
    }

    /**
     * Insert config values to table
     *
     * @param string $type
     * @return bool|array $result
     */
    public function loadSave($type = 'save'): bool|array
    {
        if ($type == 'save') {
            $sql = 'INSERT INTO ' .
                $this->db->prefix('spotifyapi_music') .
                ' (id, times, image, artist, title, album, releaseyear, ' .
                'artistlink, playlistlink, popularity) VALUES ' .
                '(null,"' . addslashes($this->times) . '", "' .
                addslashes($this->image) . '", "' . addslashes($this->artist) .
                '", "' . addslashes($this->title) . '", "' .
                addslashes($this->albumtitle) . '", "' .
                addslashes($this->release_year) . '", "' .
                addslashes($this->artisturl) . '", "' .
                addslashes($this->userplaylist) . '", "' .
                addslashes($this->popularity) . '")';
        }
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }
        return $result;
    }

    /**
     * Check if song exists in database
     *
     * @return bool
     */
    public function songexists(): bool
    {
        $sql = 'Select * From ' .
            $this->db->prefix('spotifyapi_music') .
            " where artist = '" . addslashes($this->artist) .
            "' AND title = '" . addslashes($this->title) .
            "' AND times = '" . addslashes($this->times) . "'";
        $result = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        if ($numrows > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if last entered song is the same as new song
     *
     * @return bool
     */
    public function songdublicate(): bool
    {
        $sql = 'Select * From ' .
            $this->db->prefix('spotifyapi_music') .
            ' ORDER BY id DESC LIMIT 0, 1';
        $result = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        while ($row = $this->db->fetchArray($result)) {
            $arr[] = $row;
        }

        if (
            addslashes($arr[0]['artist']) == addslashes($this->artist) and
            addslashes($arr[0]['title']) == addslashes($this->title)
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update urls in database
     *
     * @return void
     */
    public function updateurls(): void
    {
        $sql = 'UPDATE ' . $this->db->prefix('spotifyapi_music') . " SET artistlink = '" . addslashes($this->artisturl) . "', playlistlink = '" . addslashes($this->userplaylist) . "', popularity = '" . addslashes($this->popularity) . "' WHERE artist = '" . addslashes($this->artist) . "' AND title = '" . addslashes($this->title) . "' AND times = '" . addslashes($this->times) . "'";
        $result = $this->db->queryF($sql);
    }

    /**
     * Get all songs
     *
     * @return array
     */
    public function getSongs(): array
    {
        $arr = [];
        $sql = 'Select * From ' .
            $this->db->prefix('spotifyapi_music') .
            " order by STR_TO_DATE(times, '%d-%m-%Y %H:%i:%s') DESC limit 0," .
            $this->numtoshow;
        $result = $this->db->queryF($sql);
        while ($row = $this->db->fetchArray($result)) {
            $arr[] = $row;
        }
        return $arr;
    }

    /**
     * Get the latest timestamp from database.
     *
     * @return string
     */
    public function getLatestTimeStamp(): string
    {
        $arr = [];
        $sql = 'Select times from ' .
            $this->db->prefix('spotifyapi_music') .
            ' order by times DESC limit 0,1';
        $result = $this->db->queryF($sql);
        while ($row = $this->db->fetchArray($result)) {
            $arr[] = $row;
        }
        return $arr[0]['times'];
    }

    /**
     * Set config in database
     *
     * @param string $value
     * @return void
     */
    public function setConfig($value): void
    {
        $t = '';
        switch ($value) {
            case 'refreshToken':
                $v = $this->refreshToken;
                $t = 'refreshToken';
                break;

            case 'accessToken':
                $v = $this->accessToken;
                $t = 'accessToken';
                break;

            case 'code':
                $v = $this->code;
                $t = 'code';
                break;
        }

        $r = $this->cExists($t);
        if (!$r) {
            $sql = 'INSERT INTO  ' .
                $this->db->prefix('spotifyapi_config') .
                ' (' . $t . ") values ('" . $v . "')";
        } else {
            $sql = 'UPDATE ' .
                $this->db->prefix('spotifyapi_config') .
                ' SET ' . $t . " = '" . $v . "' where id = 1";
        }
    }

    /**
     * Does a config value already exist in database
     *
     * @param string $value
     * @return bool
     */
    public function cExists($value): bool
    {
        $t = '';
        switch ($value) {
            case 'refreshToken':
                $v = $this->refreshToken;
                $t = 'refreshToken';
                break;

            case 'accessToken':
                $v = $this->accessToken;
                $t = 'accessToken';
                break;

            case 'code':
                $v = $this->code;
                $t = 'code';
                break;
        }

        $sql = 'Select ' . $t . ' From ' . $this->db->prefix('spotifyapi_config') . ' where id = 1';
        $result = $this->db->queryF($sql);
        $numrows = $this->db->getRowsNum($result);
        if ($numrows > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get ranking from database from beginning until yesterday
     *
     * @return array $arr
     */
    public function getTop(): array
    {
        $arr = [];
        $sql = 'select @rownum:= @rownum + 1 as pos,';
        $sql .= 'prequery.id, ';
        $sql .= 'prequery.times, ';
        $sql .= 'prequery.image, ';
        $sql .= 'prequery.artist, ';
        $sql .= 'prequery.title, ';
        $sql .= 'prequery.album, ';
        $sql .= 'prequery.releaseyear, ';
        $sql .= 'prequery.artistlink, ';
        $sql .= 'prequery.playlistlink, ';
        $sql .= 'prequery.popularity from(select @rownum := 0 ) sqlvars, ';
        $sql .= '(SELECT count(*) postCount, ';
        $sql .= 'id, ';
        $sql .= 'times, ';
        $sql .= 'image, ';
        $sql .= 'artist, ';
        $sql .= 'title, ';
        $sql .= 'album, ';
        $sql .= 'releaseyear, ';
        $sql .= 'artistlink, ';
        $sql .= 'playlistlink, ';
        $sql .= 'popularity FROM ' . $this->db->prefix('spotifyapi_music') . ' ';
        $sql .= "WHERE STR_TO_DATE(times, '%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('" . $this->today . "', '%d-%m-%Y %H:%i:%s') ";
        $sql .= 'group by artist, title ';
        $sql .= 'order by count(*) desc LIMIT 0,' . $this->selecttoplimit . ') prequery';
        $result = $this->db->queryF($sql);

        while ($row = $this->db->fetchArray($result)) {
            $arr[] = $row;
        }
        return $arr;
    }

    /**
     * Get last weeks ranking
     *
     * @return array $arr
     */
    public function getLwTop(): array
    {
        $arr = [];
        $sql = 'select @rownum:= @rownum + 1 as pos,';
        $sql .= 'prequery.id, ';
        $sql .= 'prequery.times, ';
        $sql .= 'prequery.image, ';
        $sql .= 'prequery.artist, ';
        $sql .= 'prequery.title, ';
        $sql .= 'prequery.album, ';
        $sql .= 'prequery.releaseyear, ';
        $sql .= 'prequery.artistlink, ';
        $sql .= 'prequery.playlistlink, ';
        $sql .= 'prequery.popularity from(select @rownum := 0 ) sqlvars, ';
        $sql .= '(SELECT count(*) postCount, ';
        $sql .= 'id, ';
        $sql .= 'times, ';
        $sql .= 'image, ';
        $sql .= 'artist, ';
        $sql .= 'title, ';
        $sql .= 'album, ';
        $sql .= 'releaseyear, ';
        $sql .= 'artistlink, ';
        $sql .= 'playlistlink, ';
        $sql .= 'popularity FROM ' . $this->db->prefix('spotifyapi_music') . ' ';
        $sql .= "WHERE STR_TO_DATE(times, '%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('" . $this->lastweek . "', '%d-%m-%Y %H:%i:%s') ";
        $sql .= 'group by artist, title ';
        $sql .= 'order by count(*) desc LIMIT 0,' . $this->selecttoplimit . ') prequery';
        $result = $this->db->queryF($sql);
        while ($row = $this->db->fetchArray($result)) {
            $arr[] = $row;
        }
        return $arr;
    }

    /**
     * Get ranking this week
     *
     * @return array $arr
     */
    public function getTopSingleWeek(): array
    {
        $arr = [];
        $sql = 'select @rownum:= @rownum + 1 as pos,';
        $sql .= 'prequery.id, ';
        $sql .= 'prequery.times, ';
        $sql .= 'prequery.image, ';
        $sql .= 'prequery.artist, ';
        $sql .= 'prequery.title, ';
        $sql .= 'prequery.album, ';
        $sql .= 'prequery.releaseyear, ';
        $sql .= 'prequery.artistlink, ';
        $sql .= 'prequery.playlistlink, ';
        $sql .= 'prequery.popularity from(select @rownum := 0 ) sqlvars, ';
        $sql .= '(SELECT count(*) postCount, ';
        $sql .= 'id, ';
        $sql .= 'times, ';
        $sql .= 'image, ';
        $sql .= 'artist, ';
        $sql .= 'title, ';
        $sql .= 'album, ';
        $sql .= 'releaseyear, ';
        $sql .= 'artistlink, ';
        $sql .= 'playlistlink, ';
        $sql .= 'popularity FROM ' . $this->db->prefix('spotifyapi_music') . ' ';
        $sql .= "WHERE STR_TO_DATE(times, '%d-%m-%Y') BETWEEN STR_TO_DATE('" .
            $this->thisweek_start . "', '%d-%m-%Y') ";
        $sql .= "AND STR_TO_DATE('" . $this->thisweek_end . "', '%d-%m-%Y') ";
        $sql .= 'group by artist, title ';
        $sql .= 'order by count(*) desc LIMIT 0,' . $this->selecttoplimit . ') prequery';
        $result = $this->db->queryF($sql);

        while ($row = $this->db->fetchArray($result)) {
            $arr[] = $row;
        }

        return $arr;
    }

    /**
     * Get ranking of last week
     *
     * @return array $arr
     */
    public function getLwTopSingleWeek(): array
    {
        $arr = [];
        $sql = 'select @rownum:= @rownum + 1 as pos,';
        $sql .= 'prequery.id, ';
        $sql .= 'prequery.times, ';
        $sql .= 'prequery.image, ';
        $sql .= 'prequery.artist, ';
        $sql .= 'prequery.title, ';
        $sql .= 'prequery.album, ';
        $sql .= 'prequery.releaseyear, ';
        $sql .= 'prequery.artistlink, ';
        $sql .= 'prequery.playlistlink, ';
        $sql .= 'prequery.popularity from(select @rownum := 0 ) sqlvars, ';
        $sql .= '(SELECT count(*) postCount, ';
        $sql .= 'id, ';
        $sql .= 'times, ';
        $sql .= 'image, ';
        $sql .= 'artist, ';
        $sql .= 'title, ';
        $sql .= 'album, ';
        $sql .= 'releaseyear, ';
        $sql .= 'artistlink, ';
        $sql .= 'playlistlink, ';
        $sql .= 'popularity FROM ' . $this->db->prefix('spotifyapi_music') . ' ';
        $sql .= "WHERE STR_TO_DATE(times, '%d-%m-%Y') BETWEEN STR_TO_DATE('" . $this->lastweek_start . "', '%d-%m-%Y') ";
        $sql .= "AND STR_TO_DATE('" . $this->lastweek_end . "', '%d-%m-%Y') ";
        $sql .= 'group by artist, title ';
        $sql .= 'order by count(*) desc LIMIT 0,' . $this->selecttoplimit . ') prequery';
        // $sql .=  "order by count(*) desc LIMIT 0,1000) prequery";
        $result = $this->db->queryF($sql);
        while ($row = $this->db->fetchArray($result)) {
            $arr[] = $row;
        }
        return $arr;
    }

    /**
     * Method to merge the this week, last week into one
     *
     * @param array $tw
     * @param array $lw
     * @return array
     */
    public function parseArrayDouble($tw, $lw): array
    {
        $chart = [];
        $i = 0;
        $greatestgainer = 0;
        $greatestgainerid = 0;
        foreach ($tw as $tv) {
            $chart[$i]['lw'] = _SPOTIFYAPI_NEWCHARTENTRY;
            foreach ($lw as $yv) {
                $chart[$i]['tw'] = (int)$tv['pos'];

                if ($tv['artist'] == $yv['artist'] and $tv['title'] == $yv['title']) {
                    $chart[$i]['lw'] = (is_numeric($yv['pos'])) ? (int)$yv['pos'] : '';
                }

                // $chart[$i]['avg'] = $this->gain($chart[$i]['tw'], $chart[$i]['lw']);
                if ($this->gain($chart[$i]['tw'], $chart[$i]['lw']) > $greatestgainer) {
                    $greatestgainer = $this->gain($chart[$i]['tw'], $chart[$i]['lw']);
                    $greatestgainerid = $i;
                }

                if ($chart[$i]['tw'] > $chart[$i]['lw']) {
                    $chart[$i]['dir'] = '&#8595;';
                }

                if ($chart[$i]['tw'] < $chart[$i]['lw']) {
                    $chart[$i]['dir'] = '&#8593;';
                }

                if ($chart[$i]['tw'] == $chart[$i]['lw']) {
                    $chart[$i]['dir'] = '&#183;';
                }

                $chart[$i]['artist'] = $tv['artist'];
                $chart[$i]['title'] = $tv['title'];
                $chart[$i]['image'] = $tv['image'];
                $chart[$i]['album'] = $tv['album'];
                $chart[$i]['year'] = (int)$tv['releaseyear'];
                $chart[$i]['artlink'] = $tv['artistlink'];
                $chart[$i]['pop'] = (int)$tv['popularity'];
                $chart[$i]['ggn'] = $this->gain($chart[$i]['tw'], $chart[$i]['lw']);
                if ($chart[$i]['ggn'] > $greatestgainer) {
                    $greatestgainer = $chart[$i]['ggn'];
                    $greatestgainerid = $i;
                }
            }
            $chart[$i]['gg'] = false;
            $i += 1;
        }

        $chart[$greatestgainerid]['gg'] = $chart[$greatestgainerid]['ggn'] > 0;
        return $chart;
    }

    /**
     * calculates gain of 2 numbers
     *
     * @param int|string $tw
     * @param int|string $lw
     * @return int
     */
    public function gain($tw, $lw): int
    {
        return ((int)$lw - (int)$tw);
    }

    /**
     * Method to merge the this week, last week into one
     *
     * @param array $tw
     * @return array $chart
     */
    public function parseArraySingle($tw): array
    {
        $chart = [];
        $i = 0;
        foreach ($tw as $tv) {
            $chart[$i]['tw'] = (int)$tv['pos'];
            $chart[$i]['artist'] = $tv['artist'];
            $chart[$i]['title'] = $tv['title'];
            $chart[$i]['image'] = $tv['image'];
            $chart[$i]['album'] = $tv['album'];
            $chart[$i]['year'] = (int)$tv['releaseyear'];
            $chart[$i]['artlink'] = $tv['artistlink'];
            $chart[$i]['pop'] = (int)$tv['popularity'];
            $i += 1;
        }
        return $chart;
    }

    /**
     * Function to get distinct dates from mysql and
     * return them as date of previous saturday
     *
     * @return array $res
     */
    public function getDistinctStartDates(): array
    {
        $res = [];
        $sql = "SELECT DISTINCT str_to_date(times, '%d-%m-%Y') as dato FROM " . $this->db->prefix('spotifyapi_music') . ' ORDER BY dato ASC ';
        $result = $this->db->queryF($sql);
        while ($row = $this->db->fetchArray($result)) {
            $res[] = $row['dato'];
        }

        return $res;
    }

    /**
     * Function to get distinct Years from mysql
     *
     * @return array $res
     */
    public function getDistinctYears(): array
    {
        $res = [];
        $sql = "SELECT DISTINCT YEAR(str_to_date(times,'%d-%m-%Y')) as dato FROM " . $this->db->prefix('spotifyapi_music') . ' ORDER BY dato ASC ';
        $result = $this->db->queryF($sql);
        while ($row = $this->db->fetchArray($result)) {
            $res[] = $row['dato'];
        }
        return $res;
    }

    /**
     * Get weekns from specified year
     *
     * @param string|int $year
     * @return array $res
     */
    public function getWeeks($year): array
    {
        $res = [];
        $sql = "SELECT DISTINCT WEEK(str_to_date(times, '%d-%m-%Y')) as weeks FROM " . $this->db->prefix('spotifyapi_music') . " WHERE YEAR(str_to_date(times, '%d-%m-%Y %H:%i:%s')) = '" . $year . "' ORDER BY weeks ASC";
        $result = $this->db->queryF($sql);
        while ($row = $this->db->fetchArray($result)) {
            $res[] = $row['weeks'];
        }
        return $res;
    }

    /**
     * Function to get distinct reduced dates from mysql where
     * start date is bigger than $start and
     * return them as date
     *
     * @param string $start
     * @return array $res
     */
    public function getDistinctReducedStartDates($start): array
    {
        $res = [];
        $sql = "SELECT DISTINCT str_to_date(times, '%d-%m-%Y') as dato FROM " . $this->db->prefix('spotifyapi_music') . " WHERE str_to_date(times, '%d-%m-%Y') > str_to_date('" . $start . "', '%d-%m-%Y') ORDER BY dato ASC";
        $result = $this->db->queryF($sql);
        while ($row = $this->db->fetchArray($result)) {
            $res[] = $row['dato'];
        }
        return $res;
    }

    /**
     * Function to parse distinct dates from mysql and
     * return them as date of previous / previous saturday - 7 days
     *
     * @param array $array
     * @param string $arg
     * @return array $d
     */
    public function parseDistinctDates($array, $arg = 'start'): array
    {
        $d = [];

        $s = 0;
        foreach ($array as $arr) {
            if ($arg == 'start') {
                $d[] = date('d-m-Y', strtotime('last ' . $this->chart_day_count . ' - 1 week', strtotime($arr)));
            }
            if ($arg == 'year') {
                $d[] = $arr;
            } else {
                $d[] = date('d-m-Y', strtotime('last ' . $this->chart_day_count, strtotime($arr)));
            }
        }

        $d = array_unique($d);
        return array_values($d);
    }

    /**
     * Get start and end date of week in specified year.
     *
     * @param int|string $week
     * @param int|string $year
     * @return array $result
     */
    public function getStartAndEndDate($week, $year): array
    {
        $dateTime = new \DateTime();
        $dateTime->setISODate($year, $week);
        $result['start_date'] = $dateTime->format('d-m-Y H:i:s');
        $dateTime->modify('+6 days');
        $result['end_date'] = $dateTime->format('d-m-Y H:i:s');
        return $result;
    }
}

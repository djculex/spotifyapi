<?php
/**
 * Spotify Api module for xoops
 *
 * @package    spotifyapi
 * @subpackage page-level
 * @author     Squiz Pty Ltd <products@squiz.net>
 * @copyright  2023 Michael Albertsen (www.culex.dk)
 * @since      1.0
 * @min_xoops  2.5.9
 */

namespace XoopsModules\Spotifyapi;

class Spotifyapi_Lastfm
{
    protected $user = null;
    protected $key = null;
    protected $status = null;
    protected $format = 'json';
    protected $endpoint = null;
    protected $number = 0;


    /**
     * Constructor
     * Set up client credentials.
     *
     * @param string $clientId The client ID.
     * @param string $clientSecret Optional. The client secret.
     * @param string $redirectUri Optional. The redirect URI.
     * @param Request $request Optional. The Request object to use.
     */
    public function __construct($user, $key, $status, $number)
    {
        $this->user = $user; //Username
        $this->key = $key; //apikey
        $this->status = $status; //status
        $this->number = $number; //number of items to show
        $this->endpoint = 'https://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=' . $user . '&&limit=' . $number . '&api_key=' . $key . '&format=' . $this->format;
        //echo $this->endpoint;
    }

    public function getLatestTracks()
    {
        $result = file_get_contents($this->endpoint);
        $json = json_decode($result, true);
        return $json['recenttracks']['track'];
    }

    public function parseArray($obj)
    {
        for ($i = 0; $i <= $this->number; $i++) {
            foreach ($obj[$i] as $k => $v) {
                echo $v['artist']['#text'] . " - " . $v['name'] . "<br>";
            }
        }
        //print_r($obj);
        //echo var_dump("<pre>",$obj,"</pre>");
    }
}
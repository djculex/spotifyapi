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

class Spotifyapi_form
{
    public ?XoopsDatabase $db;
    private ?Spotifyapi_Helper $helper;

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

        if (null === $db) {
            $db = \XoopsDatabaseFactory::getDatabaseConnection();
        }

        $this->db = $db;
    }

    /**
     * Create a dropdown select
     *
     * @param string $name
     * @param array $options
     * @param string $selected (optional)
     * @param string $sep (optional)
     * @return string
     */
    public function dropdown($name, array $options, $selected = null, $sep = '<br>'): string
    {
        $dropdown = "<select name='{$name}' id='{$name}'>\n";
        foreach ($options as $key => $option) {
            $select = $selected == $key ? ' selected' : '';
            $dropdown .= "<option value='{$key}'{$selected}>{$option}</option>\n";
        }
        $dropdown .= "</select>{$sep}";
        return $dropdown;
    }

    /**
     * Bootstrap radio button
     *
     * @param string $id id
     * @param string $name name
     * @param string $text text
     * @param $sep string|(optional)
     * @return string
     *
     */
    public function radiobutton($id, $name, $text, optional|string $sep = '<br>'): string
    {
        $string = '<div class="form-check"><input class="form-check-input" type="checkbox" value="" id="' . $id . '">';
        $string .= '<label class="' . $name . '" for="' . $name . '">' . $text . '</label></div>';
        return $string;
    }

    /**
     * Create a Submit button
     *
     * @param string $name
     * @param string $text
     * @param string $sep
     * @return string
     */
    public function submitBtn($name, $text, $sep = '<br>'): string
    {
        //<button type="submit" class="btn btn-primary">Sign in</button>
        return "<button type= 'Submit' id = '{$name}' class='btn btn-link'>{$text}</button>{$sep}";
    }
}

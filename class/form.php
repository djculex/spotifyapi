<?php
namespace XoopsModules\Spotifyapi;

use XoopsModules\Spotifyapi;
use XoopsModules\Spotifyapi\Constants;

class form
{
	public $db;
	
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
		
		//$this->numtoshow = $helper->getConfig('spotifyapinumbertoshow');
		
		
		if (null === $db) {
            $db = \XoopsDatabaseFactory::getDatabaseConnection();
        }
		
        $this->db = $db;
    }

	 /**
     * Create a dropdown select
     *
     * @param string $name
     * @param array  $options
     * @param string $selected (optional)
     * @param string $sep (optional)
     * @return string
     */
    public function dropdown($name, array $options, $selected = null, $sep = '<br>')
    {
        $dropdown = "<select name='{$name}' id='{$name}'>\n";
        foreach ($options as $key => $option) {
            $select    = $selected == $key ? ' selected' : '';
            $dropdown .= "<option value='{$key}'{$selected}>{$option}</option>\n";
        }
        $dropdown .= "</select>{$sep}";

        return $dropdown;
    }
	
	/**
	 * Bootstrap radio button
	 *
	 * @param string id
	 * @param string name
	 * @param string text
	 * @param $sep (optional)
	 * @return string
	 *
	 */
	 function radiobutton($id, $name, $text, $sep = '<br>')
	 {
		 $string  = '<div class="form-check"><input class="form-check-input" type="checkbox" value="" id="' . $id . '">';
		 $string .= '<label class="' . $name . '" for="' . $name . '">' . $text . '</label></div>';
		 return $string;
	 }
	
	/**
     * Create a Submitbutton
     *
     * @param string $name
     * @return string
     */
    public function submitBtn($name, $text, $sep = '<br>')
    {
		//<button type="submit" class="btn btn-primary">Sign in</button>
        $string = "<button type= 'Submit' id = '{$name}' class='btn btn-link'>{$text}</button>{$sep}";
        return $string;
    }

}

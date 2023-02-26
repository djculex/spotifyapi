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

use RuntimeException;
use Xmf\Module\admin;
use Xmf\Module\Helper;
use XoopsDatabaseFactory;
use XoopsMySQLDatabase;
use XoopsObjectHandler;
use XoopsPersistableObjectHandler;
use function basename;
use function class_exists;
use function dirname;
use function ucfirst;

/**
 * Class Helper
 */
class Spotifyapi_Helper extends Helper
{
    public $debug;

    /**
     * @param bool $debug
     */
    public function __construct($debug = false)
    {
        $this->debug = $debug;
        $moduleDirName = basename(dirname(__DIR__));
        parent::__construct($moduleDirName);
    }

    /**
     * @return string
     */
    public function getDirname()
    {
        return $this->dirname;
    }

    /**
     * Get an Object Handler
     *
     * @param string $name name of handler to load
     *
     * @return bool|XoopsObjectHandler|XoopsPersistableObjectHandler
     */
    public function getHandler($name)
    {
        $class = __NAMESPACE__ . '\\' . ucfirst($name) . 'Handler';
        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }
        /** @var XoopsMySQLDatabase $db */
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $helper = self::getInstance();
        $ret = new $class($db, $helper);
        $this->addLog("Getting handler '$name'");

        return $ret;
    }

    /**
     * @param bool $debug
     *
     * @return Spotifyapi_Helper
     */
    public static function getInstance($debug = false)
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($debug);
        }

        return $instance;
    }
}

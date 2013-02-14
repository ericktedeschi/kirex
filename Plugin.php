<?php

/**
 * Kirex Framework
 *
 * @category Kirex
 * @package  Kirex_Plugin
 */
class Kirex_Plugin {

    /**
     * Name of the plugin's directory inside WordPress plugins directory
     * @var String $_baseName
     */
    private $_baseName = null;

    /**
     * URL for the plugin. Useful for assets
     * @var String $_baseUrl
     */
    private $_baseUrl = null;

    /**
     * Root path of the plugin
     * Ex.: /var/www/wordpress/wp-content/plugins/pluginname
     * @var String $_basePath
     */
    private $_basePath = null;

    private $_pluginFile = null;

    /**
     * Directory of the controllers
     * @var String $_controllerDir
     */
    private $_controllerDir = null;

    /**
     * Singleton instance
     *
     * @var Kirex_Plugin
     */
    protected static $_instance = null;
    /**
     * Routes to action classes
     *
     * @var Kirex_Route
     */
    private $_routes = array();

    /**
     * Stores the actions
     * @var Array
     */
    private $_actions = array();

    /**
     * Constructor
     *
     * To instantiate this singleton class use {@link getInstance()}
     *
     * @param array $config
     */
    protected function __construct() {

    }

    /**
     * Get an instance to Kirex_Plugin
     *
     * @return Kirex_Plugin
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            $c = get_called_class();
            self::$_instance = new $c();
            if (is_callable(array(self::$_instance, 'init'))) {
                self::$_instance->init();
            }
        }
        return self::$_instance;
    }

    /**
     * Add's many types of action to the plugin
     * The actions may implement an interface
     *
     * @return Kirex_Plugin
     */
    public function addAction($action) {
        // Depending on the type of the action, the Kirex_Plugin may
        // do something or call a specific method... or a method defined on an 
        // interface
        if (!($action instanceof Kirex_Controller_Interface)) {
            throw new InvalidArgumentException('The object passed must be instance of Kirex_Controller_Interface');
        }
        $action->setKirexPlugin($this);
        if (method_exists($action, 'init')) {
            $action->init();
        }
        $this->_actions[] = $action;

        return $this;
    }

    /**
     * Controller's directory
     *
     * @param string $dir
     * @return Kirex_Plugin
     */
    public function setControllerDir($dir) {
        if (!file_exists($dir) || !is_readable($dir)) {
            throw new Exception("Error: Directory {$dir} not readable");
        }
        $this->_controllerDir = $dir;
        return $this;
    }


    /**
     * Get Base Url to the plugin
     *
     * @return String
     */
    public function getBaseUrl() {
        if ($this->_baseUrl === null) {
            $this->checkPluginFile();
            $this->_baseUrl = plugin_dir_url($this->_pluginFile);
        }
        return $this->_baseUrl;
    }

    /**
     * Gets the filesystem directory path (with trailing slash) for the plugin
     */
    public function getBasePath() {
        if ($this->_basePath === null) {
            $this->checkPluginFile();
            $this->_basePath = realpath(plugin_dir_path($this->_pluginFile));
        }
        return $this->_basePath;
    }

    /**
     * Name of the plugin's directory
     */
    public function getBaseName() {
        if ($this->_baseName === null) {
            if ($this->_basePath === null) {
                $this->getBasePath();
            }
            $this->_baseName = basename($this->_basePath);
        }
        return $this->baseName;
    }

    public function setControllersDirectory($dir) {
        if (!is_readable($dir)) {
            throw new Exception('Controller\'s directory is not readable');
        }
        $this->_controllerDirectory = $dir;
    }

    /**
     * Set's the file that contains the WordPress comment block
     *
     * @return Kirex_Plugin
     */
    public function setPluginFile($pluginFile) {
        $this->_pluginFile = $pluginFile;
    }

    /**
     * Check if the {@link $_pluginFile} is already setted
     */
    private function checkPluginFile() {
        if ($this->_pluginFile === null) {
            throw new RuntimeException('The $_pluginFile must be set');
        }
    }

}


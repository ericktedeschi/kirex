<?php

abstract class Kirex_Controller_Menu implements Kirex_Controller_Interface {
    /**
     * Instance of {@link Kirex_Plugin}
     * @var Kirex_Plugin $_kirexPlugin
     */
    protected $_kirexPlugin = null;

    /**
     * Slug to this menu page (route)
     * @var String $_slug;
     */
    protected $_slug = null;

    protected $_config = array();

    public function __construct(array $config = array()) {
        if (!isset($config['page_title']) && !isset($config['menu_title']) && !isset($config['menu_slug'])) {
            throw new InvalidArgumentException("the array parameter needs the key values page_title, menu_title and slug");
        }
        $config['page_title']  = $config['page_title'];
        $config['menu_title']  = $config['menu_title'];
        $this->_slug = $config['menu_slug'];
        $config['capability']  = 'activate_plugins'; // Sets default to a high capability
        $config['callbackMethod']    = (!isset($config['callbackMethod'])) ? 'indexAction' : $config['callbackMethod'];
        $config['icon']        = '';
        $config['position']    = null;
        $this->_config = $config;

        if (isset($config['capability'])) {
            $capability = $config['capability'];
        }

        add_action('admin_menu', array(&$this, 'registerMenu'), 10, 0);

        return $this->getSlug();
    }

    public function registerMenu() {
        add_menu_page(
            $this->_config['page_title'],
            $this->_config['menu_title'],
            $this->_config['capability'],
            $this->_slug,
            array(&$this, $this->_config['callbackMethod'])
        //    $this->_config['icon'],
        //    $this->_config['position']
        );
    }

    /**
     * Return the slug to this menu page
     * @return String
     */
    public function getSlug() {
        return $this->_slug;
    }

    public function setKirexPlugin(Kirex_Plugin $obj) {
        $this->_kirexPlugin = $obj;
    }

    public function getPageTitle() {
        return $this->_config['page_title'];
    }

    /**
     * Default action for test
     */
    public function indexAction() {
        echo "indexAction from " . __CLASS__ . ' in file ' . __FILE__;
    }
}


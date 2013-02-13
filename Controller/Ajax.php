<?php

abstract class Kirex_Controller_Ajax implements Kirex_Controller_Interface {

    /**
     * Stores the AjaxMethods identified by scanAjaxMethods method! hehe
     */
    protected $_ajaxActions = array();

    protected $_ajaxNoPrivActions = array();

    /**
     * Prefix used to generate the action hook name
     */
    protected $_prefix = null;

    public function __construct($prefix = null) {
        if ($prefix === null) {
            throw new InvalidArgumentException("The paramater \$prefix must be specified");
        }
        // TODO Validate prefix as a var name
        $this->_prefix = $prefix;

        $this->scanAjaxMethods();
    }

    /**
     * Scan methods in the format xxxxxAjax and call wp_ajax WordPress action
     */
    private function scanAjaxMethods() {
        $methods = get_class_methods(get_called_class());
        foreach ($methods as $method) {
            if ('Ajax' == substr($method, -4)) {
                $action = substr($method, 0, strlen($method) - 4);
                $this->_ajaxActions[] = $action;
                add_action('wp_ajax_' . $this->_prefix . '_' . $action, array(&$this, $method));
                continue;
            }
            if ('AjaxNoPriv' == substr($method, -10)) {
                $action = substr($method, 0, strlen($method) - 10);
                $this->_ajaxNoPrivActions[] = $action;
                // TODO Implement callback via __call to support pre and post 
                // dispatch
                add_action('wp_ajax_nopriv_' . $this->_prefix . '_' . $action, array(&$this, $method));
            }
        }
    }

    public function setKirexPlugin(Kirex_Plugin $obj) {
        $this->_kirexPlugin = $obj;
    }
}


<?php
/**
 * Control actions from WordPress
 */
abstract class Kirex_Controller_Action {

    private $_ajaxActions = array();
    private $_pageActions = array();

    private function scanActions() {
        $methods = get_class_methods($this);
        foreach ($methodas as $method) {
            if ('AjaxAction' == substr($method, -10)) {
                $this->_ajaxActions[] = $method;
                continue;
            }
            if ('PageAction' == substr($method, -10)) {
                $this->_pageActions[] = $method;
                continue;
            }
        }
    }

    public function __call($method, $args) {
        if ('AjaxAction' == substr($method, -10)) {

        }



    }


}


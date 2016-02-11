<?php

/**
 * @package Entity Route Controller
 * @version 1.0
 * @date 30 August 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/controllers/
 *
 */

class RouteController {

    public $controller          = '';
    public $controller_path     = '';
    public $method              = '';
    public $parameters          = array();

    private $routes = array(
        ''          => 'DashboardController',
        'account'   => 'AccountController',
        'users'     => 'UsersController',
        'groups'    => 'GroupsController',
        'access'    => 'AccessController',
        'projects'  => 'ProjectsController',
        'invoices'  => 'InvoicesController',
        'rates'     => 'RatesController',
        'settings'  => 'SettingsController',
        'activity'  => 'ActivityController',
        'forms'     => 'FormsController',
        'messages'  => 'MessagesController',
        'docs'      => 'DocsController',
        'uploads'   => 'UploadsController',
        'monitor'   => 'HealthMonitorController'

    );

    public function __construct($uri = '') {
        $this->parseUri($uri);
    }

    public function getPathInfo() {
        if (isset($this->controller)) {

            if (array_key_exists($this->controller_path, $this->routes)) {
                return array(
                    'controller'    => $this->routes[$this->controller_path],
                    'method'        => $this->method,
                    'parameters'    => $this->parameters
                );
            } else {
                return array(
                    'controller'    => 'NotFoundController',
                    'method'        => '',
                    'parameters'    => array()
                );
            }

        }
    }

    public function parseUri($uri = null) {
        if (!$uri) {
            $this->controller = 'DashBoard';
        }

        $path   = rtrim(str_replace(ROOT_URL, '', $uri), '/');
        $params = explode('/', $path);

        if (isset($params[0]) && $params[0] != '') {
            $this->controller_path  = $params[0];
            $this->controller       = ucfirst($params[0]);
        }

        if (isset($params[1])) {
            $this->method = $params[1];
        }

        unset($params[0], $params[1]);
        array_values($params);
        $this->parameters = $params;
    }

}
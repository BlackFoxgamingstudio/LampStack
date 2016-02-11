<?php

/*
 * View Container
 */

class ViewController {

    use ViewTrait;

    public $path;

    public static function getController($route = array()) {
        global $session;
        if (empty($route)) {
            if ($session->logged_in()) {
                return new DashboardController($route);
            } else {
                return new LoginPageController($route);
            }

        } else {
            $controller = $route['controller'];
            if (file_exists(ROOT_PATH .'app/controllers/'.$controller.'.php')) {
                if ($session->logged_in()) {
                    //var_dump($route['parameters']);exit;
                    return new $controller($route['method'], $route['parameters']);
                } else {
                    return new LoginPageController();
                }
            } else {
                return new NotFoundController();
            }

        }

    }

}
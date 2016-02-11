<?php


class HealthMonitorController
{

    use ViewTrait;

    public $controller = 'HealthMonitor';
    public $method = '';
    public $parameters = array();

    public $title = 'Entity {CC} Application Health Status';
    public $description = 'Keep an eye on what\'s going on with your Entity installation';
    public $keywords = 'status monitor, health monitor';
    private static $url = 'monitor';

    public function __construct($method = '', $parameters = array())
    {
        extract($this->pullGlobals());
        $this->method = $method;
        $this->parameters = array_values($parameters);

        $this->open();
        if (!empty($this->method)) {

            if (method_exists($this, $this->method)) {
                $method = $this->method;
                if (empty($this->parameters)) {
                    $this->all();
                } else {
                    $this->$method($this->parameters);
                }
            } else {
                $this->all();
            }
        } else {
            $this->all();
        }
        $this->close();

    }


    public function all() {
        extract($this->pullGlobals());
        if (!$current_user->role()->is_staff()) {
            require_once VIEWS . 'restricted.html.php';
        } else {
            require_once VIEWS . 'health.monitor.html.php';
        }
    }

    public static function url() {
        return BASE_URL . self::$url.'/';
    }

}
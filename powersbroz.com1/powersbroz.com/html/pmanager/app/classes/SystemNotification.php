<?php
/**
 * @package Entity SystemNotification Class and Methods
 * @version 1.0
 * @date 18 October 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class SystemNotification {

    public $title   = '';
    public $message = '';

    public $flash_messages = array();

    public function __construct($title = '', $message = '') {
        $this->title    = $title;
        $this->message  = $message;
    }

    public function queue($title, $message) {
        $this->title    = $title;
        $this->message  = $message;
    }

    public function queueFlashMsg($title, $message) {
        $this->flash_messages[] = array('title' => $title, 'message' => $message);
    }

    public function render() {
        if ($this->title != '' && $this->message != '') {
            echo '<script>entityError("'.$this->title.'", "'.$this->message.'")</script>';
            $this->resetSystemNotifications();
        }
    }

    public function hasFlashMessages() {
        if (!empty($this->flash_messages)) {
            return true;
        }
        return false;
    }

    public function showFlashMessages() {
        $html = '';
        foreach ($this->flash_messages as $flash) {
            $html .= '<span class="error-title">'.$flash['title'].'</span>: ';
            $html .= '<span class="error-message">'.$flash['message'].'</span><br/>';
        }
        return $html;
    }

    public function resetSystemNotifications() {
        $this->title    = '';
        $this->message  = '';
    }

    public function log_notification() {

    }

}

$system_notification = new SystemNotification();
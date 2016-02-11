<?php

trait ViewTrait {

    public function pullGlobals() {
        global $con;
        global $session;
        global $app;
        global $app_settings;
        global $current_user;
        global $now;
        global $app_preferences;
        global $system_notification;
        $globals = array(
            'con'                   => $con,
            'session'               => $session,
            'app'                   => $app,
            'app_settings'          => $app_settings,
            'current_user'          => $current_user,
            'now'                   => $now,
            'app_preferences'       => $app_preferences,
            'system_notification'   => $system_notification
        );
        return $globals;

    }

    public function open() {
        extract($this->pullGlobals());
        echo '<!DOCTYPE html>';
        echo '<html>';
        echo '<head>';
        echo '<meta charset="utf-8">';
        echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        echo '<title>'.$this->title.'</title>';
        echo '<meta name="description" content="'.$this->description.'">';
        echo '<meta name="keywords" content="'.$this->keywords.'" >';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">';

        require_once INCLUDES . 'css.html.php';

        echo '<script src="'.BASE_URL.'js/modernizr-2.6.2.min.js"></script>';
        echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>';
        echo '<script>window.jQuery || document.write(\'<script src="'.BASE_URL.'js/jquery-1.11.2.min.js"><\/script>\')</script>';
        echo '</head>';
        echo '<body onkeypress="quickMenu(event);">';
        echo '<div id="scroll-btn"><i class="fa fa-arrow-circle-o-up"></i></div>';

        require_once INCLUDES . 'entity.alert.html.php';
        if ($current_user->role()->is_staff()) {
            require_once INCLUDES . 'quickmenu.html.php';
        }
        require_once INCLUDES . 'flash.errors.html.php';
        require_once INCLUDES . 'navigation.html.php';
    }



    public function close() {
        extract($this->pullGlobals());
        if (DEBUG) {
            require_once INCLUDES .'debug.html.php';
        }

        require_once INCLUDES . 'footer.html.php';

        echo '<script src="'.BASE_URL.'js/entity.js"></script>';
        echo '<script src="'.BASE_URL.'js/bootstrap.min.js"></script>';
        echo '<script src="'.BASE_URL.'js/Chart.min.js"></script>';
        $system_notification->render();
        if (ENVIRONMENT != 'local' && isset($app_settings['google_analytics_code'])) {
            include_once INCLUDES . 'analytics.html.php';
        }
        echo '</body></html>';
    }

    public function errorPage() {
        extract($this->pullGlobals());
        require_once VIEWS . 'error.html.php';
    }

    public function notFoundPage() {
        extract($this->pullGlobals());
        require_once VIEWS . 'notfound.html.php';
    }

}
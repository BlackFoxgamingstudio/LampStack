<?php
/**
 * @package Entity Session Class and Methods
 * @version 1.0
 * @date 23 August 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Session {

    private     $green      = false;
    //User specific information
    protected 	$userid;
    protected 	$userrole;
    // Timeout control
    protected 	$updated;
    protected	$timeout    = 1200;

    public function __construct() {
        if (isset($_SESSION['green'])) {
            if ($_SESSION['green']) {
                // Check expiration later
                if ($this->is_expired()) {
                    $user = User::find('id', $this->userid);
                    ActivityLog::log_action($user, 'logout', $user->name() . ' was logged out due to inactivity');
                    $this->log_out();
                }
                $account = User::find('id', $_SESSION['userid']);
                if ($account) {
                    $this->log_in($account);
                } else {
                    $this->log_out();
                }
            }
        }
    }

    public function log_in(User $account) {
        global $current_user;
        $current_user = $account;
        $_SESSION['green']      = $this->green      = true;
        $_SESSION['userid']     = $this->userid     = $account->id();
        $_SESSION['userrole']   = $this->userrole   = $account->role();
        $_SESSION['updated']    = new DateTime('Now');
    }

    public function log_out() {
        session_destroy();
        global $current_user;
        $current_user = false;
        $this->green = false;
    }

    public function logged_in() {
        return $this->green;
    }

    public function update() {
        global $now;
        $this->updated = $now;
    }

    public function is_expired() {
        global $now;
        if ($this->timeout < $now->format('s') - $this->updated) {
            return true;
        }
        return false;
    }


}

$session = new Session();
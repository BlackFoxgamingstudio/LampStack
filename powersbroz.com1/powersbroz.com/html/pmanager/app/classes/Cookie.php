<?php
/**
 * @package Entity Cookie Class
 * @version 1.0
 * @date 29 November 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Cookie {


    // Default view preferences for DISPLAY ALL users
    protected $all_users_layout     = 'grid';   // grid, list
    protected $all_users_order      = 'alpha';  // alpha, newest
    // Default view preferences for DISPLAY ALL groups
    protected $all_groups_layout    = 'grid';   // grid, list
    protected $all_groups_order     = 'alpha';  // alpha, newest, size
    // Default view preferences for DISPLAY ALL projects
    protected $all_projects_layout  = 'grid';   // grid, list
    protected $all_projects_order   = 'alpha';  // alpha, newest, due
    protected $all_projects_notice  = 7;        // off, 7, 30, 60 (in days)

    // Default user preferences
    protected $display_locked_users         = false;
    protected $display_completed_projects   = true;


    // Contructor will create cookie that expires in thirty days
    public function __construct() {

        // Entity-AUL AKA ALL USERS LAYOUT
        if (isset($_COOKIE['Entity-AUL'])) {

            $this->all_users_layout = $_COOKIE['Entity-AUL'];
            unset($_COOKIE['Entity-AUL']);

        }

        // Entity-AUO AKA ALL USERS ORDER
        if (isset($_COOKIE['Entity-AUO'])) {

            $this->all_users_order = $_COOKIE['Entity-AUO'];
            unset($_COOKIE['Entity-AUO']);

        }

        // Entity-AUHLA AKA ALL USER HIDE LOCKED USERS
        if (isset($_COOKIE['Entity-AUHLA'])) {

            if ($_COOKIE['Entity-AUHLA'] == 'true') {
                $this->display_locked_users = true;
            } else {
                $this->display_locked_users = false;
            }
            //$this->display_locked_users = ($_COOKIE['Entity-AUHLA'] == "true") ? true: false;
            unset($_COOKIE['Entity-AUHLA']);

        }

        if (isset($_COOKIE['Entity-AGL'])) {

            $this->all_groups_layout = $_COOKIE['Entity-AGL'];
            unset($_COOKIE['Entity-AGL']);

        }

        if (isset($_COOKIE['Entity-AGO'])) {

            $this->all_groups_order = $_COOKIE['Entity-AGO'];
            unset($_COOKIE['Entity-AGO']);

        }

        if (isset($_COOKIE['Entity-APL'])) {

            $this->all_projects_layout = $_COOKIE['Entity-APL'];
            unset($_COOKIE['Entity-APL']);

        }

        if (isset($_COOKIE['Entity-APO'])) {

            $this->all_projects_order = $_COOKIE['Entity-APO'];
            unset($_COOKIE['Entity-APO']);

        }

        if (isset($_COOKIE['Entity-APN'])) {

            $this->all_projects_notice = $_COOKIE['Entity-APN'];
            unset($_COOKIE['Entity-APN']);

        }

        if (isset($_COOKIE['Entity-APDC'])) {

            $this->display_completed_projects = ($_COOKIE['Entity-APDC'] == "true") ? true: false;
            unset($_COOKIE['Entity-APDC']);

        }

    }

    public function get_layout($type) {
        switch ($type) {

            case 'users':
                return $this->all_users_layout;
                break;

            case 'projects':
                return $this->all_projects_layout;
                break;

            case 'groups':
                return $this->all_groups_layout;
                break;

            default:
                return 'grid';

        }
    }

    public function get_order($type) {
        switch ($type) {

            case 'users':
                return $this->all_users_order;
                break;

            case 'projects':
                return $this->all_projects_order;
                break;

            case 'groups':
                return $this->all_groups_order;
                break;

            default:
                return 'grid';

        }
    }

    public function display_locked() {
        return $this->display_locked_users;
    }

    public function change_value($name, $value) {
        setcookie($name, $value, time() + (86400 * 30));
    }

    public function debug_line($type = 'users') {

        switch ($type) {

            case 'users':
                $order  = $this->get_order('users');
                $layout = $this->get_layout('users');
                $extra  = '';
                if ($this->display_locked()) {
                    $extra .= ' Locked accounts are <code>shown</code>';
                } else {
                    $extra .= ' Locked accounts are <code>hidden</code>';
                }
                break;

            case 'groups':
                $order  = $this->get_order('groups');
                $layout = $this->get_layout('groups');
                $extra  = '';
                break;

            case 'projects':
                $order  = $this->get_order('projects');
                $layout = $this->get_layout('projects');
                $extra  = '';
                break;

        }

        $html = '<p>Layout: <code>'. $layout.'</code> Order: <code>'.$order.'</code>';
        if ($extra != '') {
            $html .= $extra;
        }
        $html .= '</p>';
        return $html;
    }

}

$app_preferences = new Cookie();
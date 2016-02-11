<?php
/**
 * @package Entity View Class and Methods
 * @version 1.0
 * @date 21 October 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class View {

    public $primary;
    public $secondary;
    public $command;

    public $title       = 'Entity {CC} 2.0';
    public $description = '';
    public $keywords    = '';

    public $html        = '';

    public function open() {
        global $con;
        global $session;
        global $app;
        global $app_settings;
        global $current_user;
        global $now;
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
        require_once INCLUDES . 'quickmenu.html.php';
        require_once INCLUDES . 'navigation.html.php';
    }

    public function content($uri) {
        global $con;
        global $session;
        global $app;
        global $app_settings;
        global $current_user;
        global $now;
        global $app_preferences;
        global $system_notification;

        $uri = str_replace(ROOT_URL, '/', $uri);
        $params = explode('/', $uri);
        if (count($params) > 2) {
            $this->primary      = $params[1];
            $this->secondary    = $params[2];
            //echo $this->primary;
            //echo $this->secondary;
            //exit;
        } elseif (count($params) > 1) {
            $this->primary = $params[1];
            //echo $this->primary;
            //exit;
        }



        switch($this->primary) {

            case 'activity':
                if (!$current_user->role()->can_access('activity', 'system')) {
                    require_once VIEWS . 'restricted.html.php'; break;
                }
                require_once VIEWS . 'activity.html.php';
                break;

            case 'account':
                if (!empty($this->secondary)) {
                    switch($this->secondary) {

                        case 'edit':
                            require_once VIEWS . 'account.edit.html.php';
                            break;

                        case 'timers':
                            require_once VIEWS . 'timer.items.html.php';
                            break;

                        default:
                            require_once VIEWS . 'account.html.php';

                    }
                } else {
                    require_once VIEWS . 'account.html.php';
                }
                break;

            case 'users':
                $users = User::find('sql', "SELECT * FROM users ORDER BY lname ASC");
                if (!empty($this->secondary)) {
                    //echo $this->secondary;
                    if (is_numeric($this->secondary)) {
                        $user = User::find('id', $this->secondary);
                        if ($user) {
                            require_once VIEWS . 'single.user.html.php';
                        } else {
                            $system_notification->queue('No User Found', 'The user you are trying to access does not exist');
                            require_once VIEWS . 'all.users.html.php';
                        }
                    } else {
                        $system_notification->queue('Incorrect Access', 'The request you provided was not recognized');
                        require_once VIEWS . 'all.users.html.php';
                    }
                } else {

                    require_once VIEWS . 'all.users.html.php';
                }
                break;

            case 'groups':
                $groups = Group::find('sql', "SELECT * FROM groups ORDER BY gname ASC");
                if (!empty($this->secondary)) {
                    if (is_numeric($this->secondary)) {
                        $group = Group::find('id', $this->secondary);
                        if ($group) {
                            if (!$current_user->role()->can_access($group->id(), 'group')) {
                                require_once VIEWS . 'restricted.html.php'; break;
                            }
                            require_once VIEWS . 'single.group.html.php';
                        } else {
                            $system_notification->queue('No Group Found', 'The group you are trying to access does not exist');
                            require_once VIEWS . 'all.groups.html.php';
                        }
                    } else {
                        $system_notification->queue('Incorrect Access', 'The request you provided was not recognized');
                        require_once VIEWS . 'all.groups.html.php';
                    }
                } else {
                    require_once VIEWS . 'all.groups.html.php';
                }
                break;

            case 'access':
                $roles = Role::find('all');
                if (!$current_user->role()->can_access('roles', 'system')) {
                    require_once VIEWS.'restricted.html.php'; break;
                }
                if (!empty($this->secondary)) {
                    switch($this->secondary) {
                        case 'new':
                            require_once VIEWS . 'new.role.html.php';
                            break;

                        default:
                            $system_notification->queue('Incorrect Access', 'The request you provided was not recognized');
                            require_once VIEWS . 'all.roles.html.php';
                    }
                } else {
                    require_once VIEWS . 'all.roles.html.php';
                }
                break;

            case 'projects':
                $projects = Project::find('all');
                if (!empty($this->secondary)) {

                    //echo $this->secondary;
                    if (is_numeric($this->secondary)) {
                        $project = Project::find('id', $this->secondary);
                        if ($project) {
                            if (!$current_user->role()->can_access($project->id(), 'project')) {
                                require_once VIEWS . 'restricted.html.php'; break;
                            }
                            require_once VIEWS . 'single.project.html.php';
                        } else {
                            $system_notification->queue('No Project Found', 'The project you are trying to access does not exist');
                            require_once VIEWS . 'all.projects.html.php';
                        }
                    } else {
                        $system_notification->queue('Incorrect Access', 'The request you provided was not recognized');
                        require_once VIEWS . 'all.projects.html.php';
                    }
                } else {
                    require_once VIEWS . 'all.projects.html.php';
                }

                break;

            case 'invoices':
                if (!empty($this->secondary)) {
                    //echo $this->secondary;
                    switch ($this->secondary) {
                        case 'history':
                            $invoices = Invoice::find('sql', "SELECT * FROM invoices ORDER BY duedate ASC");
                            require_once VIEWS . 'search.invoices.html.php';
                            break;
                        default:
                            $invoices = Invoice::find('sql', "SELECT * FROM invoices WHERE inumber = '".$this->secondary."'");
                            if ($invoices) {
                                require_once VIEWS . 'single.invoice.html.php';
                            } else {
                                if ($current_user->role()->is_staff()) {
                                    $invoices = Invoice::find('sql', "SELECT * FROM invoices");
                                    require_once VIEWS . 'staff.all.invoices.html.php';
                                } else {
                                    $invoices = Invoice::find('sql', "SELECT * FROM invoices WHERE paidby = ".$current_user->id()." OR paidto =".$current_user->id());

                                    require_once VIEWS . 'all.invoices.html.php';
                                }
                            }
                    }
                } else {

                    if ($current_user->role()->is_staff()) {
                        $invoices = Invoice::find('sql', "SELECT * FROM invoices");
                        require_once VIEWS . 'staff.all.invoices.html.php';
                    } else {
                        $invoices = Invoice::find('sql', "SELECT * FROM invoices WHERE paidby = ".$current_user->id()." OR paidto =".$current_user->id());

                        require_once VIEWS . 'all.invoices.html.php';
                    }
                }
                break;

            case 'messages':
                require_once VIEWS . 'all.messages.html.php';
                break;

            case 'rates':
                require_once VIEWS . 'all.rates.html.php';
                break;

            case 'calendar':
                require_once VIEWS . 'calendar.html.php';
                break;

            case 'settings':
                if (!$current_user->role()->can_access('settings', 'system')) {
                    require_once VIEWS . 'restricted.html.php';
                } else {
                    require_once VIEWS . 'settings.html.php';
                }
                break;

            case 'docs':
                if (!$current_user->role()->can_access('docs', 'system')) {
                    require_once VIEWS . 'restricted.html.php';
                } else {
                    require_once VIEWS . 'documentation.html.php';
                }
                break;

            case 'personal-files':
                if (!$current_user->role()->can('file', 'create')) {
                    require_once VIEWS . 'restricted.html.php';
                } else {
                    require_once VIEWS . 'all.files.html.php';
                }
                break;

            case 'forms':
                $forms = Form::find('all');
                if (!$current_user->role()->is_staff()) {
                    require_once VIEWS . 'restricted.html.php'; break;
                }
                if (!empty($this->secondary)) {

                    $form = Form::find('sql', "SELECT * FROM forms WHERE slug = '".$this->secondary."'");
                    if (!$form) {
                        $system_notification->queue('No Form Found', 'The form you are trying to access does not exist');
                        require_once VIEWS . 'all.forms.html.php';
                        break;
                    } else {
                        $form = array_shift($form);
                        require_once VIEWS . 'single.form.html.php';
                        break;
                    }

                } else {

                    require_once VIEWS . 'all.forms.html.php';

                }
                break;

            default:
                require_once VIEWS . 'home.html.php';

        }

    }

    public function close() {
        global $con;
        global $session;
        global $app;
        global $app_settings;
        global $current_user;
        global $now;
        global $app_preferences;
        global $system_notification;

        if (DEBUG) {
            require_once INCLUDES .'debug.html.php';
        }

        require_once INCLUDES . 'footer.html.php';

        echo '<script src="'.BASE_URL.'js/entity.js"></script>';
        echo '<script src="'.BASE_URL.'js/bootstrap.min.js"></script>';
        echo '<script src="'.BASE_URL.'js/Chart.min.js"></script>';
        $system_notification->render();
        if (ENVIRONMENT != 'local') {
            include_once INCLUDES . 'analytics.html.php';
        }
        echo '</body></html>';
    }

    public function make($uri) {
        $this->open();
        $this->content($uri);
        $this->close();
    }

}
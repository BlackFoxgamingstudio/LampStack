<?php
/**
 * @package Entity HTML Trait - Common HTML Returns
 * @version 1.0
 * @date 21 October 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/traits/
 *
 */

trait HTML {

    public static function html_select($id, $params = array(), $selected = '') {
        global $current_user;
        if (empty($params)) {
            $objects = self::find('all');
        } else {

            if (isset($params['user'])) {

                switch($params['user']) {

                    case 'no-admin':
                        $objects = self::find('sql', "SELECT * FROM users WHERE NOT role = 1");
                        break;
                    case 'no-staff':
                        $objects = self::find('sql', "
                        SELECT users.id, uname, pass, fname, lname, email, bio, avatar, website, facebook, twitter, googleplus, linkedin, skype, yahoo, homephone, cellphone, workphone, fax, addressone, addresstwo, city, state, zip, country, role, active, stripe_secret_key, stripe_publish_key, paypal_email, users.created, users.updated
                        FROM users
                        INNER JOIN user_roles
                        ON users.role = user_roles.id
                        WHERE NOT user_roles.classification = 1");
                        break;
                    case 'only-staff':
                        $objects = self::find('sql', "
                        SELECT users.id, uname, pass, fname, lname, email, bio, avatar, website, facebook, twitter, googleplus, linkedin, skype, yahoo, homephone, cellphone, workphone, fax, addressone, addresstwo, city, state, zip, country, role, active, stripe_secret_key, stripe_publish_key, paypal_email, users.created, users.updated
                        FROM users
                        INNER JOIN user_roles
                        ON users.role = user_roles.id
                        WHERE user_roles.classification = 1");
                        break;
                    case 'no-contractors':
                        $objects = self::find('sql', "
                        SELECT users.id, uname, pass, fname, lname, email, bio, avatar, website, facebook, twitter, googleplus, linkedin, skype, yahoo, homephone, cellphone, workphone, fax, addressone, addresstwo, city, state, zip, country, role, active, stripe_secret_key, stripe_publish_key, paypal_email, users.created, users.updated
                        FROM users
                        INNER JOIN user_roles
                        ON users.role = user_roles.id
                        WHERE NOT user_roles.classification = 2");
                        break;
                    case 'no-clients':
                        $objects = self::find('sql', "
                        SELECT users.id, uname, pass, fname, lname, email, bio, avatar, website, facebook, twitter, googleplus, linkedin, skype, yahoo, homephone, cellphone, workphone, fax, addressone, addresstwo, city, state, zip, country, role, active, stripe_secret_key, stripe_publish_key, paypal_email, users.created, users.updated
                        FROM users
                        INNER JOIN user_roles
                        ON users.role = user_roles.id
                        WHERE NOT user_roles.classification = 3");
                        break;
                    case 'not-me':
                        $objects = self::find('sql', "SELECT * FROM users WHERE id <> ".$current_user->id());
                        break;

                    case 'me':
                        $objects = self::find('sql', "SELECT * FROM users WHERE id = ". $current_user->id());
                        break;

                }

            }

            if (isset($params['group'])) {

                switch($params['group']) {

                    case 'no-owner':
                        $objects = self::find("sql", "SELECT * FROM groups WHERE owner = 0");
                        break;

                }

            }

            if (isset($params['project'])) {

                switch($params['project']) {

                    case 'no-lead':
                        $objects = self::find('sql', "SELECT * FROM projects WHERE owner = 0");
                        break;

                    case 'not-complete':
                        $objects = self::find('sql', "SELECT * FROM projects WHERE complete = 0");
                        break;

                    case 'no-lead not-complete':
                        $objects = self::find('sql', "SELECT * FROM projects WHERE owner = 0 AND complete = 0");
                        break;

                }

            }

            if (isset($params['currency'])) {
                $objects = self::find('all');
            }
        }

        if ($objects) {
            $html = '<select id="'.$id.'" name="'.$id.'">';
            for ($i = 0; $i < count($objects);$i++) {
                if ($selected != '') {
                    if ($objects[$i]->id() == $selected) {
                        $html .= '<option value="'.$objects[$i]->id().'" selected>'.$objects[$i]->name().'</option>';
                    } else {
                        $html .= '<option value="'.$objects[$i]->id().'">'.$objects[$i]->name().'</option>';
                    }
                } else {
                    $html .= '<option value="'.$objects[$i]->id().'">'.$objects[$i]->name().'</option>';
                }

            }
            $html .= '</select>';
            return $html;
        } else {
            return '<p class="alert alert-warning">No records found!</p>';
        }

    }

}
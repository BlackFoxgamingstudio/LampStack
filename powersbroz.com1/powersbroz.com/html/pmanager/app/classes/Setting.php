<?php
/**
 * @package Entity Setting Class and Methods
 * @version 1.0
 * @date 21 February 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Setting {

    public $settings = array();

    // Constructor functions
    public function __construct() {
        global $con;
        if ($con->gate->query("DESCRIBE app_settings")) {
            $values = $con->gate->query("SELECT * FROM app_settings");
            if ($values->num_rows > 0) {
                while($row = $values->fetch_array(MYSQLI_ASSOC)) {
                    $this->settings[$row['setting_name']] = $row['setting_value'];
                }
            }
        }
    }

    public function initialized() {
        if (!empty($this->settings)) {
            return true;
        }
        return false;
    }

    public function owner() {
        if (!empty($this->settings['owner_account'])) {
            $owner = User::find('id', $this->settings['owner_account']);
            if ($owner) {
                return $owner;
            } else {
                return false;
            }
        }
    }

    public function get($string = '') {
        if ($string == '') {
            return false;
        } else {
            if (array_key_exists($string, $this->settings)) {
                return $this->settings[$string];
            } else {
                return false;
            }
        }
    }

    public function company() {
        $html = '<ul class="list-unstyled">';
        $html .= '<li>'. $this->settings['company_name'] .'</li>';
        $html .= '<li>'. $this->settings['company_address_one'] .'</li>';
        $html .= ($this->settings['company_address_two'] !== '') ? '<li>'. $this->settings['company_address_two'].'</li>' : '';
        $html .= '<li>'. $this->settings['company_city'] .', ' . $this->settings['company_state'] . ' ' . $this->settings['company_zip_code'] . '</li>';
        $html .= '<li>'. $this->settings['company_country'] .'</li>';
        $html .= '</ul>';

        echo $html;

    }

    public function company_contact() {
        $html = '<table class="table"><tbody>';
        $html .= '<tr><td><i class="fa fa-phone"></i> Main:</td><td>'. $this->settings['company_phone_main'] .'</td></tr>';
        $html .= '<tr><td><i class="fa fa-phone"></i> Support:</td><td>'. $this->settings['company_phone_support'] .'</td></tr>';
        $html .= '<tr><td><i class="fa fa-print"></i> Fax:</td><td>'. $this->settings['company_fax'] .'</td></tr>';
        $html .= '<tr><td><i class="fa fa-envelope"></i> Email:</td><td>'. $this->settings['company_email_main'] .'</td></tr>';
        $html .= '<tr><td><i class="fa fa-envelope"></i> Support Email:</td><td>'. $this->settings['company_email_support'] .'</td></tr>';
        $html .= '</tbody></table>';

        echo $html;
    }

    public function appStripeCapable() {
        if ($this->get('company_use_stripe') == 1) {
            // Enabled. Check mode
            switch ($this->get('stripe_mode')) {

                case 'test':
                    // Check keys
                    if ($this->get('stripe_test_secret') == '' || $this->get('stripe_test_publishable') == '') {
                        return false;
                    }
                    return true;
                    break;

                case 'live':
                    // Check keys
                    if ($this->get('stripe_live_secret') == '' || $this->get('stripe_live_publishable') == '') {
                        return false;
                    }
                    return true;
                    break;

                default:
                    return false;

            }
        }
        return false;
    }

    public function appStripePublishableModeKey() {
        if ($this->get('stripe_mode') == 'test') {
            return $this->get('stripe_test_publishable');
        } else {
            return $this->get('stripe_live_publishable');
        }
    }

    public function appStripeSecretModeKey() {
        if ($this->get('stripe_mode') == 'test') {
            return $this->get('stripe_test_secret');
        } else {
            return $this->get('stripe_live_secret');
        }
    }

}

$app_settings = new Setting();
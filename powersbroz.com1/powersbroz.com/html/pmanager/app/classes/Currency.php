<?php
/**
 * @package Entity Currency Class
 * @version 1.0
 * @date 09 December 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class Currency {

    use Finder, HTML, Access;

    // Default Rates
    private $usd = 1.0;

    private $id;
    private $symbol;
    private $code;
    private $currency;
    private $rate;
    private $updated;

    private static $table = 'app_currencies';


    // Constructor functions
    public function __construct($record) {
        $this->id 	        = $record['id'];
        $this->symbol       = $record['symbol'];
        $this->code         = trim($record['code']);
        $this->currency     = $record['currency'];
        $this->rate         = $record['rate'];
        $this->updated      = new DateTime($record['updated']);

    }

    public function symbol() {
        return $this->symbol;
    }

    public function name() {
        return htmlspecialchars($this->currency);
    }

    public function rate() {
        return $this->rate;
    }

    public function code() {
        return $this->code;
    }

    public function updated() {
        return $this->updated;
    }

    public static function last_updated() {
        $latest = self::find('sql', "SELECT * FROM app_currencies ORDER BY updated DESC LIMIT 1");
        $latest = array_shift($latest);
        return $latest->updated()->format('F d, Y') .' @ '.readable_time($latest->updated());
    }

    // Conversion

    public static function convert($units, $wage_rate, $invoice_rate, $flat = false) {
        global $app_settings;

        $system_rate = self::find('id', $app_settings->get('wage_currency'));
        if ($system_rate->id() == 1) {

            $original_amount = $units * $wage_rate;
            //echo 'Original Amount: '.$original_amount.' currency for '.$units.' units @ '.$wage_rate.' per unit<br/>';
            $new_amount = $original_amount * $invoice_rate;
            //echo 'New Amount: '.$new_amount.' currency for '.$units.' units @ '.$invoice_rate.' per unit<br/>';exit;

        } else {

            $original_amount = $units * $wage_rate; // In that currency
            $dollar_amount   = (1 / $system_rate->rate()) * $original_amount;
            //echo 'Original Amount: '.$original_amount.' currency for '.$units.' units @ '.$wage_rate.' per unit<br/>';
            $new_amount = $dollar_amount * $invoice_rate;
            //echo 'New Amount: '.$new_amount.' currency for '.$units.' units @ '.$invoice_rate.' per unit<br/>';

        }

        return $new_amount;
    }


}
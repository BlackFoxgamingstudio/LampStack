<?php
/**
 * @package Entity CurrencyExchange Class (utilizes fixer io)
 * @version 1.0
 * @date 01 November 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/classes/
 *
 */

class CurrencyExchange {

    protected static $ratesJSON;
    protected static $baseCurrency;

    public static function updateRates(Currency $baseCurrency) {
        global $con;
        $now = new DateTime('now');

        self::$baseCurrency     = $baseCurrency;
        self::$ratesJSON        = json_decode(self::getRates($baseCurrency->code()), true);
        $currencies             = Currency::find('all');

        foreach ($currencies as $currency) {
            if ($currency->id() == self::$baseCurrency->id()) {
                $con->gate->query("UPDATE app_currencies SET rate = 1, updated = '".$now->format('Y-m-d H:i:s')."' WHERE id = ".self::$baseCurrency->id());
            } else {
                $newRate = self::$ratesJSON['rates'][$currency->code()];
                $con->gate->query("UPDATE app_currencies SET rate = ".$newRate.", updated = '".$now->format('Y-m-d H:i:s')."' WHERE id = ".$currency->id());
            }
        }
        return true;
    }

    public static function getRates($baseCurrencyCode) {
        //http://api.fixer.io/latest?base=USD
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, "http://api.fixer.io/latest?base=".$baseCurrencyCode);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}
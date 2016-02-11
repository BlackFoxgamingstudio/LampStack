<?php
/**
 * @package Entity Access Trait
 * @version 1.0
 * @date 21 October 2014
 * @author Travis Coats, Zen Perfect Design
 * @location app/traits/
 *
 */

trait Access {

    // Creation function
    private static function instantiate($record) {
        $object = new self($record);
        return $object;
    }

    // Getter / Setter functions
    public function get($string) {
        return $this->$string;
    }

    public function set($var, $value) {
        $this->$var = $value;
    }

    public function id() {
        return $this->id;
    }

}
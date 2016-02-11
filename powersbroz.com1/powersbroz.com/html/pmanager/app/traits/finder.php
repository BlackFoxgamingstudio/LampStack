<?php
/**
 * @package Entity Finder Trait
 * @version 1.1
 * @date 18 October 2015
 * @author Travis Coats, Zen Perfect Design
 * @location app/traits/
 *
 */

trait Finder {

    public static function total() {
        $select = gen_query("SELECT * FROM ".self::$table);
        return $select['count'];
    }

    public static function find($flag = 'all', $arg = '') {

        switch ($flag) {
            case 'all':
                try {
                    $object = self::find('sql', 'SELECT * FROM '.self::$table);
                    return $object;
                } catch (Exception $e) {
                    var_dump($e->getTrace()); die();
                }
                break;
            case 'id':
                try {
                    $array  = self::find('sql', 'SELECT * FROM '.self::$table.' WHERE id = '.$arg);
                    $object = array_shift($array);
                    return $object;
                } catch (Exception $e) {
                    var_dump($e->getTrace()); die();
                }
                break;

            case 'sql':
                global $con;
                if ($arg != '') {
                    try {

                        $result_set = $con->gate->query($arg);
                        if ($result_set) {
                            $objects = array();
                            while($row = $result_set->fetch_array(MYSQLI_ASSOC)) {
                                $objects[] = self::instantiate($row);
                            }
                            return $objects;
                        }
                        return false;
                    } catch (Exception $e) {
                        var_dump($e->getTrace()); die();
                    }
                } else {
                    return false;
                }
                break;

            default:
                return false;
        }
    }

    public static function newest() {
        $object = self::find('sql', "SELECT * FROM ".self::$table." ORDER BY created DESC LIMIT 1");
        if ($object) {
            $object = array_shift($object);
            return $object;
        } else {
            return false;
        }
    }

    public static function oldest() {
        $object = self::find('sql', "SELECT * FROM ".self::$table." ORDER BY created ASC LIMIT 1");
        if ($object) {
            $object = array_shift($object);
            return $object;
        } else {
            return false;
        }
    }


}
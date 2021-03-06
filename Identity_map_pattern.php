<?php
/**
* Шаблон Identity Map
*/
namespace woo\domain;

class ObjectWatcher {
    private $all = array();
    private static $instance=null;

    private function __construct() {
    }
  
    static function instance()
    {
        if ( is_null( self::$instance ) ) {
            self::$instance = new ObjectWatcher();
        }
        return self::$instance;
    }
  
    public function globalKey(DomainObject $obj)
    {
        $key = get_class( $obj ).".".$obj->getId();
        return $key;
    }
  
    static function add(DomainObject $obj)
    {
        $inst = self::instance();
        $inst->all[$inst->globalKey( $obj )] = $obj;
    }

    static function exists($classname, $id)
    {
        $inst = self::instance();
        $key = "$classname.$id";
        if ( isset( $inst->all[$key] ) ) {
            return $inst->all[$key];
        }
        return null;
    }
}


namespace woo\mapper;
abstract class Mapper
{
    private function getFromMap($id)
    {
        return \woo\domain\ObjectWatcher::exists
                ( $this->targetClass(), $id );
    }
    
    private function addToMap(\woo\domain\DomainObject $obj)
    {
        return \woo\domain\ObjectWatcher::add( $obj );
    }
    
    function find($id)
    {
        $old = $this->getFromMap( $id );
        if ( ! is_null( $old ) ) { return $old; }
        //читаем БД
        return $object; 
    }
}

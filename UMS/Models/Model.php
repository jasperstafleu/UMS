<?php
namespace UMS\Models;

/**
 * Abstract Model: some methods each model should contain. This includes a magic
 * getter, setter and caller. The first two redirect to getParam and setParam
 * methods, which are caught by the third by default. The third looks for
 * protected properties with the same name as the parameter called (prefixed
 * with an underscore), and gets / sets that variable.
 *
 * Each class extending this abstract one should have two @method phpdoc part
 * for each of its protected variables; a getter and a setter
 *
 * @author Jasper Stafleu
 */
abstract class Model implements \Serializable
{
    /**
     * Constructor. It uses the argument as fields to initialize the entity
     *
     * @param array $properties
     */
    public function __construct(array $properties = array())
    {
        foreach ( $properties as $key => $value ) {
            $this->$key = $value;
        } // foreach
    } // __construct();

    /**
     * (non-PHPdoc)
     * @see Serializable::serialize()
     */
    public function serialize()
    {
        $serialize = array();
        $refl = new \ReflectionObject($this);
        foreach ( $refl->getProperties() as $prop ) {
            if ( $prop->isProtected() ) {
                $prop = $prop->getName();
                $serialize[$prop] = $this->$prop;
            }
        } // foreach
        return serialize($serialize);
    } // serialize();

    /**
     * (non-PHPdoc)
     * @see Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        $obj = unserialize($serialized);
        foreach ( $obj as $key => $value ) {
            $this->$key = $value;
        } // foreach
    } // unserialize();

    /**
     * Magic setter: set the $what propery to $value, provided the $what
     * property is defined as being protected
     *
     * @param string $what
     * @param mixed $value
     */
    public function __set($what, $value)
    {
        $method = 'set' . ucfirst($what);
        return call_user_func_array(array($this, $method), array($value));
    } // __set();

    /**
     * Retuns the value if _$what for this model.
     *
     * @param string $what
     */
    public function __get($what)
    {
        $method = 'get' . ucfirst($what);
        return $this->$method();
    } // __get();

    /**
     * Magic call method; implements getter and setter logic
     *
     * @param string $method
     * @param Array $arguments
     * @return Mixed or \UMS\Models\Model
     */
    public function __call($method, $arguments)
    {
        // magic getter
        if ( substr($method, 0, 3) === 'get' ) {
            $prop = '_' . lcfirst(substr($method, 3));
            if ( property_exists($this, $prop) ) {
                $refl = new \ReflectionProperty($this, $prop);
                if ( $refl->isProtected() ) {
                    return $this->$prop;
                }
            }
        }

        // magic setter
        if ( substr($method, 0, 3) === 'set' ) {
            $prop = '_' . lcfirst(substr($method, 3));
            if ( property_exists($this, $prop) ) {
                $refl = new \ReflectionProperty($this, $prop);
                if ( $refl->isProtected() ) {
                    $this->$prop = array_shift($arguments);
                    return $this;
                }
            }
        }
    } // __call();

} // end class Models
<?php
namespace UMS\Models;

abstract class Model implements \Serializable, \UMS\Interfaces\iModel
{
    /**
     * Constructor. It uses the argument as fields to initialize the entity
     *
     * @param array $properties
     */
    public function __construct(array $properties)
    {
        $refl = new \ReflectionObject($this);
        foreach ( $properties as $property => $value ) {
            if ( $refl->hasProperty($property) && $refl->getProperty($propery)->isProtected() ) {
                $this->$property = $value;
            }
        } // foreach
    } // __construct();

    /**
     * (non-PHPdoc)
     * @see Serializable::serialize()
     */
    public function serialize()
    {
        // TODO: implement
    } // serialize();

    /**
     * (non-PHPdoc)
     * @see Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        // TODO: Implement
    } // unserialize();

    /**
     * (non-PHPdoc)
     * @see \UMS\Interfaces\iModel::save()
     */
    public function save()
    {
        var_dump($this);
        // TODO: implement
    } // save();
} // end class Models
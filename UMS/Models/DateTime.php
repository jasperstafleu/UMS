<?php
namespace UMS\Models;

/**
 * DateTime class. Primarily implemented to wrap strftime around the default
 * DateTime object.
 *
 * @author Jasper Stafleu
 */
class DateTime extends \DateTime
{
    /**
     * Implements the default strftime method upon this DateTime object
     *
     * @param $format
     * @return string
     * @see strftime
     */
    public function strftime($format)
    {
        return strftime($format, $this->getTimestamp());
    } // strftime();

    /**
     * Returns the RFC333* value of this DateTime. This should be perfect for
     * storing it in a DB.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format(\DateTime::RFC3339);
    } // __toString();

} // end class UMS\Models\DateTime
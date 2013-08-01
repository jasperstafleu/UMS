<?php
namespace UMS\Views;

class Content extends AbstractView
{
    /**
     * Adds a randomID to the normal version of the view
     *
     * @param string $template
     * @param string $context
     */
    public function __construct($template, $context = null)
    {
        parent::__construct($template, $context);
        $this->randomID = mt_rand();
    } // __construct();

} // end class UMS\Views\Content
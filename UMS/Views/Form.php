<?php
namespace UMS\Views;

class Form extends AbstractView
{
    /**
     * The array containing errors for this form
     *
     * @var array
     */
    public $errors = array();

    /**
     * Constructor. Same as abstract view, but with errors array
     *
     * @param sting $template
     * @param string $context
     * @param array $errors
     */
    public function __construct($template, $context = null, $errors = array())
    {
        parent::__construct($template, $context);
        $this->errors = $errors;
    } // __construct();
} // end class UMS\Views\Form
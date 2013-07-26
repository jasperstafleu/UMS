<?php
namespace UMS\Views;

/**
 * Login view: basically a normal view, but always has the currently logged in
 * user as context, if any
 *
 * @author Jasper Stafleu
 */
class Login extends AbstractView {

    /**
     * Constructor for the view
     *
     * @param string $template
     */
    public function __construct($template)
    {
        parent::__construct($template, empty($_SESSION['User']) ? null : $_SESSION['User']);
    } // __construct();

} // end class Login();
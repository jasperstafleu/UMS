<?php
namespace UMS\Controllers;

/**
 * User controller
 *
 * @author Jasper Stafleu
 */
class User
{
    /**
     * Creates the User, using $_POST as properties of the user
     */
    public static function create()
    {
        // create a user from the POST variables, if they are valid
        if ( !empty($_POST) ) {
            $user = new \UMS\Models\User($_POST);
            $_POST['errors'] = self::_validate($user);

            // no errors? Save the user, log her in and try context based
            // handling (usually goes to update the same user)
            if ( empty($_POST['errors']) ) {
                $user->save();

                // If no user is already logged in (ie an admin creating a new
                // account), log the newly created user in
                if ( !Login::getUser() ) {
                    Login::doLogin();
                }

                // ensure the new contextbased action is not triggered using the
                // just posted values
                $_POST = array();

                // go to context based action determination (update for new
                // users, listing for admins
                return self::contextbased();
            }
        }

        echo new \UMS\Views\Standard('standard.phtml', $_POST);
    } // create();

    /**
     * Shows a listing of all users in the database
     */
    public static function listing()
    {
        echo new \UMS\Views\Standard('listing.phtml');
    } // listing();

    /**
     * Shows a listing of all users in the database
     */
    public static function update()
    {
        echo new \UMS\Views\Standard('standard.phtml', Login::getUser());
    } // listing();

    /**
     * Does context based controlling, which might mean
     * - Showing the create user form (if no user is logged in)
     * - Showing the logged in user form (for normal users)
     * - Showing a listing of available users (for admins)
     */
    public static function contextbased()
    {
        $user = Login::getUser();

        switch ( true ) {
            default :
            case !$user :
                return self::create();
            case $user->isAdmin :
                return self::listing();
            case !!$user :
                return self::update();
        } // switch
    } // contextbased ();

    /**
     * Validates the user for insertion into the database
     */
    protected static function _validate(\UMS\Models\User $user)
    {
        $errors = array();

        // required fields validation
        if ( !$user->email ) {
            $errors['email'] = 'Email field is required';
        } else if ( !filter_var($user->email, FILTER_VALIDATE_EMAIL) ) {
            $errors['email'] = 'Email is not a valid email adress';
        } else if ( isset($_POST['email-repeat']) && $_POST['email'] !== $_POST['email-repeat'] ) {
            $errors['email-repeat'] = 'Email fields do not match';
        }

        if ( !$user->pass ) {
            $errors['pass'] = 'Pass field is required';
        } else if ( isset($_POST['pass']) && strlen($_POST['pass']) < 10 ) {
            $errors['pass'] = 'Pass field is not long enough';
        }

        if ( !$user->firstname ) {
            $errors['firstname'] = 'Firstname is required';
        }

        if ( !$user->lastname ) {
            $errors['lastname'] = 'Lastname is required';
        }

        // TODO: admin check, notes check
        return $errors;
    } // _validate();


} // end class UMS\Controllers\User
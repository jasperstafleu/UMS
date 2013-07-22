<?php
namespace UMS\Models;

/**
 * User model: responsible for containing user information: username (email) and
 * password
 *
 * @method getEmail()
 *         string getEmail()
 *         Returns the email adress the user wants to receive reset password
 *         answers to.
 * @method setEmail()
 *         \UMS\Models\User setEmail(string emailAdress)
 *         Set the email adress the user wants to receive reset password answers
 *         to.
 * @method getPass()
 *         string getPass()
 *         Returns the users password
 * @method setPass()
 *         \UMS\Models\User setEmail(string emailAdress)
 *         Set the users password to the string passed
 *
 * @author Jasper Stafleu
 */
class User extends Model
{
    /**
     * The email address for the user. This is the email address used to send
     * the user his temporary password when he triggers a reset of the password.
     * Other email adresses (if any) should be set in the user's profile
     *
     * @var string
     */
    protected $_email = '';

    /**
     * The user's password. This is the encrypted value, not the non-encrypted
     * version
     *
     * @var string
     */
    protected $_pass = '';

} // end class User
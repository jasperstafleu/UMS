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
 *         \UMS\Models\User setEmail(string pass)
 *         Set the users password to the string passed
 *
 * @author Jasper Stafleu
 */
class User extends MySQLModel
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

    /**
     * Sets the password. Overrides the default pass setter to first encode it
     * in a safe manner for use in storing in the database.
     *
     * @param string $pass
     */
    public function setPass($pass = '')
    {
        $this->_pass = crypt($pass, '$2y$10$' . self::getRandomSalt());
    } // setPass();

    /**
     * Overriding the pass makes the pass unavailable for accidental retrieval.
     * Use validatePass to check whether the user's pass is valid
     *
     * Of course, print_r and var_dump will still show the pass, but since it
     * should be encoded anyways, the exposure is not that large
     *
     * @return string
     */
    public function getPass()
    {
        return '***';
    } // getPass();

    /**
     * Returns true iff the $pass is the user's pass.
     *
     * @param string $pass
     * @return boolean
     */
    public function isCorrectPass($pass)
    {
        return crypt($pass, $this->_pass) === $this->_pass;
    } // isCorrectPass();

    /**
     * Generates a random salt of $len length using letters from the $alphabet.
     * The default values are set up to be used in a blowfish crypt
     *
     * @param number $len       Default: 22
     * @param string $alphabet  Default: [a-zA-Z0-9]
     */
    public static function getRandomSalt($len = 22, $alphabet = './abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
    {
        $salt = '';
        for ( $it = 0; $it < 22; $it++ ) {
            $salt .= substr($alphabet, mt_rand(0, strlen($alphabet) - 1), 1);
        } // for
        return $salt;
    } // getRandomSalt();

} // end class User
<?php
namespace UMS\Models;

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
<?php
namespace UMS\Models;

/**
 * Login cookie model
 *
 * @method getUser()
 *         integer getUser()
 *         Retrieves the userID for this LoginCookie
 * @method setUser()
 *         \UMS\Models\LoginCookie setUser(integer $userID)
 *         Sets the userID for this LoginCookie
 * @method getHash()
 *         string getHash()
 *         Gets the hash relevant for this login cookie
 * @method setHash()
 *         \UMS\Models\LoginCookie setHash(string $hash)
 *         Sets the hash relevant for this login cookie
 * @author Jasper Stafleu
 */
class LoginCookie extends MySQLModel
{
    /**
     * Name of the cookie to store automatic login information
     *
     * @var string
     */
    const COOKIENAME = 'UMS-Remember';

    /**
     * Reference to the user this logincookie pertains to
     *
     * @var integer
     */
    protected $_user;

    /**
     * The hash value for this cookie.
     *
     * @var string
     */
    protected $_hash;

    /**
     * The value to store in the cookie
     *
     * @var string
     */
    private $_cookieValue;

    /**
     * Constructor. Sets the hash as based on the properties
     *
     * @param array $properties
     */
    public function __construct(array $properties = array())
    {
        parent::__construct($properties);

        if ( empty($this->_hash) ) {
            $this->_cookieValue = json_encode(array(
                    'key' => uniqid('', true),
                    'created' => $this->created . '',
            ));

            $salt = substr(base64_encode(sha1(mt_rand())), 0, 22);

            $this->hash = crypt($this->_cookieValue . $_SERVER['REMOTE_ADDR'], '$2y$10$' . $salt);
        }
    } // __construct();

    /**
     * (non-PHPdoc)
     * @see \UMS\Models\MySQLModel::save()
     */
    public function save()
    {
        parent::save();
        setcookie(self::COOKIENAME, $this->_cookieValue, strtotime('2038-01-01'));
    } // save();

    public function remove()
    {
        parent::remove();
        setcookie(self::COOKIENAME, '', 1); // remove the cookie
    } // remove();

    /**
     * Get the cookie from the DB as based on the contents of the relevant
     * cookie.
     *
     * @return LoginCookie
     */
    public static function getLoginCookie()
    {
        if ( empty($_COOKIE[self::COOKIENAME]) || !($obj = json_decode($_COOKIE[self::COOKIENAME])) ) {
            return null;
        }

        $cookies = self::getAll(array(
                'created' => $obj->created
        ));

        foreach ( $cookies as $cookie ) {
            if ( crypt($_COOKIE[self::COOKIENAME] . $_SERVER['REMOTE_ADDR'], $cookie->hash) === $cookie->hash ) {
                return $cookie;
            }
        } // foreach

        return null;
    } // getLoginCookie();

} // end class UMS\Models\LoginCookie
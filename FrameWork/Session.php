<?php

namespace FrameWork;
class Session
{
    /**
     * Session Age.
     *
     * The number of seconds of inactivity before a session expires.
     *
     * @var integer
     */
    protected static $SESSION_AGE = 18000;

    /**
     * Writes a value to the current session data.
     *
     * @param string $key String identifier.
     * @param mixed $value Single value or array of values to be written.
     * @return mixed Value or array of values written.
     * @throws Exception
     */
    public static function write($key, $value)
    {
        if ( !is_string($key) )
            throw new Exception('Session key must be string value');
        self::_init();
        $_SESSION[$key] = $value;
        self::_age();
        return $value;
    }


    /**
     * Reads a specific value from the current session data.
     *
     * @param string $key String identifier.
     * @param boolean $child Optional child identifier for accessing array elements.
     * @return mixed Returns a string value upon success.  Returns false upon failure.
     * @throws Exception Session key is not a string value.
     */
    public static function read($key, $child = false)
    {
        if ( !is_string($key) )
            throw new Exception('Session key must be string value');
        self::_init();
        if (isset($_SESSION[$key]))
        {
            self::_age();

            if (false == $child)
            {
                return $_SESSION[$key];
            }
            else
            {
                if (isset($_SESSION[$key][$child]))
                {
                    return $_SESSION[$key][$child];
                }
            }
        }
        return false;
    }

    /**
     * Deletes a value from the current session data.
     *
     * @param string $key String identifying the array key to delete.
     * @return void
     * @throws Exception Session key is not a string value.
     */
    public static function delete($key)
    {
        if ( !is_string($key) )
            throw new Exception('Session key must be string value');
        self::_init();
        unset($_SESSION[$key]);
        self::_age();
    }

    /**
     * Expires a session if it has been inactive for a specified amount of time.
     *
     * @return void
     * @throws Exception() Throws exception when read or write is attempted on an expired session.
     */
    private static function _age()
    {
        $last = isset($_SESSION['LAST_ACTIVE']) ? $_SESSION['LAST_ACTIVE'] : false ;

        if (false !== $last && (time() - $last > self::$SESSION_AGE))
        {
            self::destroy();
            throw new Exception();
        }
        $_SESSION['LAST_ACTIVE'] = time();
    }
    /**
     * Starts or resumes a session by calling {@link Session::_init()}.
     *
     * @see Session::_init()
     * @return boolean Returns true upon success and false upon failure.
     * @throws Exception Sessions are disabled.
     */
    public static function start()
    {
        // this function is extraneous
        return self::_init();
    }


    /**
     * Closes the current session and releases session file lock.
     *
     * @return boolean Returns true upon success and false upon failure.
     */
    public static function close()
    {
        if ( '' !== session_id() )
        {
            return session_write_close();
        }
        return true;
    }


    /**
     * Removes session data and destroys the current session.
     *
     * @return void
     */
    public static function destroy()
    {
        if ( '' !== session_id() )
        {
            $_SESSION = array();

            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            session_destroy();
        }
    }

    /**
     * Initializes a new secure session or resumes an existing session.
     *
     * @return void
     */
    private static function _init()
    {
        if ( '' === session_id() ) {
            $secure = false;
            $httponly = false;
            $params = session_get_cookie_params();
            session_set_cookie_params($params['lifetime'],
                $params['path'], $params['domain'],
                $secure, $httponly
            );

            session_start();
        }
    }

}
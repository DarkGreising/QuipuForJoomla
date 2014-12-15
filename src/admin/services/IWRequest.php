<?php

/**
 * Description of IWRequest
 *
 * @author nacho
 */
class IWRequest
{

    /**
     * Gets the full request path.
     *
     * @return  string
     *
     */
    public static function getURI()
    {
        $uri = JUri::getInstance();
        return $uri->toString(array('path', 'query'));
    }

    /**
     * Gets the request method.
     *
     * @return  string
     *
     */
    public static function getMethod()
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        return $method;
    }

    /**
     *
     * @param   string   $name     Variable name.
     * @param   string   $default  Default value if the variable does not exist.
     * @param   string   $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
     * @param   string   $type     Return type for the variable, for valid values see {@link JFilterInput::clean()}.
     * @param   integer  $mask     Filter mask for the variable.
     *
     * @return  mixed  Requested variable.
     *
     */
    public static function getVar($name, $default = null, $hash = 'default', $type = 'none', $mask = 0)
    {
        if ($hash == 'files')
        {
            return JFactory::getApplication()->input->files->get($name, $default);
        }
        return JFactory::getApplication()->input->get($name, $default, $type);
    }

    /**
     * @param   string  $name     Variable name.
     * @param   string  $default  Default value if the variable does not exist.
     * @param   string  $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
     *
     * @return  integer  Requested variable.

     */
    public static function getInt($name, $default = 0, $hash = 'default')
    {
        return JFactory::getApplication()->input->getInt($name, $default);
    }

    /**
     * @param   string  $name     Variable name.
     * @param   string  $default  Default value if the variable does not exist.
     * @param   string  $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
     *
     * @return  integer  Requested variable.
     *
     */
    public static function getUInt($name, $default = 0, $hash = 'default')
    {
        return JFactory::getApplication()->input->getUint($name, $default);
    }

    /**
     *
     * @param   string  $name     Variable name.
     * @param   string  $default  Default value if the variable does not exist.
     * @param   string  $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
     *
     * @return  float  Requested variable.
     */
    public static function getFloat($name, $default = 0.0, $hash = 'default')
    {
        return JFactory::getApplication()->input->getFloat($name, $default);
    }

    /**
     *
     * @param   string  $name     Variable name.
     * @param   string  $default  Default value if the variable does not exist.
     * @param   string  $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
     *
     * @return  boolean  Requested variable.
     */
    public static function getBool($name, $default = false, $hash = 'default')
    {
        return JFactory::getApplication()->input->getBool($name, $default);
    }

    /**
     *
     * @param   string  $name     Variable name.
     * @param   string  $default  Default value if the variable does not exist.
     * @param   string  $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD).
     *
     * @return  string  Requested variable.
     */
    public static function getWord($name, $default = '', $hash = 'default')
    {
        return JFactory::getApplication()->input->getWord($name, $default);
    }

    /**
     * @param   string  $name     Variable name
     * @param   string  $default  Default value if the variable does not exist
     * @param   string  $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     *
     * @return  string  Requested variable
     */
    public static function getCmd($name, $default = '', $hash = 'default')
    {
        return JFactory::getApplication()->input->getCmd($name, $default);
    }

    /**
     * @param   string   $name     Variable name
     * @param   string   $default  Default value if the variable does not exist
     * @param   string   $hash     Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
     * @param   integer  $mask     Filter mask for the variable
     *
     * @return  string   Requested variable
     */
    public static function getString($name, $default = '', $hash = 'default', $mask = 0)
    {
        return JFactory::getApplication()->input->getString($name, $default);
    }

    /**
     * Set a variable in one of the request variables.
     *
     * @param   string   $name       Name
     * @param   string   $value      Value
     * @param   string   $hash       Hash
     * @param   boolean  $overwrite  Boolean
     *
     * @return  string   Previous value
     *
     * @since   11.1
     *
     * @deprecated   12.1
     */
    public static function setVar($name, $value = null, $hash = 'method', $overwrite = true)
    {
        /* @var $input JInput */
        $input = JFactory::getApplication()->input;

        if (is_array($value))
        {
            $old = $input->get($name, array(), 'ARRAY');
            
        } else
        {
            $old = $input->get($name);
        }
        
        $input->set($name, $value);

        return $old;
    }

    /**
     * Sets a request variable.
     *
     * @param   array    $array      An associative array of key-value pairs.
     * @param   string   $hash       The request variable to set (POST, GET, FILES, METHOD).
     * @param   boolean  $overwrite  If true and an existing key is found, the value is overwritten, otherwise it is ignored.
     *
     * @return  void
     *
     * @deprecated  12.1  Use JInput::set()
     * @see         JInput::set()
     * @since       11.1
     */
    public static function set($array, $hash = 'default', $overwrite = true)
    {
        foreach ($array as $key => $value)
        {
            self::setVar($key, $value, $hash, $overwrite);
        }
    }

    /**
     * Checks for a form token in the request.
     *
     * Use in conjunction with JHtml::_('form.token').
     *
     * @param   string  $method  The request method in which to look for the token key.
     *
     * @return  boolean  True if found and valid, false otherwise.
     *
     * @deprecated  12.1 Use JSession::checkToken() instead. Note that 'default' has to become 'request'.
     * @since       11.1
     */
    public static function checkToken($method = 'post')
    {
        if ($method == 'default')
        {
            $method = 'request';
        }

        return JSession::checkToken($method);
    }

}

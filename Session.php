<?php
/**
 * Session.-
 * 
 * PHP Version 5.4.3.-
 * 
 * @package user
 * @author Tomás Juárez <tomirammstein@gmail.com>
 * @copyright 2012 PotstMe
 * @license PHP Version 3.0 { @link http://www.php.net/license/3_0.txt }
 * @link http://github/tomirammstein
 * 
 */
class Session 
{
  const KEYSESSION = 'UserSession';
  const KEYCOOKIE  = 'UserCookie';
  const EXPIRE     = 31536000;
  const PATH       = '/';
  const DOMAIN     = null;
  const SECURE     = false;
  const HTTP       = true;
  private static $_cookieEnabled = true;
  private static $user;
  private static $connection = null;
  
  /**
   * @access private ~ To not allow the new statement.-
   *  
   */
  
  private function __construct()
  {
    //BLANK
  }
  
  /**
   * Clone isn't allowed, because only one instance can exist.-
   * @access public.-
   * @throws Exception.- 
   */
  
  public function __clone()
  {
    throw new Exception( __FUNCTION__.' Not allowed.-' );
  }
  
  /**
   * Singleton method.-
   * @access public.-
   * @return Object.- 
   */
  
  public static function connect()
  {
    if ( is_null ( self::$connection ) ){
      return self::$connection = new Session;
    }
    return self::$connection;
  }
  
  /**
   * Create Cookies.-
   * @access private.-
   * @return boolean.-
   * @throws Exception.- 
   */
  
  private function _setCookies()
  {
    if ( self::$_cookieEnabled === false ) {
      return null;
    }
    try {
      $setCookie = false;
      ob_start();
      $setCookie = setcookie( self::KEYCOOKIE, self::$user, self::EXPIRE, self::PATH, self::DOMAIN, self::SECURE, self::HTTP );
      if ( $setCookie === true ) {
        $_COOKIE [ self::KEYCOOKIE ] = self::$user;
      }
      ob_end_flush();
      return $setCookie;
    }
    catch ( Exception $e ) {
      throw new Exception ( $e->getMessage() );
    }
  }
  
  /**
   * Delete cookies.-
   * @access private.-
   * @return boolean.-
   */
  
  private function _unsetCookies()
  {
    $unsetCookie = false;
    ob_start();
    $unsetCookie = setcookie( self::KEYCOOKIE, self::$user, time() - 3600 , self::PATH );
    if ( $unsetCookie === true ) {
      unset($_COOKIE [ self::KEYCOOKIE ]);
    }
    ob_end_flush();
    return $unsetCookie;
  }
  
  /**
   * Create Session & if needed, create Cookies.-
   * @access public.-
   * @param User $user.-
   * @param boolean $keepAlive.-
   * @return boolean.-
   * @throws Exception.- 
   */
  
  public function run($user, $keepAlive = false)
  {
    try {
      session_start();
      self::$user = $user;
      if ( $keepAlive === true ) {
        $this->_setCookies();
      }
      if ( isset ( $COOKIE [ self::KEYCOOKIE ] ) ) {
        if ( ! ( isset ( $_SESSION [ self::KEYSESSION ] ) ) ) {
          return $_SESSION [ self::KEYSESSION ] = $_COOKIE [ self::KEYCOOKIE ];
        }
      }
      return $_SESSION [ self::KEYSESSION ] = self::$user;
    }
    catch ( Exception $e ) {
      throw new Exception( $e->getMessage() );
    }
  }
  
  /**
   * Destroy Cookies & Sessions.-
   * @access public.-
   */
  
  public function destroy()
  {
    @session_start();
    session_unset();
    session_destroy();
    $this->_unsetCookies();
  }
  
  public function getState( $state = null, $theme )
  {
    if ( ! ( is_null ( $state ) ) && is_string ( $state ) ) {
      $value = null;
      switch ( $theme ) {
        case 'session' :
          $value = isset ( $_SESSION [ self::KEYSESSION ] );
        break;
        case 'cookie' :
          $value = isset ( $_COOKIE [ self::KEYCOOKIE ] );
        break;
        default :
          $value = null;
        break;
      }
      return $value;
    }
  }
}
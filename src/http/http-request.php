<?php

namespace SimFwk2\Http;

/**
 * Http request handler
 * 
 * @author Simon Cabos
 * @version 1.1.2
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
final class Request {

  use \SimFwk2\Factory\Singleton;

  /** @var string[] HTTP GET variables. */
  public $get;

  /** @var string[] HTTP POST variables. */
  public $post;

  /** @var mixed[] Session variables. */
  public $session;

  /** @var mixed[] File upload variables. */
  private $files;

  /** @var string[] HTTP cookies. */
  private $cookie;

  /** @var mixed[] Server and runtime variables. */
  private $server;

  private function __construct () {
    $this->get     =& $_GET;
    $this->post    =& $_POST;
    $this->session =& $_SESSION;
    $this->files   = $_FILES;
    $this->cookie  = $_COOKIE;
    $this->server  = $_SERVER;
  }

  /**
   * Returns the current request's uri.
   * @return string Returns the request's uri.
   */
  public static function uri (): string {
    return $_SERVER['REQUEST_URI'];
  }

  /**
   * Returns the requested HTTP GET variable if argument is given
   * otherwise returns the array of HTTP GET variables.
   * @param string $key (optional) The name of the requested variable.
   * @return string|string[] Returns the HTTP GET variable(s).
   */
  public function dataGet (string $key = null) {
    return array_key_exists($key, $this->get)
           ? $this->get[$key]
           : (is_null($key) ? $this->get : null);
  }

  /**
   * Returns the requested HTTP POST variable if argument is given
   * otherwise returns the array of HTTP POST variables.
   * @param string $key (optional) The name of the requested variable.
   * @return string|string[] Returns the HTTP POST variable(s).
   */
  public function dataPost (string $key = null) {
    return array_key_exists($key, $this->post)
           ? $this->post[$key]
           : (is_null($key) ? $this->post : null);
  }

  /**
   * Returns the requested session variable if argument is given
   * otherwise returns the array of session variables.
   * @param string $key (optional) The name of the requested variable.
   * @return mixed|mixed[] Returns the session variable(s).
   */
  public function dataSession (string $key = null) {
    return array_key_exists($key, $this->session)
           ? $this->session[$key]
           : (is_null($key) ? $this->session : null);
  }

  /**
   * Returns the requested file upload variable if argument is given
   * otherwise returns the array of file upload variables.
   * @param string $key (optional) The name of the requested variable.
   * @return mixed|mixed[] Returns the file upload variable(s).
   */
  public function dataFiles (string $key = null) {
    return array_key_exists($key, $this->files)
           ? $this->files[$key]
           : (is_null($key) ? $this->files : null);
  }

  /**
   * Returns the requested HTTP cookie if argument is given
   * otherwise returns the array of HTTP cookies.
   * @param string $key (optional) The name of the requested cookie.
   * @return string|string[] Returns the HTTP cookie(s).
   */
  public function dataCookie (string $key = null) {
    return array_key_exists($key, $this->cookie)
           ? $this->cookie[$key]
           : (is_null($key) ? $this->cookie : null);
  }

  /**
   * Returns the requested server variable if argument is given
   * otherwise returns the array of server variables.
   * @param string $key (optional) The name of the requested variable.
   * @return mixed|mixed[] Returns the server variable(s).
   */
  public function dataServer (string $key = null) {
    return array_key_exists($key, $this->server)
           ? $this->server[$key]
           : (is_null($key) ? $this->server : null);
  }
}

<?php

namespace SimFwk2\Http;

/**
 * Http response handler
 * 
 * @author Simon Cabos
 * @version 1.1.2
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
final class Response {

  use \SimFwk2\Factory\Singleton;

  /** @var string The current request's uri. */
  private $requestUri;

  /**
   * @param string $requestUri The current request's uri.
   */
  private function __construct(string $requestUri) {
    $this->requestUri = $requestUri;
  }

  /**
   * Send an HTTP header.
   * @param string $header The HTTP header.
   */
  public static function sendHeader(string $header): void {
    header($header);
  }

  /**
   * Make a redirection.
   * @param string $location The url to redirect to.
   * @param boolean $permanent (optional) If set to TRUE, make a permanent redirection.
   */
  public static function redirect(string $location, bool $permanent = false): void {
    if ($permanent)
      header("Status: 301 Moved Permanently", false, 301);
    header("location: $location");
    exit;
  }

  /**
   * Reload the current uri.
   */
  public function reload(): void {
    $this->redirect($this->requestUri);
  }

  /**
   * Send the HTML response.
   * @param string $html The html content to send.
   */
  public static function send(string $html): void {
    exit($html);
  }

  /**
   * Set a cookie.
   * @param string $name The name of the cookie.
   * @param string $value (optional) The value of the cookie.
   * @param integer $expire (optional) The time after which the cookie expires in seconds.
   */
  public static function setCookie(
    string $name,
    string $value = "",
    int $expires = 0
  ): void {
    setcookie($name, $value, time() + $expires, null, null, false, true);
  }

  /**
   * Set a session variable.
   * @param string $name The name of the variable.
   * @param mixed $value (optional) The value of the session.
   */
  public static function setSession(string $name, $value = null): void {
    $_SESSION[$name] = $value;
  }
}

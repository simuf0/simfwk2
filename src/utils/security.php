<?php

namespace SimFwk2\Utils;

/**
 * Security's functions handler
 * 
 * @author Simon Cabos
 * @version 1.2.0
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
final class Security {

  use \SimFwk2\Factory\Singleton, \SimFwk2\Factory\Thrower;

  /** @var string The initialization vector. */
  private $iv;

  private function __construct() {
    try {
      $this->setIv();
    } catch (SecurityException $e) {
      exit($e->getMessage());
    }
  }

  /**
   * Generates an initialization vector for AES encryption method.
   * @return string The initialization vector.
   */
  public static function iv(): string {
    $length = openssl_cipher_iv_length("aes-256-cbc");
    return openssl_random_pseudo_bytes($length);
  }

  /**
   * Encrypts data using AES algorithm.
   * @param string $data The data to encrypt.
   * @param string $key The key used to encrypt data.
   * @return string The encrypted data.
   */
  public function encryptAES(string $data, string $key) : string {
    return openssl_encrypt($data, "aes-256-cbc", $key, false, $this->iv);
  }

  /**
   * Decrypts data using AES algorithm.
   * @param string $data The data to decrypt.
   * @param string $key The key used to decrypt data.
   * @return string The decrypted data.
   */
  public function decryptAES(string $data, string $key): string {
    return openssl_decrypt($data, "aes-256-cbc", $key, false, $this->iv);
  }

  /**
   * Generates an authentication token.
   * @return string The autentication token.
   */
  public function token(): string {
    $data = openssl_random_pseudo_bytes(32);
    $key = openssl_random_pseudo_bytes(32);
    return $this->encryptAES($data, $key);
  }
  
  /**
   * Assign the iv property from session.
   * @throws \SimFwk2\Utils\SecurityException When the iv's session is missing.
   */
  private function setIv(): void {
    $request = \SimFwk2\Http\Request::getInstance();
    if (!$iv = $request->dataSession("security.iv")) {
      $this->throw("E_MISSING_IV");
    }
    $this->iv = $iv;
  }
}

/**
 * Security exception class
 *
 * @author Simon Cabos
 * @version 1.0.0
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
class SecurityException extends \LogicException {
  const E_MISSING_IV = "Failed instanciate security class : missing session `security.iv`";
}

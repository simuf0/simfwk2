<?php

namespace SimFwk2\Utils;

/**
 * Security's functions handler
 * 
 * @author Simon Cabos
 * @version 1.1.1
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
final class Security {

  use \SimFwk2\Factory\Singleton;

  /** @var string The initialization vector. */
  private $iv;

  private function __construct () {
    $request = \SimFwk2\Http\Request::getInstance();
    $this->iv = $request->dataSession("security.iv");
  }

  /**
   * Generates an initialization vector for AES encryption method.
   * @return string The initialization vector.
   */
  public static function iv (): string {
    $length = openssl_cipher_iv_length("aes-256-cbc");
    return openssl_random_pseudo_bytes($length);
  }

  /**
   * Encrypts data using AES algorithm.
   * @param string $data The data to encrypt.
   * @param string $key The key used to encrypt data.
   * @return string The encrypted data.
   */
  public function encryptAES (string $data, string $key) : string {
    return openssl_encrypt($data, "aes-256-cbc", $key, false, $this->iv);
  }

  /**
   * Decrypts data using AES algorithm.
   * @param string $data The data to decrypt.
   * @param string $key The key used to decrypt data.
   * @return string The decrypted data.
   */
  public function decryptAES (string $data, string $key): string {
    return openssl_decrypt($data, "aes-256-cbc", $key, false, $this->iv);
  }

  /**
   * Generates an authentication token.
   * @return string The autentication token.
   */
  public function token (): string {
    $data = openssl_random_pseudo_bytes(32);
    $key = openssl_random_pseudo_bytes(32);
    return $this->encryptAES($data, $key);
  }
}

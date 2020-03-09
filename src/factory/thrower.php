<?php

namespace SimFwk2\Factory;

/**
 * Throws an exception.
 *
 * @author Simon Cabos
 * @version 1.0.2
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
trait Thrower {

  /**
   * Throws an exception. The exception classname must match to the current classname.
   * @param string $message The exception's message.
   * @param string[] $args (optional) The arguments passed to the string conversion.
   */
  final protected function throw (string $message, ...$args): void {
    $exception = self::class . "Exception";
    if (!class_exists($exception)) {
      $msg = "Failed throwing exception : missing class `%s`";
      throw new \LogicException(sprintf($msg, $exception));
    }
    if (!@constant("$exception::$message")) {
      $msg = "Failed throwing exception : missing constant `%s`";
      throw new \LogicException(sprintf($msg, "$exception::$message"));
    }
    $message = constant("$exception::$message");
    throw new $exception(sprintf($message, ...$args));
  }
}
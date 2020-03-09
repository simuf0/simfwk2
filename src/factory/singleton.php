<?php

namespace SimFwk2\Factory;

/**
 * Defines a singleton class
 * 
 * @author Simon Cabos
 * @version 1.1.1
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
trait Singleton {

  /** @var mixed Instance of the class. */
  private static $instance;

  /** Unset the __clone method. */
  final private function __clone () {}

  /** Unset the __wakeup method. */
  final private function __wakeup() {}

  /** Make the constructor private. */
  private function __construct () {}

  /**
   * Instanciate the class if not exists and return the instance.
   * @param mixed[] ...$args (optional) The arguments given to the class constructor.
   * @return mixed Returns the class's instance.
   */
  final public static function getInstance (...$args): self {
    if (!isset(self::$instance)) {
      self::$instance = new static(...$args);
    }
    return self::$instance;
  }
}
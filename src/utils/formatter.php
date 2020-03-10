<?php

namespace SimFwk2\Utils;

/**
 * String formatting handler
 * 
 * @author Simon Cabos
 * @version 1.1.2
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
final class Formatter {

  use \SimFwk2\Factory\Singleton;

  /**
   * Format a string to a classname.
   * @param string $str The string to format.
   * @return string The formatted string.
   */
  final public function toClassName(string $str): string {
    $flag = false;
    $newStr = "";
    $str = trim(preg_replace("/[^\w]/", " ", $str));
    for ($i = 0; $i < strlen($str); $i++) {
      if ($str[$i] == " ") {
        $flag = true;
      } else {
        if ($flag) {
          $newStr .= strtoupper($str[$i]);
          $flag = false;
        } else {
          $newStr .= $str[$i];
        }
      }
    }
    return ucfirst($newStr);
  }

  /**
   * Format a string to a method name.
   * @param string $str The string to format.
   * @return string The formatted string.
   */
  final public function toMethodName(string $str): string {
    return lcfirst(self::toClassName($str));
  }

  /**
   * Format a string to a filename.
   * @param string $str The string to format.
   * @param string $separator (optional) The character to use for separate words.
   * @return string The formatted string.
   */
  final public function toFilename(
    string $str,
    string $separator = "-"
  ): string {
    $newStr = "";
    $str = trim(preg_replace("/[^\w]/", $separator, $str), $separator);
    for ($i = 0; $i < strlen($str); $i++) {
      if (!($str[$i] == "-" && $str[$i + 1] == "-")) {
        $newStr .= $str[$i];
      }
    }
    return strtolower($newStr);
  }
}

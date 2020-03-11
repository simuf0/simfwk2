<?php

namespace SimFwk2\Utils;

/**
 * Annotations handler.
 * 
 * @author Simon Cabos
 * @version 1.0.0
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
final class Annotation {

  /** @var mixed[][] Class's properties annotations. */
  private $annotations;

  /**
   * @param mixed $class Class whose annotations are retrieved.
   */
  public function __construct($class) {
    $reflection = new \ReflectionClass($class);
    foreach ($reflection->getProperties() as $property) {
      $annotation = $this->parse($property->getDocComment());
      $this->annotations[$property->name] = $annotation;
    }
  }

  /**
   * Returns the class's properties annotations.
   * @return mixed[][] The class's properties annotations.
   */
  public function get(): array {
    return $this->annotations;
  }

  /**
   * Parse the property's annotations.
   * @param string $docComment The property's comment.
   * @return mixed[] Returns the parsed propoerty's annotations.
   */
  private function parse(string $docComment): array {
    $p = "/@([a-zA-Z]*)\(([^\)]*)\)/";
    preg_match_all($p, $docComment, $matches);
    foreach ($matches[1] as $i => $key) {
      $value = explode(",", $matches[2][$i]);
      $data[$key] = array_map([$this, "formatValue"], $value);
    }
    return $data;
  }

  /**
   * Format the annotation's value.
   * @param string $value The value to format.
   * @return string|int Returns the formated value.
   */
  private function formatValue (string $value) {
    $value = trim($value);
    if ($value[0] == "\"" && $value[strlen($value) - 1] == "\"") {
      return substr($value, 1, strlen($value) - 2);
    } else {
      return (int) $value;
    }
  }
}
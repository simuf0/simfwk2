<?php

namespace SimFwk2\Db;

/**
 * Defines a model object.
 * 
 * @author Simon Cabos
 * @version 1.0.0
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
abstract class Model {

  use \SimFwk2\Factory\Thrower;

  /**
   * @Type("int")
   * @var int The model id.
   */
  protected $id;
  
  /**
   * @param string[] $data The raw data to hydrate the model.
   */
  final public function __construct(array $data) {
    foreach ($data as $prop => $value) {
      if (property_exists($this, $prop)) {
        $this->$prop = $this->formatValue($prop, $value);
      }
    }
  }

  /**
   * Return the requested object property.
   * @param string $prop The property name.
   * @return int|bool|string Returns the property value.
   */
  final public function __get(string $prop) {
    if (!property_exists($this, $prop)) {
       $this->throw("E_MISSING_PROPERTY", $prop);
    }
    return $this->$prop;
  }

  /**
   * Format the requested property value with the property's annotation type.
   * @param string $prop The name of property to format.
   * @param string $value The property's value.
   * @return int|bool|string Returns the property value.
   */
  private function formatValue(string $prop, ?string $value) {
    $annotations = (new \SimFwk2\Utils\Annotation($this))->get();
    switch ($annotations[$prop]['Type'][0]) {
      case "int":
        return (int) $value;
        break;
      case "bool":
        return (bool) $value;
        break;
      default:
      return $value;
        break;
    }
  }
}

/**
 * Model exception class.
 *
 * @author Simon Cabos
 * @version 1.0.0
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
class ModelException extends \LogicException {
  const E_MISSING_PROPERTY = "Failed accessing property : missing property `%s`";
}

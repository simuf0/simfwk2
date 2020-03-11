<?php

namespace SimFwk2\Core;

/**
 * View rendering handler.
 * 
 * @author Simon Cabos
 * @version 1.0.1
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
final class View {

  use \SimFwk2\Factory\Thrower;

  /** @var string The view file to be rendered. */
  private $filename;

  /** @var mixed[] The variables passed to the view file. */
  private $vars = [];

  /**
   * @param string $filename The view file to be rendered.
   * @throws \SimFwk2\Core\ViewException When filename is missing.
   */
  public function __construct(string $filename) {
    if (!is_file($filename)) {
      $this->throw("E_MISSING_FILE", $filename);
    }
    $this->filename = $filename;
  }

  /**
   * Append a new variable.
   * @param string $name The variable's name.
   * @param mixed $value The variable's value.
   */
  public function addVar(string $name, $value): void {
    $this->vars[$name] = $value;
  }

  /**
   * Append multiple variables.
   * @param mixed[] $vars The variable's array to append.
   */
  public function addVars(array $vars): void {
    $this->vars = array_merge($this->vars, $vars);
  }

  /**
   * Render and returns the view.
   * @return string The html content.
   */
  public function render() : string {
    extract($this->vars);
    ob_start();
    require $this->filename;
    return ob_get_clean();
  }
}

/**
 * View exception class.
 *
 * @author Simon Cabos
 * @version 1.0.0
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
class ViewException extends \LogicException {
  const E_MISSING_FILE = "Failed initializing view : missing file `%s`";
}
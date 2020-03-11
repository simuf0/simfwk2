<?php

namespace SimFwk2\Core;

/**
 * Defines a controller class.
 * 
 * @author Simon Cabos
 * @version 1.0.2
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
abstract class Controller extends ApplicationComponent {

  use \SimFwk2\Factory\Thrower;

  /**
   * @param \SimFwk2\Core\Application The application's instance.
   */
  public function __construct(Application $app) {
    parent::__construct($app);
  }

  /**
   * Test if an action exists.
   * @param string $name The action's name.
   * @return boolean Returns TRUE if the action exists, returns FALSE otherwise.
   */
  public function existsAction (string $name): bool {
    $f = \SimFwk2\Utils\Formatter::getInstance();
    $action = $f->toMethodName("execute-$name");
    return is_callable([$this, $action]);
  }

  /**
   * Test if a view exists.
   * @param string $name The view's name.
   * @return boolean Returns TRUE if the view exists, returns FALSE otherwise.
   */
  public function existsView(string $name): bool {
    $file = $this->path . "/views/$name.html";
    return is_file($file);
  }

  /**
   * Execute an action.
   * @param string $action The action's name to execute.
   * @param mixed[] $parameters (optional) The parameters passed to the action.
   * @return mixed[] Returns the data results.
   * @throws \SimFwk2\Core\ControllerException When action is missing.
   */
  public function execute(string $action, ...$parameters): array {
    $f = \SimFwk2\Utils\Formatter::getInstance();
    $action = $f->toMethodName("execute-$action");
    if (!is_callable([$this, $action])) {
      $this->throw("E_MISSING_ACTION", $action);
    }
    return $this->$action(...$parameters);
  }

  /**
   * Render an returns the action's associated view.
   * @param string $view The view's name.
   * @param mixed[] $vars The action's returned variables.
   * @return string Returns the rendered view.
   */
  public function renderView(string $view, array $vars = []): string {
    $file = $this->path."/views/$view.html";
    try {
      $view = new View($file);
    } catch (\SimFwk2\Core\ViewException $th) {
      exit($e->getMessage());
    }
    $view->addVars($vars);
    return $view->render();
  }
}

/**
 * Controller exception class.
 *
 * @author Simon Cabos
 * @version 1.0.0
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
class ControllerException extends \LogicException {
  const E_MISSING_ACTION = "Failed executing action : missing action `%s`";
}
<?php

namespace SimFwk2\Core;

/**
 * Application class.
 * 
 * @author Simon Cabos
 * @version 1.1.0
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
final class Application {

  use \SimFwk2\Factory\Singleton;

  /** @var \SimFwk2\Core\Config The configuration's class instance. */
  public $config;

  /** @var \SimFwk2\Core\Router The router's class instance. */
  public $router;

  /**
   * @param \SimFwk2\Core\Config $config The configuration's class instance.
   * @param \SimFwk2\Core\Router $router The router's class instance.
   */
  private function __construct(Config $config, Router $router) {
    $this->config = $config;
    $this->router = $router;
  }

  /**
   * Returns the specified dao instance.
   * @param string $name The name of the dao to load.
   * @return \SimFwk2\Db\Dao The dao instance.
   * @throws \SimFwk2\Core\ApplicationException When dao is missing.
   */
  public function loadDao(string $name): \SimFwk2\Db\Dao {
    $f = \SimFwk2\Utils\Formatter::getInstance();
    $class = "Dao\\" . $f->toClassName($name);
    if (!class_exists($class)) {
      $this->throw("E_MISSING_DAO", $name);
    }
    return new $class($this);
  }

  /**
   * Returns the specified layout instance.
   * @param string $name The name of the layout to load.
   * @return \SimFwk2\Core\Layout The layout instance.
   * @throws \SimFwk2\Core\ApplicationException When layout is missing.
   */
  public function loadLayout(string $name): Layout {
    $f = \SimFwk2\Utils\Formatter::getInstance();
    $class = "Layouts\\" . $f->toClassName($name);
    if (!class_exists($class)) {
      $this->throw("E_MISSING_LAYOUT", $name);
    }
    return new $class($this);
  }

  /**
   * Returns the specified module instance.
   * @param string $name The name of the module to load.
   * @return \SimFwk2\Core\Controller The module instance.
   * @throws \SimFwk2\Core\ApplicationException When module is missing.
   */
  public function loadModule(string $name, ...$args): Controller {
    $f = \SimFwk2\Utils\Formatter::getInstance();
    $class = "Modules\\" . $f->toClassName($name);
    if (!class_exists($class)) {
      $this->throw("E_MISSING_MODULE", $name);
    }
    return new $class($this, ...$args);
  }

  /**
   * Returns the specified service instance.
   * @param string $name The name of the service to load.
   * @return \SimFwk2\Core\Service The service instance.
   * @throws \SimFwk2\Core\ApplicationException When service is missing.
   */
  public function loadService(string $name): Service {
    $f = \SimFwk2\Utils\Formatter::getInstance();
    $class = "Services\\" . $f->toClassName($name);
    if (!class_exists($class)) {
      $this->throw("E_MISSING_SERVICE", $name);
    }
    return new $class($this);
  }

  /**
   * Run the application.
   * @param string $request The request uri.
   * @param string $dataType (optional) The returned data type.
   */
  public function run(string $request, string $dataType = "html"): void {

    $response = \SimFwk2\Http\Response::getInstance($request);
    $route = $this->router->getRoute($request);

    // If the route has a redirect
    if (isset($route['redirectTo'])) {
      $this->run($route['redirectTo'], $dataType);

    // If the route is valid
    } elseif ($this->router->isValidRoute($route)) {

      $this->initSession();

      try {
        $module = $this->loadModule($route['controller']);
      } catch (ApplicationException $e) {
        exit($e->getMessage());
      }

      // If the action exists
      if ($module->existsAction($route['action'])) {
        $data = $module->execute($route['action'], $route['params']);
      }

      // If the returned data type of the request is `html`
      if ($dataType == "html") {

        // If the view exists
        if ($module->existsView($route['action'])) {
          $view = $module->renderView($route['action'], $data ?? []);
          if ($module instanceof PageController) {
            $view = $module->layout()->renderPage($view);
          }
          $response->send($view);
        }

      // If the returned data type of the request is `json`
      } elseif ($dataType == "json") {
        $response->send(json_encode($data));
      }
    }
  }

  /**
   * Initialize application sessions.
   */
  private function initSession(): void {
    session_start();
    $request = \SimFwk2\Http\Request::getInstance();
    if (!$request->dataSession("security.iv")) {
      \SimFwk2\Http\Response::setSession(
        "security.iv",
        \SimFwk2\Utils\Security::iv());
    }
    $security = \SimFwk2\Utils\Security::getInstance();
    if (!$request->dataSession("security.key-storage")) {
      \SimFwk2\Http\Response::setSession(
        "security.key-storage",
        $security->token());
    }
  }
}

/**
 * Application exception class.
 *
 * @author Simon Cabos
 * @version 1.0.0
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
class ApplicationException extends \LogicException {
  const E_MISSING_DAO = "Failed loading dao : missing dao `%s`";
  const E_MISSING_LAYOUT = "Failed loading layout : missing layout `%s`";
  const E_MISSING_MODULE = "Failed loading module : missing module `%s`";
  const E_MISSING_SERVICE = "Failed loading service : missing service `%s`";
}

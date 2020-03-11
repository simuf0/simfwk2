<?php

namespace SimFwk2\Core;

/**
 * Bootloader class
 *
 * @author Simon Cabos
 * @version 1.2.0
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
final class Bootloader {

  use \SimFwk2\Factory\Singleton, \SimFwk2\Factory\Thrower;
  
  /** @var string The projects relative path. */
  const PROJECTS_PATH = __DIR__ . "/../../projects";

  /**
   * Launch an application.
   * @param  string $appName Name of the application.
   * @throws \SimFwk2\Core\BootloaderException When the application is missing.
   */
  public function launch(string $appName): void {
    if (!$appPath = realpath(self::PROJECTS_PATH . "/$appName")) {
      $this->throw("E_MISSING_APP", $appName);
    }
    try {
      $config = Config::getInstance("$appPath/config.json");
      $router = Router::getInstance("$appPath/routes.json");
    } catch (ConfigException | RouterException $e) {
      exit($e->getMessage());
    }

    require_once realpath($config['paths.vendor'] . "/autoload.php");
    $request = \SimFwk2\Http\Request::uri();
    $app = Application::getInstance($config, $router);
    $app->run($request);
  }
}

/**
 * Bootloader exception class
 *
 * @author Simon Cabos
 * @version 1.0.1
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
class BootloaderException extends \LogicException {
  const E_MISSING_APP = "Failed loading application : missing project `%s`";
}
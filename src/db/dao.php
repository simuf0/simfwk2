<?php

namespace SimFwk2\Db;

/**
 * Database access object.
 * 
 * @author Simon Cabos
 * @version 1.1.0
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
abstract class Dao extends \SimFwk2\Core\ApplicationComponent {

  use \SimFwk2\Factory\Thrower;

  /** @var \SimFwk2\Db\DbHandler The database access handler. */
  protected $dbh;

  /**
   * @param \SimFwk2\Core\Application $app The application instance.
   */
  public function __construct (\SimFwk2\Core\Application $app) {
    parent::__construct($app);
    $this->dbh = DbHandler::getInstance(
      $this->app->config->get("database.host"),
      $this->app->config->get("database.name"),
      $this->app->config->get("database.user"),
      $this->app->config->get("database.password"));
  }

  /**
   * Returns the requested query.
   * @param string $name The query's name.
   * @return string The query's content.
   * @throws \SimFwk2\Db\DaoException When query is missing.
   */
  protected function getQuery (string $name): string {
    $queryFile = $this->path."/sql/$name.sql";
    if (!is_file($queryFile)) {
      $this->throw("E_MISSING_QUERY", $name);
    }
    return file_get_contents($queryFile);
  }

  /**
   * Load and return a model.
   * @param string[] $data The raw data to hydrate the model.
   */
  protected function loadModel (array $data) {
    $f = \SimFwk2\Utils\Formatter::getInstance();
    $class = "Models\\" . $f->toClassName($this->name);
    if (!class_exists($class)) {
      $this->throw("E_MISSING_MODEL", $this->name);
    }
    return new $class($data);
  }
}

/**
 * Database access exception class.
 *
 * @author Simon Cabos
 * @version 1.0.0
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
class DaoException extends \LogicException {
  const E_MISSING_QUERY = "Failed loading query : missing query `%s`";
  const E_MISSING_MODEL = "Failed loading model : missing model `%s`";
}
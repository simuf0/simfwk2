<?php

namespace SimFwk2\Core;

/**
 * Defines a service class.
 * 
 * @author Simon Cabos
 * @version 1.0.2
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
abstract class Service extends ApplicationComponent {

  /** @var \SimFwk2\Db\Dao The dao instance. */
  private $dao; 

  /**
   * @param \SimFwk2\Core\Application The application's instance.
   */
  public function __construct(Application $app) {
    parent::__construct($app);
  }

  /**
   * Set the requested dao instance.
   * @param string $name The dao's name.
   */
  protected function setDao(string $name): void {
    try {
      $this->dao = $this->app->loadDao($name);
    } catch (\ApplicationException $e) {
      exit($e->getMessage());
    }
  }

  /**
   * Returns the dao.
   * @return \SimFwk2\Db\Dao The dao's instance.
   */
  protected function dao(): \SimFwk2\Db\Dao {
    return $this->dao;
  }
}
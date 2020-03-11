<?php

namespace SimFwk2\Core;

/**
 * Defines an application component.
 * 
 * @author Simon Cabos
 * @version 1.0.1
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
abstract class ApplicationComponent {

  /** @var \SimFwk2\Core\Application The application instance. */
  protected $app;

  /** @var string The name of the component. */
  protected $name;

  /** @var string The type of the component. */
  protected $type;

  /** @var string The path of the component. */
  protected $path;
  
  /**
   * @param \SimFwk2\Core\Application The application instance.
   */
  public function __construct(Application $app) {
    $this->app = $app;
    $this->setName();
    $this->setType();
    $this->setPath();
  }

  /**
   * Set the name of the component.
   */
  private function setName(): void {
    $f = \SimFwk2\Utils\Formatter::getInstance();
    $pos = strrpos(get_class($this), "\\") + 1;
    $this->name = $f->toFilename(substr(get_class($this), $pos));
  }

  /**
   * Set the type of the component.
   */
  private function setType(): void {
    $f = \SimFwk2\Utils\Formatter::getInstance();
    $pos = strrpos(get_class($this), "\\");
    $this->type = $f->toFilename(substr(get_class($this), 0, $pos));
  }

  /**
   * Set the absolute path of the component.
   */
  private function setPath(): void {
    $rc = new \ReflectionClass(get_class($this));
    $this->path = dirname($rc->getFileName());
  }
}
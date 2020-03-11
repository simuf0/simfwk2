<?php

namespace SimFwk2\Core;

/**
 * Defines a layout class.
 * 
 * @author Simon Cabos
 * @version 1.0.1
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
abstract class Layout extends Controller {

  /** @var mixed[] The html headers. */
  public $headers;

  /**
   * @param \SimFwk2\Core\Application The application's instance.
   */
  public function __construct(Application $app) {
    parent::__construct($app);
    $this->setHeaders();
  }

  /**
   * Set the html headers.
   */
  private function setHeaders(): void {
    $filename = $this->path."/headers.json";
    try {
      $fh = new \SimFwk2\File\JSonFileHandler($filename);
    } catch (\SimFwk2\File\FileHandlerException $e) {
      exit($e->getMessage());
    }
    $this->headers = $fh->readJson();
  }

  /**
   * Render an returns the page view.
   * @param string $view The view's name.
   * @return string Returns the rendered page view.
   */
  abstract public function renderPage (string $view): string;
}
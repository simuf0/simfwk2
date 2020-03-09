<?php

namespace SimFwk2\File;

/**
 * File handler
 * 
 * @author Simon Cabos
 * @version 1.1.1
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
class FileHandler {
  
  use \SimFwk2\Factory\Thrower;
  
  /** @var \SplFileObject The file object. */
  protected $file;

  /**
   * @param string $filename The file's path to handle.
   * @param string $mode (boolean) The type of file's access.
   */
  public function __construct (string $filename, string $mode = "r") {
    $this->open($filename, $mode);
  }

  /**
   * Test if a file exists.
   * @param string $filename The file's path to test.
   * @return boolean returns TRUE if the file exists, returns FALSE otherwise.
   */
  final public static function exists (string $filename) : bool {
    return is_file($filename);
  }

  /**
   * Open a file.
   * @param string $filename The file's path to open.
   * @param string $mode (boolean) The type of file's access.
   * @throws SimFwk2\File\FileHandlerException When the file is missing.
   */
  final public function open (string $filename, string $mode = "r"): void {
    if (!self::exists($filename)) {
      $this->throw("E_MISSING_FILE", $filename);
    }
    $this->file = new \SplFileObject($filename, $mode);
  }

  /**
   * Returns the content of the file. File must be opened before.
   * @return string The file's content.
   */
  public function read (): string {
    return $this->file->fread($this->file->getSize());
  }

  /**
   * Write content into a file.
   * @param string $content The content to write.
   * @param string $eol (boolean) The end of line string to use.
   */
  public function write (string $content, string $eol = \PHP_EOL): void {
    $this->file->flock(LOCK_EX);
    $this->file->fwrite($content . $eol);
    $this->file->flock(LOCK_UN);
  }

  /**
   * Close the current opened file.
   */
  final public function close (): void {
    $this->file = null;
  }
}

/**
 * FileHandler exception class
 *
 * @author Simon Cabos
 * @version 1.0.0
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
class FileHandlerException extends \LogicException {
  const E_MISSING_FILE = "Failed opening file : missing file `%s`";
}

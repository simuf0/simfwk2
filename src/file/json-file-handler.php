<?php

namespace SimFwk2\File;

/**
 * Json file handler
 * 
 * @author Simon Cabos
 * @version 1.0.5
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
final class JsonFileHandler extends FileHandler {
  
  /**
   * Returns the content of the json file. File must be opened before.
   * @return mixed[] The decoded json file's content.
   */
  public function readJson() {
    return json_decode(parent::read(), true);
  }

  /**
   * Write content into a json file.
   * @param mixed $content The json's content to write.
   */
  public function writeJson($content): void {
    parent::write(json_encode($content));
  }
}

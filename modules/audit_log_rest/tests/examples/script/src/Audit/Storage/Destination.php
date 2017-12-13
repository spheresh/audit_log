<?php

namespace Audit\Storage;

/**
 * Destination class.
 */
class Destination {

  /**
   * Storing csv.
   *
   * @TODO Add a congif file name/place.
   */
  public static function putContent($content) {
    file_put_contents('logs.txt', $content, FILE_APPEND | LOCK_EX);
  }

}

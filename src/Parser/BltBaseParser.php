<?php

namespace Hedron\BLT\Parser;

use Hedron\GitPostReceiveHandler;
use Hedron\Parser\BaseParser;
use Symfony\Component\Console\Exception\RuntimeException;

abstract class BltBaseParser extends BaseParser {

  protected function doParse(GitPostReceiveHandler $handler) {
    $gitDir = $this->getGitDirectoryPath();
    $applicationDir = $gitDir . DIRECTORY_SEPARATOR . 'application';
    if (!file_exists($applicationDir) || !is_dir($applicationDir)) {
      throw new RuntimeException("The project is missing an \"application\" directory.");
    }
    $parse = FALSE;
    foreach ($handler->getCommittedFiles() as $file_name) {
      if (strpos($file_name, 'application/') === 0) {
        $parse = TRUE;
        break;
      }
    }
    return $parse;
  }
}
<?php

namespace Hedron\BLT\Parser;

use Hedron\Command\CommandStackInterface;
use Hedron\GitPostReceiveHandler;

/**
 * @Hedron\Annotation\Parser(
 *   pluginId = "blt_phpunit"
 * )
 */
class BltPHPUnit extends BltBaseParser {

  /**
   * {@inheritdoc}
   */
  public function parse(GitPostReceiveHandler $handler, CommandStackInterface $commandStack) {
    if ($this->doParse($handler) && $this->getFileSystem()->exists($this->getDataDirectoryPath() . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'phpunit')) {
      $commandStack->addCommand("cd {$this->getDataDirectoryPath()}");
      $commandStack->addCommand("./vendor/bin/phpunit");
      $commandStack->execute();
    }
  }

}

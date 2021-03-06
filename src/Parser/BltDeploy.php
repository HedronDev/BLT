<?php

namespace Hedron\BLT\Parser;

use Hedron\Command\CommandStackInterface;
use Hedron\GitPostReceiveHandler;

/**
 * @Hedron\Annotation\Parser(
 *   pluginId = "blt_deploy"
 * )
 */
class BltDeploy extends BltBaseParser {

  /**
   * {@inheritdoc}
   */
  public function parse(GitPostReceiveHandler $handler, CommandStackInterface $commandStack) {
    if ($this->doParse($handler)) {
      $commandStack->addCommand("cd {$this->getDataDirectoryPath()}");
      $commandStack->addCommand("./vendor/bin/blt deploy:build -Ddeploy.dir={$this->getDataDirectoryPath()}");
      $commandStack->execute();
    }
  }

}

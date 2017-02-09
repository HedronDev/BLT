<?php

namespace Hedron\BLT\Parser;

use Hedron\Command\CommandStackInterface;
use Hedron\GitPostReceiveHandler;
use Hedron\Parser\BaseParser;

/**
 * @Hedron\Annotation\Parser(
 *   pluginId = "blt_deploy"
 * )
 */
class BltDeploy extends BaseParser {

  /**
   * {@inheritdoc}
   */
  public function parse(GitPostReceiveHandler $handler, CommandStackInterface $commandStack) {
    $gitDir = $this->getGitDirectoryPath();
    $applicationDir = $gitDir . DIRECTORY_SEPARATOR . 'application';
    if (!file_exists($applicationDir) || !is_dir($applicationDir)) {
      throw new \Exception("The project is missing an \"application\" directory.");
    }
    $parse = FALSE;
    foreach ($handler->getCommittedFiles() as $file_name) {
      if (strpos($file_name, 'application/') === 0) {
        $parse = TRUE;
        break;
      }
    }
    if ($parse) {
      $blt_dir = $this->getDataDirectoryPath() . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'bin';
      $commandStack->addCommand("cd $blt_dir");
      $commandStack->addCommand("./blt deploy:build -Ddeploy.dir={$this->getDataDirectoryPath()}");
      $commandStack->execute();
    }
  }

}

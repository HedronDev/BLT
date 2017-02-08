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
      $user_directory = trim(shell_exec("cd ~; pwd"));
      $hedron_directory = $user_directory . DIRECTORY_SEPARATOR . '.hedron';
      $blt = $hedron_directory . DIRECTORY_SEPARATOR . 'blt' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'blt';
      if (!$this->getFileSystem()->exists($hedron_directory . DIRECTORY_SEPARATOR . 'blt')) {
        $blt_dir = $hedron_directory . DIRECTORY_SEPARATOR . 'blt';
        $commandStack->addCommand("mkdir -p $blt_dir");
        $commandStack->addCommand("cd $blt_dir");
        $commandStack->addCommand("composer create-project --no-interaction acquia/blt .");
      }

      if (!$this->getFileSystem()->exists($this->getDataDirectoryPath() . DIRECTORY_SEPARATOR . 'docroot')) {
        $commandStack->addCommand("cd {$this->getDataDirectoryPath()}");
        $commandStack->addCommand("composer create-project --no-interaction acquia/blt-project .");
      }
      $commandStack->addCommand("./$blt deploy:build -Ddeploy.dir={$this->getDataDirectoryPath()}");
      $commandStack->execute();
    }
  }

}

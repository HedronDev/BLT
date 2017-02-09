<?php

namespace Hedron\BLT\Parser;

use Hedron\Command\CommandStackInterface;
use Hedron\GitPostReceiveHandler;
use Hedron\Parser\BaseParser;
use Symfony\Component\Console\Exception\RuntimeException;

/**
 * @Hedron\Annotation\Parser(
 *   pluginId = "blt_create_project"
 * )
 */
class BltCreateProject extends BaseParser {

  /**
   * {@inheritdoc}
   */
  public function parse(GitPostReceiveHandler $handler, CommandStackInterface $commandStack) {
    if (!$this->getFileSystem()->exists($this->getDataDirectoryPath() . DIRECTORY_SEPARATOR . 'docroot')) {
      $commandStack->addCommand("cd {$this->getDataDirectoryPath()}");
      $commandStack->addCommand("composer create-project --no-interaction acquia/blt-project .");
      $commandStack->execute();
    }
    // Ensure blt-project was successfully created and stop execution if not.
    if (!$this->getFileSystem()->exists($this->getDataDirectoryPath() . DIRECTORY_SEPARATOR . 'docroot')) {
      throw new RuntimeException("Acquia BLT-Project not successfully created.");
    }
  }

}

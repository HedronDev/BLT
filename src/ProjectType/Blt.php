<?php

namespace Hedron\BLT\ProjectType;

use EclipseGc\Plugin\Discovery\PluginDefinitionSet;
use Hedron\Annotation\ProjectType;
use Hedron\ParserDictionary;
use Hedron\ProjectType\ProjectTypeBase;

/**
 * @ProjectType(
 *   pluginId = "blt",
 *   label = "Acquia BLT"
 * )
 */
class Blt extends ProjectTypeBase {

  /**
   * {@inheritdoc}
   */
  public static function getFileParsers(ParserDictionary $dictionary) : PluginDefinitionSet {
    $parsers = [
      'git_pull',
      'ensure_shared_volumes',
      'blt_create_project',
      'blt_deploy',
      'blt_phpunit',
      'docker_compose',
      'docker_compose_ps',
    ];
    $definitions = [];
    foreach ($parsers as $parser) {
      $definitions[] = $dictionary->getDefinition($parser);
    }
    return new PluginDefinitionSet(... $definitions);
  }

}

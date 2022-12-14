<?php

namespace Acquia\Blt\Robo\Common;

use Consolidation\Comments\Comments;
use Symfony\Component\Yaml\Yaml;

/**
 * Reads and writes to YAML files in a friendly and consistent way.
 *
 * @package Acquia\Blt\Robo\Common
 *
 * Preserves comments when possible.
 */
class YamlWriter {

  /**
   * Filepath.
   *
   * @var string
   */
  private $filepath;

  /**
   * YAML contents.
   *
   * @var string
   */
  private $contents = "\n";

  /**
   * YamlWriter constructor.
   *
   * @param string $filepath
   *   Filepath.
   */
  public function __construct(string $filepath) {
    $this->filepath = $filepath;
    if (file_exists($this->filepath)) {
      $this->contents = file_get_contents($filepath);
    }
  }

  /**
   * Get contents.
   *
   * @return array
   *   Array.
   */
  public function getContents(): array {
    $yaml = Yaml::parse($this->contents);
    return is_array($yaml) ? $yaml : [];
  }

  /**
   * Writes contents to file, preserving comments.
   *
   * @param array $yaml
   *   Yaml.
   */
  public function write(array $yaml): void {
    $alteredContents = Yaml::dump($yaml, PHP_INT_MAX, 2);
    $commentManager = new Comments();
    $commentManager->collect(explode("\n", $this->contents));
    $alteredWithComments = $commentManager->inject(explode("\n", $alteredContents));
    $result = implode("\n", $alteredWithComments);
    $result .= "\n";
    file_put_contents($this->filepath, $result);
  }

}

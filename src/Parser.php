<?php

namespace FlySkyPie\MarkdownRegulationParser;

/**
 * To parse name, histories and clauses of regulation.
 *
 * @author flyskypie
 */
class Parser {

  private $sourceString;
  private $isChaptered = false;
  private $regulationName;
  private $regulationHistories = [];
  private $regulations;

  function __construct(string $markdownString) {
    $this->sourceString = $markdownString;

    $this->takeName();
    $this->takeHistories();
    $this->removeListTag();
    $this->normalizeChapters();
    $this->parseRegulationLines();
  }

  public function getName(): string {
    return $this->regulationName;
  }

  public function getHistories(): array {
    return $this->regulationHistories;
  }

  public function getRegulations(): array {
    return $this->regulations;
  }

  public function isChaptered(): bool {
    return $this->isChaptered;
  }

  /*
   * Find level-1 headings and remove it(them),
   * but saved first one as name of regulation.
   */

  private function takeName() {
    $matche = [];
    $pattern = '/^# (.+)(?:\n|$)/m';
    if (\preg_match($pattern, $this->sourceString, $matche)) {
      $this->sourceString = preg_replace($pattern, '', $this->sourceString);
      $this->regulationName = $matche[1];
    } else {
      throw new Exception('The name of regulation not found.');
    }
  }

  /*
   * Find level-6 headings , save those as histories of regulation 
   * and remove it(them).
   */

  private function takeHistories() {
    $matches = [];
    $pattern = '/^#{6}\ (.+)(?:\n|$|\r\n)/m';
    if (\preg_match_all($pattern, $this->sourceString, $matches)) {
      $this->sourceString = \preg_replace($pattern, '', $this->sourceString);
      $this->regulationHistories = $matches[1];
    } else {
      throw new Exception('History not found.');
    }
  }

  private function removeListTag() {
    $pattern = '/^(\s*)- (.*)/m';
    $replacement = '$1$2';
    $this->sourceString = \preg_replace($pattern, $replacement, $this->sourceString);
  }

  /*
   * Add \t to each line except chapter, and remove ### from chapter title.
   */

  private function normalizeChapters() {
    $patterns = ['/^((?:[^#]{3}[^ ]).*)$/m', '/^#{3}\ (.+)(?:$|\n|\r\n)/m'];
    $replacements = ["\t$1", '$1'];

    $pattern = '/^#{3}\ (.+)(?:$|\n|\r\n)/m';
    if (\preg_match_all($pattern, $this->sourceString)) {
      $this->isChaptered = true;
      $this->sourceString = \preg_replace($patterns, $replacements, $this->sourceString);
    }
  }

  private function parseRegulationLines() {
    $chunks = [];
    $templateChunks = $this->getLevelOneChunks();

    foreach ($templateChunks as $chunk) {
      $subKey = '';
      $subsubObject = [];
      $this->parseChunk($chunk, $subKey, $subsubObject);
      $chunks[$subKey] = $subsubObject;
    }

    $this->regulations = $chunks;
  }

  /*
   * Separate regulations to chunks.
   */

  private function getLevelOneChunks() {
    $lines = \array_filter(\preg_split("/((\r?\n)|(\r\n?))/", $this->sourceString));
    //print_r($lines);
    $chunks = [];
    $index = 0;
    foreach ($lines as $line) {
      $pattern = '/^([^\t].*)/m';
      if (\preg_match($pattern, $line)) {
        $index += 1;
        $chunks[$index] = [$line];
        continue;
      }

      if ($index === 0) {
        continue;
      }

      $chunks[$index][] = $line;
    }
    return $chunks;
  }

  /*
   * To parse chunk like '^(.*)$^\t(.*)$':
   * there are indent in each line except first line.
   * @param array $lines
   */

  private function parseChunk(array $lines, string &$key, array &$subchunk) {
    $key = \array_shift($lines);

    $preprocessSubchunks = $this->parseSubcunk($lines);
    $subObject = [];

    foreach ($preprocessSubchunks as $preprocessChunk) {
      $subKey = '';
      $subsubObject = [];
      $this->parseChunk($preprocessChunk, $subKey, $subsubObject);
      $subObject [$subKey] = $subsubObject;
    }

    $subchunk = $subObject;
  }

  /*
   * To parse chunk like : '/^\t(.*)$/m'
   * @param array $lines
   */

  private function parseSubcunk(array $lines) {
    $subchunks = [];

    $index = 0;
    foreach ($lines as $line) {
      $line = \preg_replace('/^\t(.*)/m', '$1', $line); //remove 1 indent

      $matches = [];
      $pattern = '/^([^\t].*)/m';
      if (\preg_match($pattern, $line, $matches)) {
        $index += 1;
        $subchunks[$index] = [$line];
        continue;
      }

      if ($index === 0) {
        continue;
      }

      $subchunks[$index][] = $line;
    }
    return $subchunks;
  }

}

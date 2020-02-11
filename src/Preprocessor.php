<?php

namespace FlySkyPie\MarkdownRegulationParser;

class Preprocessor {

  private $markdownString;

  function __construct(string $markdownString) {
    $this->markdownString = $markdownString;

    $this->normalizeLevel1Heading();
    $this->normalizeIndent();
    $this->normalizeList();
  }

  public function getString(): string {
    return $this->markdownString;
  }

  private function normalizeLevel1Heading() {
    $this->preg_replace('/(.+)\n\={3,}(?:\n|$)/', '# $1');
  }

  private function normalizeList(){
    $this->preg_replace('/[0-9]+. (.+)/', '- $1')
            ->preg_replace('/\* (.+)/', '- $1');
  }
  
  private function normalizeIndent(){
    $this->preg_replace('/ {4}/', "\t");
  }
  /*
   * Make preg_replace be method of object.
   */

  private function preg_replace($pattern, $replacement) {
    $this->markdownString = preg_replace($pattern, $replacement, $this->markdownString);
    return $this;
  }

}

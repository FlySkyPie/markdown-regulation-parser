<?php

use PHPUnit\Framework\TestCase;
use FlySkyPie\MarkdownRegulationParser\Preprocessor;

class PreprocessorTest extends TestCase {

  public function test_level_1_heading() {
    $source = file_get_contents(__DIR__ . '/test1a.md');
    $target = file_get_contents(__DIR__ . '/test1b.md');

    $p = new Preprocessor($source);
    $this->assertEquals($target, $p->getString());
  }

  public function test_list() {
    $source = file_get_contents(__DIR__ . '/test2a.md');
    $target = file_get_contents(__DIR__ . '/test2b.md');

    $p = new Preprocessor($source);
    $this->assertEquals($target, $p->getString());
  }

  public function test_indent() {
    $source = file_get_contents(__DIR__ . '/test3a.md');
    $target = file_get_contents(__DIR__ . '/test3b.md');

    $p = new Preprocessor($source);
    $this->assertEquals($target, $p->getString());
  }

}

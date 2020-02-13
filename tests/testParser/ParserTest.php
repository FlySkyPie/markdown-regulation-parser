<?php

use PHPUnit\Framework\TestCase;
use FlySkyPie\MarkdownRegulationParser\Parser;

/**
 * Description of ParserTest
 *
 * @author flyskypie
 */
class ParserTest extends TestCase {

  public function test_get_title() {
    $source = file_get_contents(__DIR__ . '/test1.md');

    $p = new Parser($source);
    $this->assertEquals('title', $p->getName());
  }

  public function test_get_histories() {
    $source = file_get_contents(__DIR__ . '/test1.md');

    $p = new Parser($source);
    $this->assertEquals(['amendment1', 'amendment2'], $p->getHistories());
  }

  public function test_is_not_chaptered() {
    $source = file_get_contents(__DIR__ . '/test1.md');

    $p = new Parser($source);
    $this->assertEquals(false, $p->isChaptered());
  }

  public function test_is_chaptered() {
    $source = file_get_contents(__DIR__ . '/test2.md');

    $p = new Parser($source);
    $this->assertEquals(true, $p->isChaptered());
  }

  public function test_get_regulations_without_chapter() {
    $source = file_get_contents(__DIR__ . '/test3.md');
    $target = [
        'Article 1' => ['Paragraph 1' => []],
        'Article 2' => ['Paragraph 1' => [], 'Paragraph 2' => []],
    ];

    $p = new Parser($source);
    $this->assertEquals($target, $p->getRegulations());
  }

  public function test_get_regulations_with_chapter() {
    $source = file_get_contents(__DIR__ . '/test4a.md');
    $target = file_get_contents(__DIR__ . '/test4b.md');


    $sourceParser = new Parser($source);
    $targetParser = new Parser($target);
    $this->assertEquals($sourceParser->getRegulations(), $targetParser->getRegulations());
  }

  public function test_get_regulations_with_nested() {
    $source = file_get_contents(__DIR__ . '/test5.md');
    $target = [
        'Article 1' => [
            'Paragraph 1' => [
                'Subsection 1' => [
                    'Item 1' => []
                ]
            ]
        ]
    ];

    $p = new Parser($source);
    $this->assertEquals($target, $p->getRegulations());
  }

  public function test_get_json() {
    $source = file_get_contents(__DIR__ . '/test6a.md');
    $target = file_get_contents(__DIR__ . '/test6b.md');

    $p = new Parser($source);
    $this->assertEquals($target, $p->getJSON());
  }

}

<?php

/*
 * This file is part of GeoBuilder.
 *
 * Copyright Adamo Aerendir Crespi 2020.
 *
 * @author    Adamo Aerendir Crespi <hello@aerendir.me>
 * @copyright Copyright (C) 2020 Aerendir. All rights reserved.
 * @license   MIT
 */

namespace SerendipityHQ\Component\GeoBuilder\Tests;

use PHPUnit\Framework\TestCase;
use SerendipityHQ\Component\GeoBuilder\Parser;

/**
 * Tests Parser.
 */
final class ParserTest extends TestCase
{
    public function testNoSourceFileFound(): void
    {
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'Parser' . DIRECTORY_SEPARATOR . 'IT' . DIRECTORY_SEPARATOR . 'IT.txt';

        // This is the first result of the parsed file
        $expected = [
            0 => 'IT',
  1           => '67010',
  2           => 'Barete',
  3           => 'Abruzzi',
  4           => 'AB',
  5           => "L'Aquila",
  6           => 'AQ',
  7           => '',
  8           => '',
  9           => '42.4501',
  10          => '13.2806',
  11          => '4',
];
        $result = Parser::parse($filePath);

        self::assertCount(17980, $result);
        self::assertEquals($expected, $result[0]);
    }
}

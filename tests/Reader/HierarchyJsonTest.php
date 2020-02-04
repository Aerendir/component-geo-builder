<?php

namespace SerendipityHQ\Component\GeoBuilder\Test\Exception;

use PHPUnit\Framework\TestCase;
use SerendipityHQ\Component\GeoBuilder\Exception\BuildException;
use SerendipityHQ\Component\GeoBuilder\Parser;
use SerendipityHQ\Component\GeoBuilder\Reader\HierarchyJsonDumper;
use SerendipityHQ\Component\GeoBuilder\Reader\HierarchyJsonReader;

/**
 * Tests Parser.
 */
class HierarchyJsonTest extends TestCase
{
    public function testDumperAndReader():void
    {
        $dumpFolder = sys_get_temp_dir();
        $hierarchyJsonDumper = new HierarchyJsonDumper();
        $hierarchyJsonReader = new HierarchyJsonReader($dumpFolder);

        // This is the first result of the parsed file
        $test = [[
            0 => "IT",
  1 => "67010",
  2 => "Barete",
  3 => "Abruzzi",
  4 => "AB",
  5 => "L'Aquila",
  6 => "AQ",
  7 => "",
  8 => "",
  9 => "42.4501",
  10 => "13.2806",
  11 => "4",
]];
        $expectedAdmin1Dump = '{"AB":"Abruzzi"}';
        $expectedAdmin2Dump = '{"AQ":"L\'Aquila"}';
        $expectedAdmin3Dump = '{"67010":"Barete"}';
        $hierarchyJsonDumper->dump($dumpFolder, $test);

        $admin1 = \Safe\file_get_contents($dumpFolder . DIRECTORY_SEPARATOR . 'IT.json');
        $admin2 = \Safe\file_get_contents($dumpFolder . DIRECTORY_SEPARATOR . 'IT_AB.json');
        $admin3 = \Safe\file_get_contents($dumpFolder . DIRECTORY_SEPARATOR . 'IT_AB_AQ.json');

        self::assertEquals($expectedAdmin1Dump, $admin1);
        self::assertEquals($expectedAdmin2Dump, $admin2);
        self::assertEquals($expectedAdmin3Dump, $admin3);

        $expectedAdmin1Read = ["AB" =>"Abruzzi"];
        $expectedAdmin2Read = ["AQ" => "L'Aquila"];
        $expectedAdmin3Read = ["67010" => "Barete"];

        $admin1 = $hierarchyJsonReader->read('it');
        $admin2 = $hierarchyJsonReader->read('it', 'ab');
        $admin3 = $hierarchyJsonReader->read('it', 'ab', 'aq');

        self::assertEquals($expectedAdmin1Read, $admin1);
        self::assertEquals($expectedAdmin2Read, $admin2);
        self::assertEquals($expectedAdmin3Read, $admin3);
    }
}

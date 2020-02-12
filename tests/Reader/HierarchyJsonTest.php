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

namespace SerendipityHQ\Component\GeoBuilder\Tests\Reader;

use PHPUnit\Framework\TestCase;
use function Safe\file_get_contents;
use SerendipityHQ\Component\GeoBuilder\Parser;
use SerendipityHQ\Component\GeoBuilder\Reader\HierarchyJsonDumper;
use SerendipityHQ\Component\GeoBuilder\Reader\HierarchyJsonReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * Tests Parser.
 */
class HierarchyJsonTest extends TestCase
{
    public function testDumperAndReader(): void
    {
        $dumpFolder          = sys_get_temp_dir();
        $hierarchyJsonDumper = new HierarchyJsonDumper();
        $hierarchyJsonReader = new HierarchyJsonReader($dumpFolder);

        // This is the first result of the parsed file
        $test = [[
            0 => 'IT',
            1 => '67010',
            2 => 'Barete',
            3 => 'Abruzzi',
            4 => 'AB',
            5 => "L'Aquila",
            6 => 'AQ',
            7 => '',
            8 => '',
            9 => '42.4501',
            10=> '13.2806',
            11=> '4',
        ]];
        $jsonData           = ['AB' => 'Abruzzi'];
        $expectedAdmin1Dump = (new JsonEncoder())->encode($jsonData, JsonEncoder::FORMAT);
        $jsonData           = ['AQ' => "L'Aquila"];
        $expectedAdmin2Dump = (new JsonEncoder())->encode($jsonData, JsonEncoder::FORMAT);
        $jsonData           = [67010 => 'Barete'];
        $expectedAdmin3Dump = (new JsonEncoder())->encode($jsonData, JsonEncoder::FORMAT);
        $hierarchyJsonDumper->dump($dumpFolder, $test);

        $admin1 = file_get_contents($dumpFolder . DIRECTORY_SEPARATOR . 'IT.json');
        $admin2 = file_get_contents($dumpFolder . DIRECTORY_SEPARATOR . 'IT_AB.json');
        $admin3 = file_get_contents($dumpFolder . DIRECTORY_SEPARATOR . 'IT_AB_AQ.json');

        self::assertEquals($expectedAdmin1Dump, $admin1);
        self::assertEquals($expectedAdmin2Dump, $admin2);
        self::assertEquals($expectedAdmin3Dump, $admin3);

        $expectedAdmin1Read = ['AB' =>'Abruzzi'];
        $expectedAdmin2Read = ['AQ' => "L'Aquila"];
        $expectedAdmin3Read = ['67010' => 'Barete'];

        $admin1 = $hierarchyJsonReader->read('it');
        $admin2 = $hierarchyJsonReader->read('it', 'ab');
        $admin3 = $hierarchyJsonReader->read('it', 'ab', 'aq');

        self::assertEquals($expectedAdmin1Read, $admin1);
        self::assertEquals($expectedAdmin2Read, $admin2);
        self::assertEquals($expectedAdmin3Read, $admin3);
    }
}

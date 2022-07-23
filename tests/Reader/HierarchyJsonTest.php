<?php

declare(strict_types=1);

/*
 * This file is part of the Serendipity HQ Geo Builder Component.
 *
 * Copyright (c) Adamo Aerendir Crespi <aerendir@serendipityhq.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SerendipityHQ\Component\GeoBuilder\Tests\Reader;

use PHPUnit\Framework\TestCase;
use SerendipityHQ\Component\GeoBuilder\Parser;
use SerendipityHQ\Component\GeoBuilder\Reader\HierarchyJsonDumper;
use SerendipityHQ\Component\GeoBuilder\Reader\HierarchyJsonReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use function Safe\file_get_contents;

/**
 * Tests Parser.
 */
final class HierarchyJsonTest extends TestCase
{
    // This is the first result of the parsed file
    /** @var string[][] */
    private const TEST = [[
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

    public function testDumperAndReader(): void
    {
        $dumpFolder          = \sys_get_temp_dir();
        $hierarchyJsonDumper = new HierarchyJsonDumper();
        $hierarchyJsonReader = new HierarchyJsonReader($dumpFolder);
        $jsonData            = ['AB' => 'Abruzzi'];
        $expectedAdmin1Dump  = (new JsonEncoder())->encode($jsonData, JsonEncoder::FORMAT);
        $jsonData            = ['AQ' => "L'Aquila"];
        $expectedAdmin2Dump  = (new JsonEncoder())->encode($jsonData, JsonEncoder::FORMAT);
        $jsonData            = [67010 => 'Barete'];
        $expectedAdmin3Dump  = (new JsonEncoder())->encode($jsonData, JsonEncoder::FORMAT);
        $hierarchyJsonDumper->dump($dumpFolder, self::TEST);

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

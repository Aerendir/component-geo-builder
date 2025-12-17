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

namespace SerendipityHQ\Component\GeoBuilder\Reader;

use SerendipityHQ\Component\GeoBuilder\DumperInterface;
use SerendipityHQ\Component\GeoBuilder\GeoBuilder;
use SerendipityHQ\Component\GeoBuilder\Helper\FileWriter;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * Dumps into the given folder the content of the parsed dump from GeoNames.
 */
final class HierarchyJsonDumper implements DumperInterface
{
    private JsonEncode $encoder;

    /** @var string[] $countries */
    private array $countries = [];

    private array $admins1 = [];
    private array $admins2 = [];
    private array $admins3 = [];
    private array $places  = [];

    /**
     * Initializes the encoder.
     */
    public function __construct()
    {
        $this->encoder = new JsonEncode();
    }

    public function dump(string $dumpPath, array $parsedGeonamesDump): void
    {
        $this->rearrangeData($parsedGeonamesDump);
        $this->dumpData($dumpPath);
    }

    /**
     * @codeCoverageIgnore
     */
    public function reset(): void
    {
        $this->countries = [];
        $this->admins1   = [];
        $this->admins2   = [];
        $this->admins3   = [];
        $this->places    = [];
    }

    private function rearrangeData(array $parsedGeonamesDump): void
    {
        foreach ($parsedGeonamesDump as $place) {
            $this->addCountry($place[GeoBuilder::COUNTRY_CODE]);
            $this->admins1[$place[GeoBuilder::COUNTRY_CODE]][$place[GeoBuilder::ADMIN_1_CODE]]                                                                                                     = $place[GeoBuilder::ADMIN_1_NAME];
            $this->admins2[$place[GeoBuilder::COUNTRY_CODE]][$place[GeoBuilder::ADMIN_1_CODE]][$place[GeoBuilder::ADMIN_2_CODE]]                                                                   = $place[GeoBuilder::ADMIN_2_NAME];
            $this->admins3[$place[GeoBuilder::COUNTRY_CODE]][$place[GeoBuilder::ADMIN_1_CODE]][$place[GeoBuilder::ADMIN_2_CODE]][$place[GeoBuilder::ADMIN_3_CODE]]                                 = $place[GeoBuilder::ADMIN_3_NAME];
            $this->places[$place[GeoBuilder::COUNTRY_CODE]][$place[GeoBuilder::ADMIN_1_CODE]][$place[GeoBuilder::ADMIN_2_CODE]][$place[GeoBuilder::ADMIN_3_CODE]][$place[GeoBuilder::POSTAL_CODE]] = $place[GeoBuilder::PLACE_NAME];
        }
    }

    private function addCountry(string $countryCode): void
    {
        if (false === \in_array($countryCode, $this->countries, true)) {
            $this->countries[] = $countryCode;
        }
    }

    private function dumpData(string $dumpPath): void
    {
        $this->dumpCountries($dumpPath);
        $this->dumpAdmins1($dumpPath);
        $this->dumpAdmins2($dumpPath);
        $this->dumpAdmins3($dumpPath);
        $this->dumpPlaces($dumpPath);
    }

    private function dumpCountries(string $dumpPath): void
    {
        $export = $this->encoder->encode($this->countries, JsonEncoder::FORMAT);
        FileWriter::writeFile($dumpPath . DIRECTORY_SEPARATOR . 'countries.json', $export);
    }

    private function dumpAdmins1(string $dumpPath): void
    {
        foreach ($this->admins1 as $countryCode => $admins1) {
            $filename = \strtoupper($countryCode) . '.json';
            $export   = $this->encoder->encode($admins1, JsonEncoder::FORMAT);
            FileWriter::writeFile($dumpPath . DIRECTORY_SEPARATOR . $filename, $export);
        }
    }

    private function dumpAdmins2(string $dumpPath): void
    {
        foreach ($this->admins2 as $countryCode => $admins1) {
            foreach ($admins1 as $admin1 => $admins2) {
                $filename = FileWriter::buildFileName([
                    $countryCode,
                    $admin1,
                ], '.json');
                $export = $this->encoder->encode($admins2, JsonEncoder::FORMAT);
                FileWriter::writeFile($dumpPath . DIRECTORY_SEPARATOR . $filename, $export);
            }
        }
    }

    private function dumpAdmins3(string $dumpPath): void
    {
        foreach ($this->admins3 as $countryCode => $admins1) {
            foreach ($admins1 as $admin1 => $admins2) {
                foreach ($admins2 as $admin2 => $admins3) {
                    $filename = FileWriter::buildFileName([
                        $countryCode,
                        $admin1,
                        $admin2,
                    ], '.json');
                    $export = $this->encoder->encode($admins3, JsonEncoder::FORMAT);
                    FileWriter::writeFile($dumpPath . DIRECTORY_SEPARATOR . $filename, $export);
                }
            }
        }
    }

    private function dumpPlaces(string $dumpPath): void
    {
        foreach ($this->places as $countryCode => $admins1) {
            foreach ($admins1 as $admin1 => $admins2) {
                foreach ($admins2 as $admin2 => $admins3) {
                    foreach ($admins3 as $admin3 => $places) {
                        $filename = FileWriter::buildFileName([
                            $countryCode,
                            $admin1,
                            $admin2,
                            $admin3,
                        ], '.json');
                        $export = $this->encoder->encode($places, JsonEncoder::FORMAT);
                        FileWriter::writeFile($dumpPath . DIRECTORY_SEPARATOR . $filename, $export);
                    }
                }
            }
        }
    }
}

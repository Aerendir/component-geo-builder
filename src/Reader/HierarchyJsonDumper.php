<?php

declare(strict_types=1);

/*
 * This file is part of GeoBuilder.
 *
 * Copyright Adamo Aerendir Crespi 2020.
 *
 * @author    Adamo Aerendir Crespi <hello@aerendir.me>
 * @copyright Copyright (C) 2020 Aerendir. All rights reserved.
 * @license   MIT
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
class HierarchyJsonDumper implements DumperInterface
{
    /** @var JsonEncode $encoder */
    private $encoder;

    /** @var array $countries */
    private $countries = [];

    /** @var array $admins1 */
    private $admins1 = [];

    /** @var array $admins2 */
    private $admins2 = [];

    /** @var array $admins3 */
    private $admins3 = [];

    /** @var array $places */
    private $places = [];

    /**
     * Initializes the encoder.
     */
    public function __construct()
    {
        $this->encoder = new JsonEncode();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Safe\Exceptions\FilesystemException
     * @throws \Safe\Exceptions\StringsException
     */
    public function dump(string $dumpPath, array $parsedGeonamesDump): void
    {
        $this->rearrangeData($parsedGeonamesDump);
        $this->dumpData($dumpPath);
    }

    /**
     * {@inheritdoc}
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

    /**
     * @param array $parsedGeonamesDump
     */
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

    /**
     * @param string $countryCode
     */
    private function addCountry(string $countryCode): void
    {
        if (false === in_array($countryCode, $this->countries, true)) {
            $this->countries[] = $countryCode;
        }
    }

    /**
     * @param string $dumpPath
     *
     * @throws \Safe\Exceptions\FilesystemException
     * @throws \Safe\Exceptions\StringsException
     */
    private function dumpData(string $dumpPath): void
    {
        $this->dumpCountries($dumpPath);
        $this->dumpAdmins1($dumpPath);
        $this->dumpAdmins2($dumpPath);
        $this->dumpAdmins3($dumpPath);
        $this->dumpPlaces($dumpPath);
    }

    /**
     * @param string $dumpPath
     *
     * @throws \Safe\Exceptions\FilesystemException
     * @throws \Safe\Exceptions\StringsException
     */
    private function dumpCountries(string $dumpPath): void
    {
        $export = $this->encoder->encode($this->countries, JsonEncoder::FORMAT);
        \SerendipityHQ\Component\GeoBuilder\Helper\FileWriter::writeFile($dumpPath . DIRECTORY_SEPARATOR . 'countries.json', $export);
    }

    /**
     * @param string $dumpPath
     *
     * @throws \Safe\Exceptions\FilesystemException
     * @throws \Safe\Exceptions\StringsException
     */
    private function dumpAdmins1(string $dumpPath): void
    {
        /**
         * @var string               IT, DE, ecc
         * @var array<string,string> $admins1
         */
        foreach ($this->admins1 as $countryCode => $admins1) {
            $filename = strtoupper($countryCode) . '.json';
            $export   = $this->encoder->encode($admins1, JsonEncoder::FORMAT);
            \SerendipityHQ\Component\GeoBuilder\Helper\FileWriter::writeFile($dumpPath . DIRECTORY_SEPARATOR . $filename, $export);
        }
    }

    /**
     * @param string $dumpPath
     *
     * @throws \Safe\Exceptions\FilesystemException
     * @throws \Safe\Exceptions\StringsException
     */
    private function dumpAdmins2(string $dumpPath): void
    {
        /**
         * @var string               IT, DE, ecc
         * @var array<string,string> $admins1
         */
        foreach ($this->admins2 as $countryCode => $admins1) {
            /**
             * @var string               CM (Campania), ecc
             * @var array<string,string> $admins2
             */
            foreach ($admins1 as $admin1 => $admins2) {
                $filename = FileWriter::buildFileName([
                    $countryCode,
                    $admin1,
                ], '.json');
                $export = $this->encoder->encode($admins2, JsonEncoder::FORMAT);
                \SerendipityHQ\Component\GeoBuilder\Helper\FileWriter::writeFile($dumpPath . DIRECTORY_SEPARATOR . $filename, $export);
            }
        }
    }

    /**
     * @param string $dumpPath
     *
     * @throws \Safe\Exceptions\FilesystemException
     * @throws \Safe\Exceptions\StringsException
     */
    private function dumpAdmins3(string $dumpPath): void
    {
        /**
         * @var string               IT, DE, ecc
         * @var array<string,string> $admins1
         */
        foreach ($this->admins3 as $countryCode => $admins1) {
            /**
             * @var string               CM (Campania), ecc
             * @var array<string,string> $admins2
             */
            foreach ($admins1 as $admin1 => $admins2) {
                /**
                 * @var string               CM (Campania), ecc
                 * @var array<string,string> $admins3
                 */
                foreach ($admins2 as $admin2 => $admins3) {
                    $filename = FileWriter::buildFileName([
                            $countryCode,
                            $admin1,
                            $admin2,
                        ], '.json');
                    $export = $this->encoder->encode($admins3, JsonEncoder::FORMAT);
                    \SerendipityHQ\Component\GeoBuilder\Helper\FileWriter::writeFile($dumpPath . DIRECTORY_SEPARATOR . $filename, $export);
                }
            }
        }
    }

    /**
     * @param string $dumpPath
     *
     * @throws \Safe\Exceptions\FilesystemException
     * @throws \Safe\Exceptions\StringsException
     */
    private function dumpPlaces(string $dumpPath): void
    {
        /**
         * @var string               IT, DE, ecc
         * @var array<string,string> $admins1
         */
        foreach ($this->places as $countryCode => $admins1) {
            /**
             * @var string               CM (Campania), ecc
             * @var array<string,string> $admins2
             */
            foreach ($admins1 as $admin1 => $admins2) {
                /**
                 * @var string               CM (Campania), ecc
                 * @var array<string,string> $admins3
                 */
                foreach ($admins2 as $admin2 => $admins3) {
                    /**
                     * @var string               CM (Campania), ecc
                     * @var array<string,string> $admins3
                     */
                    foreach ($admins3 as $admin3 => $places) {
                        $filename = FileWriter::buildFileName([
                                $countryCode,
                                $admin1,
                                $admin2,
                                $admin3,
                            ], '.json');
                        $export = $this->encoder->encode($places, JsonEncoder::FORMAT);
                        \SerendipityHQ\Component\GeoBuilder\Helper\FileWriter::writeFile($dumpPath . DIRECTORY_SEPARATOR . $filename, $export);
                    }
                }
            }
        }
    }
}

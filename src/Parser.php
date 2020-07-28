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

namespace SerendipityHQ\Component\GeoBuilder;

use Safe\Exceptions\FilesystemException;
use function Safe\file_get_contents;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

/**
 * Parses the content of a dump from GeoNames.
 * @see \SerendipityHQ\Component\GeoBuilder\Tests\ParserTest
 */
final class Parser
{
    /**
     * @param string $filePath the txt file from Geonames
     *
     * @throws FilesystemException
     *
     * @return array
     */
    public static function parse(string $filePath): array
    {
        $unzippedCountry = file_get_contents($filePath);
        $encoder         = new CsvEncoder([
            CsvEncoder::DELIMITER_KEY     => "\t",
            CsvEncoder::AS_COLLECTION_KEY => false,
            CsvEncoder::NO_HEADERS_KEY    => true,
        ]);

        return $encoder->decode($unzippedCountry, CsvEncoder::FORMAT);
    }
}

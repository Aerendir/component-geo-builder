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

namespace SerendipityHQ\Component\GeoBuilder;

use Symfony\Component\Serializer\Encoder\CsvEncoder;

use function Safe\file_get_contents;

/**
 * Parses the content of a dump from GeoNames.
 *
 * @see Tests\ParserTest
 */
final class Parser
{
    /**
     * @param string $filePath the txt file from Geonames
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

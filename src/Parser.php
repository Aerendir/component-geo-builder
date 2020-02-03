<?php declare(strict_types=1);

namespace SerendipityHQ\Component\GeoBuilder;

use Symfony\Component\Serializer\Encoder\CsvEncoder;

/**
 * Parses the content of a dump from GeoNames.
 */
class Parser
{
    /**
     * @param string $filePath The txt file from Geonames.
     *
     * @return array
     */
    public static function parse(string $filePath):array
    {
        $unzippedCountry = file_get_contents($filePath);
        $encoder = new CsvEncoder([
            CsvEncoder::DELIMITER_KEY => "\t",
            CsvEncoder::AS_COLLECTION_KEY => false,
            CsvEncoder::NO_HEADERS_KEY => true,
        ]);

        return $encoder->decode($unzippedCountry, CsvEncoder::FORMAT);
    }
}

<?php declare(strict_types=1);

namespace SerendipityHQ\Component\GeoBuilder;

/**
 * Common interface for dumpers.
 */
interface DumperInterface
{
    /**
     * @param string $dumpPath
     * @param array  $parsedGeonamesDump
     */
    public function dump(string $dumpPath, array $parsedGeonamesDump):void;

    /**
     * Resets the dumper so it can be used again.
     */
    public function reset():void;
}

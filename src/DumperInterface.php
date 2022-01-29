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

/**
 * Common interface for dumpers.
 */
interface DumperInterface
{
    public function dump(string $dumpPath, array $parsedGeonamesDump): void;

    /**
     * Resets the dumper so it can be used again.
     */
    public function reset(): void;
}

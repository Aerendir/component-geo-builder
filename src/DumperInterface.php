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

/**
 * Common interface for dumpers.
 */
interface DumperInterface
{
    /**
     * @param string $dumpPath
     * @param array  $parsedGeonamesDump
     */
    public function dump(string $dumpPath, array $parsedGeonamesDump): void;

    /**
     * Resets the dumper so it can be used again.
     */
    public function reset(): void;
}

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
interface ReaderInterface
{
    /**
     * @param string $dataFolderPath the path to the folder where dumps reside
     */
    public function __construct(string $dataFolderPath);

    /**
     * @return array<int|string,string>
     */
    public function read(string $country = null, string $admin1 = null, string $admin2 = null, string $admin3 = null, string $place = null): array;
}

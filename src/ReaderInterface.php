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
interface ReaderInterface
{
    /**
     * @param string $dataFolderPath the path to the folder where dumps reside
     */
    public function __construct(string $dataFolderPath);

    /**
     * @param string|null $country
     * @param string|null $admin1
     * @param string|null $admin2
     * @param string|null $admin3
     * @param string|null $place
     *
     * @return array<string,string>
     */
    public function read(?string $country = null, ?string $admin1 = null, ?string $admin2 = null, ?string $admin3 = null, ?string $place = null);
}

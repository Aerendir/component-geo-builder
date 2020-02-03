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
 * Constants representing the header used in any dump from GeoNames.
 */
class GeoBuilder
{
    /** @var int */
    public const COUNTRY_CODE = 0;

    /** @var int */
    public const POSTAL_CODE = 1;

    /** @var int */
    public const PLACE_NAME = 2;

    /** @var int */
    public const ADMIN_1_NAME = 3;

    /** @var int */
    public const ADMIN_1_CODE = 4;

    /** @var int */
    public const ADMIN_2_NAME = 5;

    /** @var int */
    public const ADMIN_2_CODE = 6;

    /** @var int */
    public const ADMIN_3_NAME = 7;

    /** @var int */
    public const ADMIN_3_CODE = 8;

    /** @var int */
    public const LATITUDE = 9;

    /** @var int */
    public const LONGITUDE = 10;

    /** @var int */
    public const ACCURACY = 11;
}

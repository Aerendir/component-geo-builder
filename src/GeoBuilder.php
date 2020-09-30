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
 * Constants representing the header used in any dump from GeoNames.
 *
 * @codeCoverageIgnore
 */
final class GeoBuilder
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
}

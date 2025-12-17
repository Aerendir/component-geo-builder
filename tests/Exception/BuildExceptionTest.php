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

namespace SerendipityHQ\Component\GeoBuilder\Tests\Exception;

use PHPUnit\Framework\TestCase;
use SerendipityHQ\Component\GeoBuilder\Exception\BuildException;

/**
 * Tests BuildException.
 */
final class BuildExceptionTest extends TestCase
{
    /** @var string */
    private const REQUESTED_COUNTRY = 'it';

    public function testNoSourceFileFound(): never
    {
        $this->expectException(BuildException::class);

        throw BuildException::noSourceFileFound(self::REQUESTED_COUNTRY);
    }
}

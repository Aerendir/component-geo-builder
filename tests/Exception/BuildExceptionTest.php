<?php

/*
 * This file is part of GeoBuilder.
 *
 * Copyright Adamo Aerendir Crespi 2020.
 *
 * @author    Adamo Aerendir Crespi <hello@aerendir.me>
 * @copyright Copyright (C) 2020 Aerendir. All rights reserved.
 * @license   MIT
 */

namespace SerendipityHQ\Component\GeoBuilder\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Safe\Exceptions\StringsException;
use SerendipityHQ\Component\GeoBuilder\Exception\BuildException;

/**
 * Tests BuildException.
 */
final class BuildExceptionTest extends TestCase
{
    /** @var string */
    private const REQUESTED_COUNTRY = 'it';

    /**
     * @throws BuildException
     * @throws StringsException
     */
    public function testNoSourceFileFound(): void
    {
        $this->expectException(BuildException::class);
        throw BuildException::noSourceFileFound(self::REQUESTED_COUNTRY);
    }
}

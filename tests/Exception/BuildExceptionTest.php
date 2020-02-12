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
class BuildExceptionTest extends TestCase
{
    /**
     * @throws BuildException
     * @throws StringsException
     */
    public function testNoSourceFileFound(): void
    {
        $requestedCountry = 'it';
        $this->expectException(BuildException::class);
        throw BuildException::noSourceFileFound($requestedCountry);
    }
}

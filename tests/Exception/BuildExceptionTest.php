<?php

namespace SerendipityHQ\Component\GeoBuilder\Test\Exception;

use PHPUnit\Framework\TestCase;
use SerendipityHQ\Component\GeoBuilder\Exception\BuildException;

/**
 * Tests BuildException.
 */
class BuildExceptionTest extends TestCase
{
    /**
     * @throws BuildException
     * @throws \Safe\Exceptions\StringsException
     */
    public function testNoSourceFileFound():void
    {
        $requestedCountry = 'it';
        $this->expectException(BuildException::class);
        throw BuildException::noSourceFileFound($requestedCountry);
    }
}

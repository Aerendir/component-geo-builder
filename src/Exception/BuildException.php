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

namespace SerendipityHQ\Component\GeoBuilder\Exception;

use Safe\Exceptions\StringsException;
use function Safe\sprintf;
use Throwable;

/**
 * Contains all exception throwable by BuildCommand.
 *
 * @see \SerendipityHQ\Component\GeoBuilder\Tests\Exception\BuildExceptionTest
 */
final class BuildException extends \Exception
{
    /**
     * {@inheritdoc}
     *
     * Avoid this Exception being initialized without using static methods.
     */
    protected function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @throws StringsException
     */
    public static function noSourceFileFound(string $requestedCountry): self
    {
        return new self(sprintf('Impossible to find the source file for country %s.', $requestedCountry));
    }
}

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

namespace SerendipityHQ\Component\GeoBuilder\Exception;

use Throwable;

/**
 * Contains all exception throwable by BuildCommand.
 */
class BuildException extends \Exception
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
     * @param string $requestedCountry
     *
     * @throws \Safe\Exceptions\StringsException
     *
     * @return static
     */
    public static function noSourceFileFound(string $requestedCountry): self
    {
        return new self(\Safe\sprintf('Impossible to find the source file for country %s.', $requestedCountry));
    }
}

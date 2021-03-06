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

namespace SerendipityHQ\Component\GeoBuilder\Helper;

use Safe\Exceptions\FilesystemException;
use Safe\Exceptions\StringsException;
use function Safe\file_put_contents;

/**
 * Helper class to deal with file writing.
 */
final class FileWriter
{
    /**
     * Writes a file ensuring the entire path exists.
     *
     * @param string       $dir
     * @param mixed|string $contents
     *
     * @throws FilesystemException
     * @throws StringsException
     */
    public static function writeFile(string $dir, $contents): void
    {
        // The content here is typically from a decode return value.
        // PHPStan complains about it being string or boolean, but only using --prefer-lowest.
        // This seems to be the easiest fix to make the build green.
        // Check this to better understand: https://github.com/Aerendir/component-geo-builder/runs/1734149064
        if (false === \is_string($contents)) {
            throw new \RuntimeException('Something went wrong encoding the countries.');
        }

        $parts = \explode(DIRECTORY_SEPARATOR, $dir);
        $file  = \array_pop($parts);
        $dir   = '';

        foreach ($parts as $part) {
            if ( ! \is_dir($dir .= DIRECTORY_SEPARATOR . $part) && ! \Safe\mkdir($dir) && ! \is_dir($dir)) {
                throw new \RuntimeException(\Safe\sprintf('Directory "%s" was not created', $dir));
            }
        }

        file_put_contents(\Safe\sprintf('%s%s%s', $dir, DIRECTORY_SEPARATOR, $file), $contents);
    }

    /**
     * @param array  $fileNameParts
     * @param string $ext
     *
     * @return string
     */
    public static function buildFileName(array $fileNameParts, string $ext): string
    {
        return \strtoupper(\implode('_', self::removeEmpties($fileNameParts))) . $ext;
    }

    /**
     * @param array $fileName
     *
     * @return array
     */
    private static function removeEmpties(array $fileName): array
    {
        return \array_filter($fileName, static function ($value): bool { return ! empty($value); });
    }
}

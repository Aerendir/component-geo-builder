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

namespace SerendipityHQ\Component\GeoBuilder\Helper;

/**
 * Helper class to deal with file writing.
 */
class FileWriter
{
    /**
     * Writes a file ensuring the entire path exists.
     *
     * @param string $dir
     * @param string $contents
     *
     * @throws \Safe\Exceptions\FilesystemException
     * @throws \Safe\Exceptions\StringsException
     */
    public static function writeFile(string $dir, string $contents): void
    {
        $parts = explode(DIRECTORY_SEPARATOR, $dir);
        $file  = array_pop($parts);
        $dir   = '';

        foreach ($parts as $part) {
            if ( ! is_dir($dir .= DIRECTORY_SEPARATOR . $part) && ! mkdir($dir) && ! is_dir($dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }
        }

        \Safe\file_put_contents(\Safe\sprintf('%s%s%s', $dir, DIRECTORY_SEPARATOR, $file), $contents);
    }

    /**
     * @param array  $fileNameParts
     * @param string $ext
     *
     * @return string
     */
    public static function buildFileName(array $fileNameParts, string $ext): string
    {
        return strtoupper(implode('_', self::removeEmpties($fileNameParts))) . $ext;
    }

    /**
     * @param array $fileName
     *
     * @return array
     */
    private static function removeEmpties(array $fileName): array
    {
        return array_filter($fileName, static function ($value) { return ! empty($value); });
    }
}

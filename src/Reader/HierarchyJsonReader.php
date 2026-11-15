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

namespace SerendipityHQ\Component\GeoBuilder\Reader;

use SerendipityHQ\Component\GeoBuilder\Helper\FileWriter;
use SerendipityHQ\Component\GeoBuilder\ReaderInterface;

/**
 * Reader for the hierarchy json dumps.
 */
final readonly class HierarchyJsonReader implements ReaderInterface
{
    public function __construct(private string $dataFolderPath)
    {
    }

    public function read(?string $country = null, ?string $admin1 = null, ?string $admin2 = null, ?string $admin3 = null, ?string $place = null): array
    {
        $fileName = FileWriter::buildFileName([$country, $admin1, $admin2, $admin3], '.json');
        $filePath = $this->dataFolderPath . DIRECTORY_SEPARATOR . $fileName;
        $content  = \file_get_contents($filePath);
        if (false === $content) {
            throw new \RuntimeException(\sprintf('Impossible to read the file "%s".', $filePath));
        }

        return \json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }
}

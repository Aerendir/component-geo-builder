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
final class HierarchyJsonReader implements ReaderInterface
{
    /** @var string $dataFolderPath */
    private $dataFolderPath;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $dataFolderPath)
    {
        $this->dataFolderPath = $dataFolderPath;
    }

    /**
     * {@inheritdoc}
     */
    public function read(?string $country = null, ?string $admin1 = null, ?string $admin2 = null, ?string $admin3 = null, ?string $place = null): array
    {
        $fileName = FileWriter::buildFileName([$country, $admin1, $admin2, $admin3], '.json');
        $filePath = $this->dataFolderPath . DIRECTORY_SEPARATOR . $fileName;
        $content  = \Safe\file_get_contents($filePath);

        return \Safe\json_decode($content, true);
    }
}

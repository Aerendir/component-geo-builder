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

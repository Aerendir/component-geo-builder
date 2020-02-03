<?php declare(strict_types=1);

namespace SerendipityHQ\Component\GeoBuilder\Reader;

use SerendipityHQ\Component\GeoBuilder\Helper\FileWriter;
use SerendipityHQ\Component\GeoBuilder\ReaderInterface;

/**
 * Reader for the hierarchy json dumps.
 */
class HierarchyJsonReader implements ReaderInterface
{
    /** @var string $dataFolderPath */
    private $dataFolderPath;

    /**
     * {@inheritDoc}
     */
    public function __construct(string $dataFolderPath)
    {
        $this->dataFolderPath = $dataFolderPath;
    }

    /**
     * {@inheritDoc}
     */
    public function read(?string $country = null, ?string $admin1 = null, ?string $admin2 = null, ?string $admin3 = null, ?string $place = null)
    {
        $fileName = FileWriter::buildFileName([$country,$admin1,$admin2,$admin3], '.json');
        $filePath = $this->dataFolderPath . DIRECTORY_SEPARATOR . $fileName;
        $content = file_get_contents($filePath);
        return json_decode($content, true);
    }
}

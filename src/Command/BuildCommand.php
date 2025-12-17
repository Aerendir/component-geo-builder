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

namespace SerendipityHQ\Component\GeoBuilder\Command;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use SerendipityHQ\Component\GeoBuilder\Exception\BuildException;
use SerendipityHQ\Component\GeoBuilder\Parser;
use SerendipityHQ\Component\GeoBuilder\Reader\HierarchyJsonDumper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;

use function Safe\mkdir;
use function Safe\sprintf;
use function Safe\substr;
use function Safe\tempnam;
use function Safe\unlink;

/**
 * Builds the geo lists.
 */
final class BuildCommand extends Command
{
    /** @var string */
    private const DATA_URL = 'https://www.geonames.org/export/zip/';

    /** @var string */
    private const EXT = '.zip';

    /** @var string $defaultName */
    protected static $defaultName = 'geobuilder:build';

    private Client $client;
    private string $dumpDir;
    private SymfonyStyle $ioWriter;
    private HierarchyJsonDumper $dumper;

    /** @var array $availableCountries The list of countries available on GeoNames */
    private array $availableCountries = [];

    public function __construct(Client $client, string $dumpDir)
    {
        $this->client  = $client;
        $this->dumpDir = $dumpDir;
        $this->dumper  = new HierarchyJsonDumper();

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ( ! $output instanceof ConsoleOutput) {
            throw new InvalidArgumentException('We need a ConsoleOutput object.');
        }

        // Create the Input/Output writer
        $this->ioWriter = new SymfonyStyle($input, $output);

        $this->ioWriter->title('GeoBuilder');

        $requestedCountries = $input->getArgument('countries');

        $this->scrapeListOfCountries();
        if (false === $this->checkRequestedCountriesAreAvailable($requestedCountries)) {
            return 1;
        }

        $this->processRequestedCountries($output, $requestedCountries);

        return 0;
    }

    protected function configure(): void
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates the list of information of countries.')
            ->addArgument('countries', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'Indicate for which countries you want to build information.');
    }

    private function scrapeListOfCountries(): void
    {
        $this->ioWriter->writeln('Downloading the list of countries available on GeoNames');
        $response = $this->client->request('GET', self::DATA_URL);
        $content  = $response->getBody()->getContents();

        $this->ioWriter->writeln('Processing the list of countries available on GeoNames');
        $this->availableCountries = (new Crawler($content))
            ->filterXPath('//html/body/pre[1]/a')
            ->reduce(static function (Crawler $node): bool {
                $value  = $node->text();
                $length = \strlen(self::EXT);

                return self::EXT === substr($value, -$length);
            })
            ->each(static function (Crawler $node): string {
                return $node->text();
            });
    }

    private function checkRequestedCountriesAreAvailable(array $requestedCountries): bool
    {
        foreach ($requestedCountries as $requestedCountry) {
            if (false === \in_array(sprintf('%s%s', \strtoupper($requestedCountry), self::EXT), $this->availableCountries, true)) {
                $this->ioWriter->error(sprintf('The country "%s" you requested is not available on GeoNames', $requestedCountry));

                return false;
            }
        }

        return true;
    }

    private function processRequestedCountries(OutputInterface $output, array $requestedCountries): void
    {
        $this->ioWriter->writeln('Starting to process requested countries');
        $progress = new ProgressBar($output, \count($requestedCountries));
        foreach ($requestedCountries as $requestedCountry) {
            /** @var ConsoleSectionOutput $sectionOutput */
            $sectionOutput = $output->section();
            $this->processRequestedCountry($sectionOutput, $requestedCountry);
            $sectionOutput->clear();
            $progress->advance();
        }
    }

    private function processRequestedCountry(ConsoleSectionOutput $output, string $requestedCountry): void
    {
        $this->ioWriter->writeln(sprintf('Starting to process requested country %s', $requestedCountry));
        $this->ioWriter->writeln(sprintf('Downloading requested country %s', $requestedCountry));
        $downloadedCountry = $this->downloadRequestedCountry($output, $requestedCountry);

        $this->ioWriter->writeln(sprintf('Extracting requested country %s', $requestedCountry));
        $unzippedCountry = $this->unzipDownloadedCountry($downloadedCountry);

        $this->ioWriter->writeln(sprintf('Decoding requested country %s', $requestedCountry));
        $decodedCountry = $this->decodeUnzippedCountry($requestedCountry, $unzippedCountry);

        $this->dumper->dump($this->dumpDir, $decodedCountry);
    }

    private function downloadRequestedCountry(ConsoleSectionOutput $output, string $requestedCountry): string
    {
        $tmpFileName = tempnam(\sys_get_temp_dir(), 'geobuilder');
        $progress    = new ProgressBar($output, 0, 1);
        $this->client->request('GET', sprintf('%s%s%s', self::DATA_URL, \strtoupper($requestedCountry), self::EXT), [
            RequestOptions::SINK     => $tmpFileName,
            // Vote up https://stackoverflow.com/a/34923682/1399706
            RequestOptions::PROGRESS => static function (int $dlSize, int $dlDownloaded) use ($progress): void {
                if (0 < $dlSize && 0 === $progress->getMaxSteps()) {
                    $progress->setMaxSteps($dlSize);
                }

                if (0 < $dlDownloaded) {
                    $progress->setProgress($dlDownloaded);
                }
            },
        ]);

        $progress->finish();
        $progress->clear();
        $output->clear();

        return $tmpFileName;
    }

    private function unzipDownloadedCountry(string $downloadedCountry): string
    {
        $zip = new \ZipArchive();
        $res = $zip->open($downloadedCountry);

        // @phpstan-ignore-next-line
        if (false === $res) {
            throw new \RuntimeException('Impossile to unzip the source file of the country.');
        }

        $tmpFolder = tempnam(\sys_get_temp_dir(), 'geobuilder');
        if (false === \is_dir($tmpFolder)) {
            if (\file_exists($tmpFolder)) {
                unlink($tmpFolder);
            }

            mkdir($tmpFolder);

            if ( ! \is_dir($tmpFolder)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $tmpFolder));
            }
        }

        $zip->extractTo($tmpFolder);
        $zip->close();

        return $tmpFolder;
    }

    private function decodeUnzippedCountry(string $requestedCountry, string $unzippedCountry): array
    {
        $fileName = null;
        if (2 === \strlen($requestedCountry)) {
            $fileName = sprintf('%s.txt', \strtoupper($requestedCountry));
        }

        $filePath = sprintf('%s/%s', $unzippedCountry, $fileName);

        if (false === \file_exists($filePath)) {
            throw BuildException::noSourceFileFound($requestedCountry);
        }

        return Parser::parse($filePath);
    }
}

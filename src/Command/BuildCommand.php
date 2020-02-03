<?php

declare(strict_types=1);

/*
 * This file is part of Whois Component.
 *
 * Copyright Adamo Aerendir Crespi 2019.
 *
 * @author    Adamo Aerendir Crespi <hello@aerendir.me>
 * @copyright Copyright (C) 2019 Aerendir. All rights reserved.
 * @license   MIT. Read the file LICENSE for more information.
 */

namespace SerendipityHQ\Component\GeoBuilder\Command;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use SerendipityHQ\Component\GeoBuilder\Reader\HierarchyJsonDumper;
use SerendipityHQ\Component\GeoBuilder\Exception\BuildException;
use SerendipityHQ\Component\GeoBuilder\Parser;
use Symfony\Component\Console\Helper\ProgressBar;
use SerendipityHQ\Bundle\ConsoleStyles\Console\Formatter\SerendipityHQOutputFormatter;
use SerendipityHQ\Bundle\ConsoleStyles\Console\Style\SerendipityHQStyle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Builds the geo lists.
 */
class BuildCommand extends Command
{
    /** @var string */
    private const DATA_URL = 'https://www.geonames.org/export/zip/';

    /** @var string  */
    private const EXT = '.zip';

    /** @var string $defaultName */
    protected static $defaultName = 'geobuilder:build';

    /** @var Client $client */
    private $client;

    /** @var  */
    private $dumpDir;

    /** @var SerendipityHQStyle $ioWriter */
    private $ioWriter;

    /** @var HierarchyJsonDumper $dumper */
    private $dumper;

    /** @var array $availableCountries The list of countries available on GeoNames */
    private $availableCountries = [];

    /**
     * @param Client $client
     * @param string $dumpDir
     */
    public function __construct(Client $client, string $dumpDir)
    {
        $this->client  = $client;
        $this->dumpDir = $dumpDir;
        $this->dumper  = new HierarchyJsonDumper();

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates the list of information of countries.')
            ->addArgument('countries', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'Indicate for which countries you want to build information.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws BuildException
     * @throws \Safe\Exceptions\FilesystemException
     * @throws \Safe\Exceptions\StringsException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if ( ! $output instanceof ConsoleOutput) {
            throw new \Symfony\Component\Console\Exception\InvalidArgumentException('We need a ConsoleOutput object.');
        }

        // Create the Input/Output writer
        $this->ioWriter = new SerendipityHQStyle($input, $output);
        $this->ioWriter->setFormatter(new SerendipityHQOutputFormatter(true));

        $this->ioWriter->title('GeoBuilder');

        $requestedCountries = $input->getArgument('countries');

        $this->scrapeListOfCountries();
        if (false === $this->checkRequestedCountriesAreAvailable($requestedCountries)) {
            return 1;
        }

        $this->processRequestedCountries($output, $requestedCountries);

        return 0;
    }

    /**
     * @return void
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function scrapeListOfCountries():void
    {
        $this->ioWriter->writeln('Downloading the list of countries available on GeoNames');
        $response = $this->client->request('GET', self::DATA_URL);
        $content = $response->getBody()->getContents();

        $this->ioWriter->writeln('Processing the list of countries available on GeoNames');
        $this->availableCountries = (new Crawler($content))
            ->filterXPath('//html/body/pre[1]/a')
            ->reduce(static function (Crawler $node) {
                $value = $node->text();
                $length = strlen(self::EXT);

                return \Safe\substr($value, -$length) === self::EXT;
            })
            ->each(static function (Crawler $node) {

                return $node->text();
            });
    }

    /**
     * @param array $requestedCountries
     *
     * @return bool
     * @throws \Safe\Exceptions\StringsException
     */
    private function checkRequestedCountriesAreAvailable(array $requestedCountries):bool
    {
        foreach ($requestedCountries as $requestedCountry) {
            if (false === in_array(\Safe\sprintf('%s%s', strtoupper($requestedCountry), self::EXT), $this->availableCountries, true)) {
                $this->ioWriter->error(\Safe\sprintf('The country "%s" you requested is not available on GeoNames', $requestedCountry));

                return false;
            }
        }

        return true;
    }

    /**
     * @param OutputInterface $output
     * @param array           $requestedCountries
     *
     * @throws BuildException
     * @throws \Safe\Exceptions\FilesystemException
     * @throws \Safe\Exceptions\StringsException
     */
    private function processRequestedCountries(OutputInterface $output, array $requestedCountries):void
    {
        $this->ioWriter->writeln('Starting to process requested countries');
        $progress = new ProgressBar($output, count($requestedCountries));
        foreach ($requestedCountries as $requestedCountry) {
            $sectionOutput = $output->section();
            $this->processRequestedCountry($sectionOutput, $requestedCountry);
            $sectionOutput->clear();
            $progress->advance();
        }
    }

    /**
     * @param ConsoleSectionOutput $output
     * @param string               $requestedCountry
     *
     * @throws BuildException
     * @throws \Safe\Exceptions\FilesystemException
     * @throws \Safe\Exceptions\StringsException
     */
    private function processRequestedCountry(ConsoleSectionOutput $output, string $requestedCountry)
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

    /**
     * @param ConsoleSectionOutput $output
     * @param string               $requestedCountry
     *
     * @return string
     * @throws \Safe\Exceptions\FilesystemException
     * @throws \Safe\Exceptions\StringsException
     *
     * Thanks to
     * - https://gist.github.com/devNoiseConsulting/fb6195fbd09bfb2c1f81367dd9e727ed
     */
    private function downloadRequestedCountry(ConsoleSectionOutput $output, string $requestedCountry):string
    {
        $tmpFileName = \Safe\tempnam(sys_get_temp_dir(), 'geobuilder');
        $progress = new ProgressBar($output, 0, 1);
        $this->client->request('GET', \Safe\sprintf('%s%s%s', self::DATA_URL, strtoupper($requestedCountry), self::EXT), [
            RequestOptions::SINK => $tmpFileName,
            // Vote up https://stackoverflow.com/a/34923682/1399706
            RequestOptions::PROGRESS => static function(int $dlSize, int $dlDownloaded, int $ulSize, int $ulUploaded) use ($progress):void {
                if (0 < $dlSize && 0 === $progress->getMaxSteps()) {
                    $progress->setMaxSteps($dlSize);
                }

                if (0 < $dlDownloaded) {
                    $progress->setProgress($dlDownloaded);
                }
            }
        ]);

        $progress->finish();
        $progress->clear();
        $output->clear();

        return $tmpFileName;
    }

    /**
     * @param string $downloadedCountry
     *
     * @return string
     * @throws \Safe\Exceptions\FilesystemException
     */
    private function unzipDownloadedCountry(string $downloadedCountry):string
    {
        $zip = new \ZipArchive();
        $res = $zip->open($downloadedCountry);

        if (false === $res) {
            throw new \RuntimeException('Impossile to unzip the source file of the country.');
        }

        $tmpFolder = \Safe\tempnam(sys_get_temp_dir(), 'geobuilder');
        if (false === is_dir($tmpFolder)) {
            if (file_exists($tmpFolder)) {
                unlink($tmpFolder);
            }

            if ( ! mkdir($tmpFolder) && ! is_dir($tmpFolder)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $tmpFolder));
            }
        }

        $zip->extractTo($tmpFolder);
        $zip->close();

        return $tmpFolder;
    }

    /**
     * @param string $requestedCountry
     * @param string $unzippedCountry
     *
     * @return array
     * @throws BuildException
     * @throws \Safe\Exceptions\StringsException
     */
    private function decodeUnzippedCountry(string $requestedCountry, string $unzippedCountry):array
    {
        $fileName = null;
        if (2 === strlen($requestedCountry)) {
            $fileName = \Safe\sprintf('%s.txt', strtoupper($requestedCountry));
        }

        $filePath = \Safe\sprintf('%s/%s', $unzippedCountry, $fileName);

        if (false === file_exists($filePath)) {
            throw BuildException::noSourceFileFound($requestedCountry);
        }

        return Parser::parse($filePath);
    }
}

<?php
/**
 * This is an automatically generated baseline for Phan issues.
 * When Phan is invoked with --load-baseline=path/to/baseline.php,
 * The pre-existing issues listed in this file won't be emitted.
 *
 * This file can be updated by invoking Phan with --save-baseline=path/to/baseline.php
 * (can be combined with --load-baseline)
 */
return [
    // # Issue statistics:
    // PhanRedefinedClassReference : 30+ occurrences
    // PhanUndeclaredStaticMethod : 15+ occurrences
    // PhanRedefinedExtendedClass : 4 occurrences
    // PhanUndeclaredProperty : 4 occurrences
    // PhanUndeclaredMethod : 3 occurrences
    // PhanUnreferencedUseNormal : 3 occurrences
    // PhanTypeMismatchArgumentNullableInternal : 1 occurrence
    // PhanTypeMismatchArgumentSuperType : 1 occurrence
    // PhanUndeclaredExtendedClass : 1 occurrence
    // PhanUnreferencedProtectedProperty : 1 occurrence

    'file_suppressions' => [
        'src/Bridge/Symfony/Form/Type/HierarchyJsonType.php' => [
            'PhanUnreferencedUseNormal' => ['src/Bridge/Symfony/Form/Type/HierarchyJsonType.php']
        ],
        'src/Command/BuildCommand.php' => [
            'PhanRedefinedClassReference' => ['\\SerendipityHQ\\Component\\GeoBuilder\\Command\\BuildCommand', '\\SerendipityHQ\\Component\\GeoBuilder\\Command\\BuildCommand::__construct', '\\SerendipityHQ\\Component\\GeoBuilder\\Command\\BuildCommand::checkRequestedCountriesAreAvailable', '\\SerendipityHQ\\Component\\GeoBuilder\\Command\\BuildCommand::configure', '\\SerendipityHQ\\Component\\GeoBuilder\\Command\\BuildCommand::downloadRequestedCountry', '\\SerendipityHQ\\Component\\GeoBuilder\\Command\\BuildCommand::execute', '\\SerendipityHQ\\Component\\GeoBuilder\\Command\\BuildCommand::processRequestedCountries', '\\SerendipityHQ\\Component\\GeoBuilder\\Command\\BuildCommand::processRequestedCountry', '\\SerendipityHQ\\Component\\GeoBuilder\\Command\\BuildCommand::scrapeListOfCountries', '\\closure'],
            'PhanRedefinedExtendedClass' => ['src/Command/BuildCommand.php'],
            'PhanTypeMismatchArgumentNullableInternal' => ['\\SerendipityHQ\\Component\\GeoBuilder\\Command\\BuildCommand::decodeUnzippedCountry'],
            'PhanTypeMismatchArgumentSuperType' => ['\\SerendipityHQ\\Component\\GeoBuilder\\Command\\BuildCommand::scrapeListOfCountries'],
            'PhanUndeclaredMethod' => ['\\SerendipityHQ\\Component\\GeoBuilder\\Command\\BuildCommand::processRequestedCountries'],
            'PhanUnreferencedProtectedProperty' => ['\\SerendipityHQ\\Component\\GeoBuilder\\Command\\BuildCommand']
        ],
        'src/Exception/BuildException.php' => [
            'PhanUnreferencedUseNormal' => ['src/Exception/BuildException.php']
        ],
        'tests/Bridge/Symfony/Form/Type/HierarchyJsonTypeTest.php' => [
            'PhanUndeclaredExtendedClass' => ['tests/Bridge/Symfony/Form/Type/HierarchyJsonTypeTest.php'],
            'PhanUndeclaredMethod' => ['\\SerendipityHQ\\Component\\GeoBuilder\\Tests\\Bridge\\Symfony\\Form\\Type\\HierarchyJsonTypeTest::testHierarchyJsonTypeRequiresCountry'],
            'PhanUndeclaredProperty' => ['\\SerendipityHQ\\Component\\GeoBuilder\\Tests\\Bridge\\Symfony\\Form\\Type\\HierarchyJsonTypeTest::testHierarchyJsonTypeRequiresCountry', '\\SerendipityHQ\\Component\\GeoBuilder\\Tests\\Bridge\\Symfony\\Form\\Type\\HierarchyJsonTypeTest::testHierarchyJsonTypeShowsOnlyAdmin1', '\\SerendipityHQ\\Component\\GeoBuilder\\Tests\\Bridge\\Symfony\\Form\\Type\\HierarchyJsonTypeTest::testHierarchyJsonTypeShowsOnlyAdmin1AndAdmin2', '\\SerendipityHQ\\Component\\GeoBuilder\\Tests\\Bridge\\Symfony\\Form\\Type\\HierarchyJsonTypeTest::testHierarchyJsonTypeShowsOnlyAdmin1AndAdmin2AndAdmin3'],
            'PhanUndeclaredStaticMethod' => ['\\SerendipityHQ\\Component\\GeoBuilder\\Tests\\Bridge\\Symfony\\Form\\Type\\HierarchyJsonTypeTest::testHierarchyJsonTypeShowsOnlyAdmin1', '\\SerendipityHQ\\Component\\GeoBuilder\\Tests\\Bridge\\Symfony\\Form\\Type\\HierarchyJsonTypeTest::testHierarchyJsonTypeShowsOnlyAdmin1AndAdmin2', '\\SerendipityHQ\\Component\\GeoBuilder\\Tests\\Bridge\\Symfony\\Form\\Type\\HierarchyJsonTypeTest::testHierarchyJsonTypeShowsOnlyAdmin1AndAdmin2AndAdmin3']
        ],
        'tests/Exception/BuildExceptionTest.php' => [
            'PhanRedefinedExtendedClass' => ['tests/Exception/BuildExceptionTest.php'],
            'PhanUndeclaredMethod' => ['\\SerendipityHQ\\Component\\GeoBuilder\\Tests\\Exception\\BuildExceptionTest::testNoSourceFileFound']
        ],
        'tests/ParserTest.php' => [
            'PhanRedefinedExtendedClass' => ['tests/ParserTest.php'],
            'PhanUndeclaredStaticMethod' => ['\\SerendipityHQ\\Component\\GeoBuilder\\Tests\\ParserTest::testNoSourceFileFound']
        ],
        'tests/Reader/HierarchyJsonTest.php' => [
            'PhanRedefinedExtendedClass' => ['tests/Reader/HierarchyJsonTest.php'],
            'PhanUndeclaredStaticMethod' => ['\\SerendipityHQ\\Component\\GeoBuilder\\Tests\\Reader\\HierarchyJsonTest::testDumperAndReader'],
            'PhanUnreferencedUseNormal' => ['tests/Reader/HierarchyJsonTest.php']
        ],
    ],
    // 'directory_suppressions' => ['src/directory_name' => ['PhanIssueName1', 'PhanIssueName2']] can be manually added if needed.
    // (directory_suppressions will currently be ignored by subsequent calls to --save-baseline, but may be preserved in future Phan releases)
];

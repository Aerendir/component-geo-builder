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
    // PhanUnreferencedUseNormal : 2 occurrences
    // PhanUndeclaredExtendedClass : 1 occurrence
    // PhanUnreferencedClosure : 1 occurrence

    // Currently, file_suppressions and directory_suppressions are the only supported suppressions
    'file_suppressions' => [
        'src/Bridge/Symfony/Form/Type/HierarchyJsonType.php' => ['PhanUnreferencedUseNormal'],
        'src/Command/BuildCommand.php' => ['PhanRedefinedClassReference', 'PhanRedefinedExtendedClass', 'PhanUndeclaredMethod', 'PhanUnreferencedClosure'],
        'tests/Bridge/Symfony/Form/Type/HierarchyJsonTypeTest.php' => ['PhanUndeclaredExtendedClass', 'PhanUndeclaredMethod', 'PhanUndeclaredProperty', 'PhanUndeclaredStaticMethod'],
        'tests/Exception/BuildExceptionTest.php' => ['PhanRedefinedExtendedClass', 'PhanUndeclaredMethod'],
        'tests/ParserTest.php' => ['PhanRedefinedExtendedClass', 'PhanUndeclaredStaticMethod'],
        'tests/Reader/HierarchyJsonTest.php' => ['PhanRedefinedExtendedClass', 'PhanUndeclaredStaticMethod', 'PhanUnreferencedUseNormal'],
    ],
    // 'directory_suppressions' => ['src/directory_name' => ['PhanIssueName1', 'PhanIssueName2']] can be manually added if needed.
    // (directory_suppressions will currently be ignored by subsequent calls to --save-baseline, but may be preserved in future Phan releases)
];

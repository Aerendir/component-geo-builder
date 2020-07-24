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
    // PhanRedefinedClassReference : 20+ occurrences
    // PhanUndeclaredStaticMethod : 10+ occurrences
    // PhanUndeclaredProperty : 4 occurrences
    // PhanUndeclaredMethod : 2 occurrences
    // PhanUnreferencedUseNormal : 2 occurrences
    // PhanRedefinedExtendedClass : 1 occurrence
    // PhanUndeclaredExtendedClass : 1 occurrence
    // PhanUnextractableAnnotation : 1 occurrence
    // PhanUnreferencedClosure : 1 occurrence
    // PhanUnreferencedProtectedProperty : 1 occurrence

    // Currently, file_suppressions and directory_suppressions are the only supported suppressions
    'file_suppressions' => [
        'src/Bridge/Symfony/Form/Type/HierarchyJsonType.php' => ['PhanUnreferencedUseNormal'],
        'src/Command/BuildCommand.php' => ['PhanRedefinedClassReference', 'PhanRedefinedExtendedClass', 'PhanUndeclaredMethod', 'PhanUnextractableAnnotation', 'PhanUnreferencedClosure', 'PhanUnreferencedProtectedProperty'],
        'tests/Bridge/Symfony/Form/Type/HierarchyJsonTypeTest.php' => ['PhanUndeclaredExtendedClass', 'PhanUndeclaredMethod', 'PhanUndeclaredProperty', 'PhanUndeclaredStaticMethod'],
        'tests/Reader/HierarchyJsonTest.php' => ['PhanUnreferencedUseNormal'],
    ],
    // 'directory_suppressions' => ['src/directory_name' => ['PhanIssueName1', 'PhanIssueName2']] can be manually added if needed.
    // (directory_suppressions will currently be ignored by subsequent calls to --save-baseline, but may be preserved in future Phan releases)
];

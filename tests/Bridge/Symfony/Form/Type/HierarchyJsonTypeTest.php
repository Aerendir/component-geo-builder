<?php

/*
 * This file is part of the Serendipity HQ Geo Builder Component.
 *
 * Copyright (c) Adamo Aerendir Crespi <aerendir@serendipityhq.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SerendipityHQ\Component\GeoBuilder\Tests\Bridge\Symfony\Form\Type;

use SerendipityHQ\Component\GeoBuilder\Bridge\Symfony\Form\Type\HierarchyJsonType;
use SerendipityHQ\Component\GeoBuilder\Reader\HierarchyJsonReader;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * Tests the HierarchyJsonTypeTest class.
 */
final class HierarchyJsonTypeTest extends TypeTestCase
{
    public function testHierarchyJsonTypeRequiresCountry(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->factory->create(HierarchyJsonType::class, null, ['data_class' => null]);
    }

    public function testHierarchyJsonTypeShowsOnlyAdmin1(): void
    {
        $form = $this->factory->create(HierarchyJsonType::class, [], ['data_class' => null, HierarchyJsonType::COUNTRY_FIELD => 'IT']);

        // submit the data to the form directly
        $form->submit([]);

        self::assertTrue($form->isSynchronized());

        $view     = $form->createView();
        $children = $view->children;

        self::assertArrayHasKey(HierarchyJsonType::ADMIN1_FIELD, $children);
        self::assertArrayNotHasKey(HierarchyJsonType::ADMIN2_FIELD, $children);
        self::assertArrayNotHasKey(HierarchyJsonType::ADMIN3_FIELD, $children);
    }

    public function testHierarchyJsonTypeShowsOnlyAdmin1AndAdmin2(): void
    {
        $expected = [
            HierarchyJsonType::ADMIN1_FIELD => 'CM',
            HierarchyJsonType::ADMIN2_FIELD => null,
        ];

        $values         = new \stdClass();
        $values->admin1 = $expected[HierarchyJsonType::ADMIN1_FIELD];
        $values->admin2 = null;
        $values->admin3 = null;

        $form = $this->factory->create(HierarchyJsonType::class, [], ['data_class' => null, HierarchyJsonType::COUNTRY_FIELD => 'IT']);

        // submit the data to the form directly
        $form->submit($values);

        $view     = $form->createView();
        $children = $view->children;

        self::assertArrayHasKey(HierarchyJsonType::ADMIN1_FIELD, $children);
        self::assertArrayHasKey(HierarchyJsonType::ADMIN2_FIELD, $children);
        self::assertArrayNotHasKey(HierarchyJsonType::ADMIN3_FIELD, $children);
    }

    public function testHierarchyJsonTypeShowsOnlyAdmin1AndAdmin2AndAdmin3(): void
    {
        $expected = [
            HierarchyJsonType::ADMIN1_FIELD => 'CM',
            HierarchyJsonType::ADMIN2_FIELD => 'NA',
            HierarchyJsonType::ADMIN3_FIELD => null,
        ];

        $values         = new \stdClass();
        $values->admin1 = $expected[HierarchyJsonType::ADMIN1_FIELD];
        $values->admin2 = $expected[HierarchyJsonType::ADMIN2_FIELD];
        $values->admin3 = $expected[HierarchyJsonType::ADMIN3_FIELD];

        $form = $this->factory->create(HierarchyJsonType::class, [], ['data_class' => null, HierarchyJsonType::COUNTRY_FIELD => 'IT']);

        // submit the data to the form directly
        $form->submit($values);

        $view     = $form->createView();
        $children = $view->children;

        self::assertArrayHasKey(HierarchyJsonType::ADMIN1_FIELD, $children);
        self::assertArrayHasKey(HierarchyJsonType::ADMIN2_FIELD, $children);
        self::assertArrayHasKey(HierarchyJsonType::ADMIN3_FIELD, $children);
    }

    protected function getExtensions(): array
    {
        $parsedFixtures      = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'parsed';
        $hierarchyJsonReader = new HierarchyJsonReader($parsedFixtures);
        // create a type instance with the mocked dependencies
        $type = new HierarchyJsonType($hierarchyJsonReader);

        return [
            // register the type instances with the PreloadedExtension
            new PreloadedExtension([$type], []),
        ];
    }
}

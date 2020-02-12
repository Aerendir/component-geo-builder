<?php

/*
 * This file is part of GeoBuilder.
 *
 * Copyright Adamo Aerendir Crespi 2020.
 *
 * @author    Adamo Aerendir Crespi <hello@aerendir.me>
 * @copyright Copyright (C) 2020 Aerendir. All rights reserved.
 * @license   MIT
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
class HierarchyJsonTypeTest extends TypeTestCase
{
    public function testHierarchyJsonTypeRequiresCountry(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->factory->create(HierarchyJsonType::class, null, ['data_class' => null]);
    }

    public function testHierarchyJsonTypeShowsOnlyAdmin1(): void
    {
        $form = $this->factory->create(HierarchyJsonType::class, [], ['data_class' => null, 'country' => 'IT']);

        // submit the data to the form directly
        $form->submit([]);

        $this::assertTrue($form->isSynchronized());

        $view     = $form->createView();
        $children = $view->children;

        $this::assertArrayHasKey('admin1', $children);
        $this::assertArrayNotHasKey('admin2', $children);
        $this::assertArrayNotHasKey('admin3', $children);
    }

    public function testHierarchyJsonTypeShowsOnlyAdmin1AndAdmin2(): void
    {
        $expected = [
            'admin1' => 'CM',
            'admin2' => null,
        ];

        $values         = new \stdClass();
        $values->admin1 = $expected['admin1'];
        $values->admin2 = null;
        $values->admin3 = null;

        $form = $this->factory->create(HierarchyJsonType::class, [], ['data_class' => null, 'country' => 'IT']);

        // submit the data to the form directly
        $form->submit($values);

        $view     = $form->createView();
        $children = $view->children;

        $this::assertArrayHasKey('admin1', $children);
        $this::assertArrayHasKey('admin2', $children);
        $this::assertArrayNotHasKey('admin3', $children);
    }

    public function testHierarchyJsonTypeShowsOnlyAdmin1AndAdmin2AndAdmin3(): void
    {
        $expected = [
            'admin1' => 'CM',
            'admin2' => 'NA',
            'admin3' => null,
        ];

        $values         = new \stdClass();
        $values->admin1 = $expected['admin1'];
        $values->admin2 = $expected['admin2'];
        $values->admin3 = $expected['admin3'];

        $form = $this->factory->create(HierarchyJsonType::class, [], ['data_class' => null, 'country' => 'IT']);

        // submit the data to the form directly
        $form->submit($values);

        $view     = $form->createView();
        $children = $view->children;

        $this::assertArrayHasKey('admin1', $children);
        $this::assertArrayHasKey('admin2', $children);
        $this::assertArrayHasKey('admin3', $children);
    }

    /**
     * @return array
     */
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

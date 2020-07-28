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

namespace SerendipityHQ\Component\GeoBuilder\Bridge\Symfony\Form\Type;

use App\Entity\User;
use SerendipityHQ\Component\GeoBuilder\Reader\HierarchyJsonReader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Form type to manage User's permissions.
 * @see \SerendipityHQ\Component\GeoBuilder\Tests\Bridge\Symfony\Form\Type\HierarchyJsonTypeTest
 */
final class HierarchyJsonType extends AbstractType
{
    /** @var string */
    public const COUNTRY_FIELD = 'country';

    /** @var string */
    public const ADMIN1_FIELD = 'admin1';

    /** @var string */
    public const ADMIN2_FIELD = 'admin2';

    /** @var string */
    public const ADMIN3_FIELD = 'admin3';

    /** @var string */
    private const CHOICES = 'choices';

    /** @var string */
    private const PLACEHOLDER = 'placeholder';

    /** @var string */
    private const REQUIRED = 'required';

    /** @var HierarchyJsonReader $reader */
    private $reader;

    /** @var PropertyAccessor $propertyAccessor*/
    private $propertyAccessor;

    /**
     * @param HierarchyJsonReader $reader
     */
    public function __construct(HierarchyJsonReader $reader)
    {
        $this->reader           = $reader;
        $this->propertyAccessor = new PropertyAccessor();
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array<string,mixed>  $options
     *
     * @suppress PhanUnusedPublicMethodParameter
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = $this->reader->read($options[self::COUNTRY_FIELD]);
        $choices = $this->prepareChoices($choices);
        $builder->add(self::ADMIN1_FIELD, ChoiceType::class, [self::CHOICES => $choices, self::PLACEHOLDER => '', self::REQUIRED => false]);

        $this->addAdmin2Field($builder, $options);
        $this->addAdmin3Field($builder, $options);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function addAdmin2Field(FormBuilderInterface $builder, array $options): void
    {
        $type          = $this;
        $addAccessForm = static function (FormEvent $event) use ($options, $type): void {
            $data = $event->getData();

            if (\is_array($data)) {
                return;
            }

            if (null !== $type->propertyAccessor->getValue($data, self::ADMIN1_FIELD)) {
                $choices = $type->reader->read($options[self::COUNTRY_FIELD], $type->propertyAccessor->getValue($data, self::ADMIN1_FIELD));
                $choices = $type->prepareChoices($choices);
                $event->getForm()->add(self::ADMIN2_FIELD, ChoiceType::class, [self::CHOICES => $choices, self::PLACEHOLDER => '', self::REQUIRED => false]);
            }
        };

        $builder->addEventListener(FormEvents::POST_SET_DATA, $addAccessForm);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, $addAccessForm);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function addAdmin3Field(FormBuilderInterface $builder, array $options): void
    {
        $type          = $this;
        $addAccessForm = static function (FormEvent $event) use ($options, $type): void {
            $data = $event->getData();

            if (\is_array($data)) {
                return;
            }

            if (null !== $type->propertyAccessor->getValue($data, self::ADMIN1_FIELD) && null !== $type->propertyAccessor->getValue($data, self::ADMIN2_FIELD)) {
                $choices = $type->reader->read($options[self::COUNTRY_FIELD], $type->propertyAccessor->getValue($data, self::ADMIN1_FIELD), $type->propertyAccessor->getValue($data, self::ADMIN2_FIELD));
                $choices = $type->prepareChoices($choices);
                $event->getForm()->add('admin3', ChoiceType::class, [self::CHOICES => $choices, self::PLACEHOLDER => '', self::REQUIRED => false]);
            }
        };

        $builder->addEventListener(FormEvents::POST_SET_DATA, $addAccessForm);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, $addAccessForm);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            self::COUNTRY_FIELD => null,
                               ]);

        $resolver->setAllowedTypes(self::COUNTRY_FIELD, ['string']);
    }

    /**
     * @param array $values
     *
     * @return array
     */
    private function prepareChoices(array $values): array
    {
        \Safe\asort($values, SORT_NATURAL);

        return \Safe\array_flip($values);
    }
}

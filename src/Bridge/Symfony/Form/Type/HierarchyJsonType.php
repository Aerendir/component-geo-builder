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
 */
class HierarchyJsonType extends AbstractType
{
    /** @var HierarchyJsonReader $reader */
    private $reader;

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
        $choices = $this->reader->read($options['country']);
        $choices = $this->prepareChoices($choices);
        $builder->add('admin1', ChoiceType::class, ['choices' => $choices, 'placeholder' => '', 'required' => false]);

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
        $addAccessForm = static function (FormEvent $event) use ($options, $type) {
            $data = $event->getData();

            if (is_array($data)) {
                return;
            }

            if (null !== $type->propertyAccessor->getValue($data, 'admin1')) {
                $choices = $type->reader->read($options['country'], $type->propertyAccessor->getValue($data, 'admin1'));
                $choices = $type->prepareChoices($choices);
                $event->getForm()->add('admin2', ChoiceType::class, ['choices' => $choices, 'placeholder' => '', 'required' => false]);
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
        $addAccessForm = static function (FormEvent $event) use ($options, $type) {
            $data = $event->getData();

            if (is_array($data)) {
                return;
            }

            if (null !== $type->propertyAccessor->getValue($data, 'admin1') && null !== $type->propertyAccessor->getValue($data, 'admin2')) {
                $choices = $type->reader->read($options['country'], $type->propertyAccessor->getValue($data, 'admin1'), $type->propertyAccessor->getValue($data, 'admin2'));
                $choices = $type->prepareChoices($choices);
                $event->getForm()->add('admin3', ChoiceType::class, ['choices' => $choices, 'placeholder' => '', 'required' => false]);
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
            'country' => null,
                               ]);

        $resolver->setAllowedTypes('country', ['string']);
    }

    /**
     * @param array $values
     *
     * @return array
     */
    private function prepareChoices(array $values): array
    {
        asort($values, SORT_NATURAL);
        $values = array_flip($values);

        return $values;
    }
}

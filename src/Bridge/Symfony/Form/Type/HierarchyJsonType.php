<?php

/*
 * This file is part of the Serendipity HQ Geo Builder Component.
 *
 * Copyright (c) Adamo Aerendir Crespi <aerendir@serendipityhq.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SerendipityHQ\Component\GeoBuilder\Bridge\Symfony\Form\Type;

use App\Entity\User;
use function Safe\array_flip;
use function Safe\asort;
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
 *
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

    /** @var PropertyAccessor $propertyAccessor */
    private $propertyAccessor;

    public function __construct(HierarchyJsonReader $reader)
    {
        $this->reader           = $reader;
        $this->propertyAccessor = new PropertyAccessor();
    }

    /**
     * @param array<string,mixed> $options
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            self::COUNTRY_FIELD => null,
                               ]);

        $resolver->setAllowedTypes(self::COUNTRY_FIELD, ['string']);
    }

    private function prepareChoices(array $values): array
    {
        asort($values, SORT_NATURAL);

        return array_flip($values);
    }
}

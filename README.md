<p align="center">
    <a href="http://www.serendipityhq.com" target="_blank">
        <img style="max-width: 350px" src="http://www.serendipityhq.com/assets/open-source-projects/Logo-SerendipityHQ-Icon-Text-Purple.png">
    </a>
</p>

<h1 align="center">Serendipity HQ Geo Builder</h1>
<p align="center">
    Parses the exports of countries from Geonames and exports the data in machine readable formats.<br />
    It downloads information of countries from [Geonames exports](https://www.geonames.org/export/zip/).<br />
    Ready to be integrated in Symfony apps.
</p>
<p align="center">
    <a href="https://github.com/Aerendir/component-geo-builder/releases"><img src="https://img.shields.io/packagist/v/serendipity_hq/component-geo-builder.svg?style=flat-square"></a>
    <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square"></a>
    <a href="https://github.com/Aerendir/component-geo-builder/releases"><img src="https://img.shields.io/packagist/php-v/serendipity_hq/component-geo-builder?color=%238892BF&style=flat-square&logo=php" /></a>
</p>
<p align="center">
    <a href="https://symfony.com/doc/current/forms.html"><img src="https://img.shields.io/badge/Suggests-symfony/form-%238892BF?style=flat-square&logo=php"></a>
</p>
<p>
    Supports <br />
    <a title="Supports Symfony ^4.4" href="https://github.com/Aerendir/component-geo-builder/actions?query=branch%3Adev"><img title="Supports Symfony ^4.4" src="https://img.shields.io/badge/Symfony-%5E4.4-333?style=flat-square&logo=symfony" /></a>
    <a title="Supports Symfony ^5.4" href="https://github.com/Aerendir/component-geo-builder/actions?query=branch%3Adev"><img title="Supports Symfony ^5.4" src="https://img.shields.io/badge/Symfony-%5E5.4-333?style=flat-square&logo=symfony" /></a>
    <a title="Supports Symfony ^6.0" href="https://github.com/Aerendir/component-geo-builder/actions?query=branch%3Adev"><img title="Supports Symfony ^6.0" src="https://img.shields.io/badge/Symfony-%5E6.0-333?style=flat-square&logo=symfony" /></a>
</p>
<p>
    Tested on <br />
    <a title="Tested with Symfony ^4.4" href="https://github.com/Aerendir/component-geo-builder/actions?query=branch%3Adev"><img title="Tested with Symfony ^4.4" src="https://img.shields.io/badge/Symfony-%5E4.4-333?style=flat-square&logo=symfony" /></a>
    <a title="Tested with Symfony ^5.4" href="https://github.com/Aerendir/component-geo-builder/actions?query=branch%3Adev"><img title="Tested with Symfony ^5.4" src="https://img.shields.io/badge/Symfony-%5E5.4-333?style=flat-square&logo=symfony" /></a>
    <a title="Tested with Symfony ^6.0" href="https://github.com/Aerendir/component-geo-builder/actions?query=branch%3Adev"><img title="Tested with Symfony ^6.0" src="https://img.shields.io/badge/Symfony-%5E6.0-333?style=flat-square&logo=symfony" /></a>
</p>

## Current Status
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=Aerendir_component-geo-builder&metric=coverage)](https://sonarcloud.io/dashboard?id=Aerendir_component-geo-builder)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=Aerendir_component-geo-builder&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=Aerendir_component-geo-builder)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=Aerendir_component-geo-builder&metric=alert_status)](https://sonarcloud.io/dashboard?id=Aerendir_component-geo-builder)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=Aerendir_component-geo-builder&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=Aerendir_component-geo-builder)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=Aerendir_component-geo-builder&metric=security_rating)](https://sonarcloud.io/dashboard?id=Aerendir_component-geo-builder)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=Aerendir_component-geo-builder&metric=sqale_index)](https://sonarcloud.io/dashboard?id=Aerendir_component-geo-builder)
[![Vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=Aerendir_component-geo-builder&metric=vulnerabilities)](https://sonarcloud.io/dashboard?id=Aerendir_component-geo-builder)

[![Phan](https://github.com/Aerendir/component-geo-builder/workflows/Phan/badge.svg)](https://github.com/Aerendir/component-geo-builder/actions?query=branch%3Adev)
[![PHPStan](https://github.com/Aerendir/component-geo-builder/workflows/PHPStan/badge.svg)](https://github.com/Aerendir/component-geo-builder/actions?query=branch%3Adev)
[![PSalm](https://github.com/Aerendir/component-geo-builder/workflows/PSalm/badge.svg)](https://github.com/Aerendir/component-geo-builder/actions?query=branch%3Adev)
[![PHPUnit](https://github.com/Aerendir/component-geo-builder/workflows/PHPunit/badge.svg)](https://github.com/Aerendir/component-geo-builder/actions?query=branch%3Adev)
[![Composer](https://github.com/Aerendir/component-geo-builder/workflows/Composer/badge.svg)](https://github.com/Aerendir/component-geo-builder/actions?query=branch%3Adev)
[![PHP CS Fixer](https://github.com/Aerendir/component-geo-builder/workflows/PHP%20CS%20Fixer/badge.svg)](https://github.com/Aerendir/component-geo-builder/actions?query=branch%3Adev)
[![Rector](https://github.com/Aerendir/component-geo-builder/workflows/Rector/badge.svg)](https://github.com/Aerendir/component-geo-builder/actions?query=branch%3Adev)

## Features

- Download the exports of countries
- Build you custom lists of countries to use in your app

<hr />
<h3 align="center">
    <b>Do you like this library?</b><br />
    <b><a href="#js-repo-pjax-container">LEAVE A &#9733;</a></b>
</h3>
<p align="center">
    or run<br />
    <code>composer global require symfony/thanks && composer thanks</code><br />
    to say thank you to all libraries you use in your current project, this included!
</p>
<hr />

## Installation and Configuration
### Install Component GeoBuilder via Composer

    composer req serendipity_hq/component-geo-builder

This library follows the http://semver.org/ versioning conventions.

However, until the version 1, the minor release is treated like a major one.

So it is possible a break in the public API between minor versions (0.1 > 0.2 > 0.3).).

The component is anyway stable and can be used in production, also if it is not very flexible.

See the issues to know more about what we have in mind to implement.

### Register the command in a Symfony application

Open the file `config/services.yaml` and add the class of the `geobuilder` command:

```yaml
# config/services.yaml

services:
    ...
    SerendipityHQ\Component\GeoBuilder\Command\BuildCommand:
        $dumpDir: '%kernel.cache_dir%/geobuilder'

    # You also need to autowire the Guzzle CLient if you don't already have one
    GuzzleHttp\Client: ~
    ...
```

## Building the list you need

To build the list you need, simply launch the command `geobuilder:build` appending the countries you want to build lists for:

```console
$ bin/console geobuilder:build it de
```

By default the command uses the Hierarchy reader and will create a lot of `json` files in the `kernel.cache_dir/geobuilder` folder of your Symfony App.

You can read more about readers, saving folders and more reading the full documentation.

For the moment, let's continue putting `GeoBuilder` at work.

The next step is to create a form with the data we have just built.

## Creating the form

To use the form types, you need to register them as services, so they can be properly initialized by Symfony.

To register them, open the file `config/services.yaml` and add the `HierarchyJsonType`:

```yaml
# config/services.yaml

services:
    ...
    SerendipityHQ\Component\GeoBuilder\Reader\HierarchyJsonReader:
        $dataFolderPath: '%kernel.cache_dir%/geobuilder'
    SerendipityHQ\Component\GeoBuilder\Bridge\Symfony\Form\Type\HierarchyJsonType: ~
```

Then in your form you can add the `HierarchyJsonType`:

```php

class UserZoneType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array<string,mixed>  $options
     * @suppress PhanUnusedPublicMethodParameter
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('geobuilder', HierarchyJsonType::class, ['country' => 'it']);
    }
}
```

## About the `countries.json` file

This file will contain only the countries you built with the `bin/console geobuilder:build` command.

You can get a complete list of localized countries using the `Countries::getNames()` method of the `symfony/intl` component.

But the Symfony component will return all countries in the world, also if you didn't built them.

<hr />
<h3 align="center">
    <b>Do you like this library?</b><br />
    <b><a href="#js-repo-pjax-container">LEAVE A &#9733;</a></b>
</h3>
<p align="center">
    or run<br />
    <code>composer global require symfony/thanks && composer thanks</code><br />
    to say thank you to all libraries you use in your current project, this included!
</p>
<hr />

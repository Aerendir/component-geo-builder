---
---
mode: 'agent'
description: 'Update Symfony version constraints (library-safe)'
---

You are working on a PHP **library** repository (not an application). Follow the repository's AGENTS.md rules.

Task: update the supported Symfony versions for this library.

Target Symfony versions to support: \${input\:symfony\_versions\:Enter the Symfony major/minor ranges to support (e.g., "^6.4 || ^7.1")}

# How to proceed

The upgrade process must be done in two steps:

1. "Static" update of some relevant files (e.g., `composer.json`).
2. Code execution to ensure all works well.

## 1. Static update of relevant files

To update the Symfony versions you have to update many files.

### Update `composer.json`

- Update the version of all Symfony components in the `require` and `require-dev` sections according to "Target Symfony versions to support". (E.g., `"symfony/http-foundation": "^6.4 || ^7.1"`)

### Update `Makefile`

- Update the variable `SF_V` to the lowest supported version according to "Target Symfony versions to support". (E.g., `SF_V=6.4`)

### Update `README.md`

- Under the section "Supports", update the badges that show the supported Symfony versions.
- Under the section "Tested on", update the badges that show the tested Symfony versions.

### Update GitHub Actions workflows

- `.github/workflows/phan.yml`: add ONLY the lowest supported version to the array at `jobs.phan.strategy.matrix.symfony`
- `.github/workflows/php-cs-fixer.yml`: add ONLY the lowest supported version to the array at `jobs.phpcs.strategy.matrix.symfony`
- `.github/workflows/phpstan.yml`: add ONLY the lowest supported version to the array at `jobs.phpstan.strategy.matrix.symfony`
- `.github/workflows/psalm.yml`: add ONLY the lowest supported version to the array at `jobs.psalm.strategy.matrix.symfony`
- `.github/workflows/psalm.yml`: add ONLY the lowest supported version to the array at `jobs.rector.strategy.matrix.symfony`
- `.github/workflows/phpunit.yml`: add ANY supported version to the array `jobs.phpunit.strategy.matrix.symfony`

## 2. Code execution to ensure all works well

This will be taken care of by the HUMAN after you finish the static updates.

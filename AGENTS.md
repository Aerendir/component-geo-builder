# AGENTS Guidelines for this repository

This document defines how automated agents (AI tools, bots, and assistants) should interact with this PHP library repository. Its goal is to ensure consistency, safety, and alignment with the project's technical and maintenance standards.

This repository contains a PHP library compatible with all currently supported PHP versions. When Symfony components are used, they are compatible with all supported Symfony versions. When writing code, please, ensure the use of the functions and classes supported by the lowest supported version.

The `composer.json` file indicates which are the lowest supported versions of PHP and Symfony components.

## Scope of Agent Actions

- The agent MUST do everything necessary to complete the assigned task.
- All actions must remain within the boundaries and constraints defined in this document.
- The agent MUST NOT exceed the scope of the task unless explicitly instructed.

## General Principles for Agents

- Prefer **clarity and maintainability** over clever or overly compact solutions.
- Follow existing coding style and architectural patterns found in the repository.
- Avoid introducing breaking changes unless explicitly instructed.
- Do not remove backward compatibility without clear justification.

## PHP Language Features Policy

- The agent MUST use only PHP language features that are supported by the **lowest supported PHP version** as defined in `composer.json`.
- The agent MUST NOT introduce syntax, functions, or language features that require a higher PHP version, even if they are available in newer versions.
- If the codebase already uses a specific PHP feature, the agent MAY continue using it consistently, but MUST NOT expand its usage beyond the existing scope.
- **Polyfills are NOT allowed**. The agent MUST NOT introduce polyfills, compatibility layers, or conditional shims to emulate newer PHP features.
- The agent MUST assume that the code will be executed in environments running the lowest supported PHP version.

## Behavioral Backward Compatibility

- The agent MUST preserve the existing runtime behavior of the library unless a change is explicitly required by the assigned task.
- The agent MUST consider the following as **breaking changes**, even if method signatures remain unchanged:
  - changes in thrown exception types
  - changes in exception messages
  - changes in return values or return types
  - changes in default values or implicit assumptions
  - changes in side effects or execution order
- The agent MUST NOT introduce behavioral changes for "improvement" or "consistency" unless explicitly instructed.
- Refactors are allowed **only if they are behavior-preserving**.
- If a behavioral change is strictly necessary to complete the task, it MUST be clearly justified and documented.

## Security Guidelines

- The agent MUST NOT weaken the security posture of the library to improve performance, readability, or convenience.
- All external inputs MUST be treated as untrusted unless explicitly documented otherwise.
- The agent MUST preserve existing input validation, sanitization, and normalization logic.
- The agent MUST NOT remove or relax security checks, guards, or assertions, even if they appear redundant.
- When handling sensitive data, the agent MUST avoid:
  - logging sensitive values
  - exposing internal details through exception messages
  - changing error handling in ways that may leak information
- The agent MUST prefer secure defaults and fail-safe behavior when introducing or modifying logic.
- If a potential security issue is discovered while working on a task, the agent MUST highlight it clearly, even if fixing it is outside the assigned scope.

## Coding Standards

- Code MUST be written in **English**, including comments and documentation.
- Use strict typing where applicable.
- Public APIs must be documented with PHPDoc.
- Internal changes should not leak into the public API.
- Variables MUST be named using **descriptive, explicit names**. Avoid abbreviated or compressed identifiers that reduce readability.

### Examples

#### Naming variables

```php
# Avoid
->map(fn ($a) => [

# Prefer
->map(fn ($area) => [
```

## Dependency Management

- The agent MUST NOT add a `composer.lock` file to the repository. Lock files are intentionally excluded to ensure that this library can be installed with compatible dependency versions when used by other libraries or applications.
- Do not introduce new dependencies unless strictly necessary.
- When adding dependencies, the agent MUST:
  - use the **lowest possible compatible version**;
  - define **relaxed version constraints** (e.g. caret or compatible ranges) to minimize conflicts in downstream projects.
- If a dependency is added or updated, explain the rationale clearly.
- Ensure compatibility across all supported PHP and Symfony versions.

## Testing Guidelines

- All changes must be covered by automated tests where applicable.
- Tests should be compatible with all supported PHP and Symfony versions.
- Do not weaken existing test coverage.

## Useful Commands for Agents

The following commands are explicitly allowed and recommended for agent usage when appropriate:

| Command                  | Purpose                                                   | Constraints                                                                             |
| ------------------------ | --------------------------------------------------------- | --------------------------------------------------------------------------------------- |
| `composer:fix`           | Apply automated code style fixes and Rector refactorings. | MUST be executed at most once per task.                                                 |
| `composer:test:coverage` | Run the test suite with coverage reporting.               | The agent MUST ensure that the code it introduced is **100% covered by tests**.         |
| `composer:val`           | Run validation and static analysis checks.                | Results are informational and MUST NOT trigger iterative fix loops or baseline updates. |

## Command Execution and Static Analysis Policy

- The agent MAY execute validation and testing commands (e.g. running the test suite or static analyzers) to verify the correctness of the changes.
- The agent MUST execute automated fixing commands (such as code style fixes or Rector) **at most once per task**, and only when required to complete the assigned work.
- The agent MUST NOT enter iterative fix–analyze–fix loops in an attempt to fully satisfy static analysis tools.
- Static analysis tools (PHPStan, Psalm, Phan, etc.) MUST be treated as **measurement tools**, not as primary drivers of development decisions.
- The agent MAY fix static analysis errors **only if they are directly related to the assigned task**.
- The agent MUST NOT regenerate, update, or modify static analysis baselines unless explicitly instructed to do so.

## Multi-Version Compatibility and CI Matrix

- This library is expected to support multiple PHP and Symfony versions, as defined in `composer.json`.
- The agent MUST ensure logical compatibility with the lowest supported PHP and Symfony versions when writing or modifying code.
- The agent MUST NOT assume responsibility for exhaustively validating the full PHP/Symfony version matrix locally.
- Comprehensive multi-version validation is delegated to the CI pipeline (e.g. GitHub Actions) and is outside the agent’s authority unless explicitly requested.

## Versioning and Backward Compatibility

- Assume semantic versioning unless stated otherwise.
- Breaking changes require a major version bump.
- Deprecations must be explicit and documented.

## What Agents MUST NOT Do

- Do not refactor large portions of the codebase without explicit approval.
- Do not change public APIs implicitly.
- Do not introduce framework-specific assumptions unless already present.


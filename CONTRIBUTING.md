# Contributing to Temp Mail PHP Client

First off, thank you for taking the time to contribute! The following guidelines will help you make useful contributions that align with our project goals and standards.

## Table of Contents
- [Project Overview](#project-overview)
- [Getting Started](#getting-started)
- [Reporting Issues](#reporting-issues)
- [Suggesting Enhancements](#suggesting-enhancements)
- [Pull Requests](#pull-requests)
- [Code Style](#code-style)
- [Testing](#testing)
- [Community and Code of Conduct](#community-and-code-of-conduct)

## Project Overview
The **Temp Mail PHP Client** provides developers a reliable way to interact with the [Temp Mail API](https://docs.temp-mail.io). We welcome contributions that improve features, fix bugs, add documentation, or enhance test coverage.

## Getting Started
1. **Fork the Repository**: Click the "Fork" button at the top-right corner of the [main repo](https://github.com/temp-mail-io/temp-mail-php).
2. **Clone Your Fork**:
   ```bash
   git clone https://github.com/<your-username>/temp-mail-php.git
   ```
3. **Create a Branch** for your contribution:
    ```bash
    git checkout -b feature/your-feature-name
    ```
4. **Install Dependencies**:
    ```bash
    composer install
    ```
5. **Make Your Changes** in the new branch.

## Reporting Issues
If you find a bug or run into an issue, please check [existing issues](https://github.com/temp-mail-io/temp-mail-php/issues) first. If it’s not already reported:
1. Click on **New issue**.
2. Provide a clear, descriptive title.
3. Describe the steps to reproduce, the expected behavior, and the actual behavior.
4. Provide any relevant logs, error messages, or screenshots.

## Suggesting Enhancements
We’re open to new features and improvements. When creating an issue for a feature request:
1. Clearly explain the proposed feature.
2. Describe why it would be useful.
3. Include any relevant examples or mockups.

## Pull Requests
1. **Ensure your work is up to date**: Rebase your branch onto the latest `main` branch before opening a PR:
    ```bash
    git checkout main
    git pull upstream main
    git checkout feature/your-feature-name
    git rebase main
    ```
2. **Commit Messages**: Use descriptive commit messages that explain _what_ changes you made and _why_.
3. **Open a Pull Request**:
    - Ensure you have meaningful title and description.
    - Reference any related issues or pull requests.
    - Provide testing steps or instructions for how reviewers can verify your work.
4. **Code Review**: Maintainers will review your PR and may suggest changes. Please be open to feedback and update your PR accordingly.

## Code Style
- **PHP Version**: We aim to support the latest 8.1 - 8.4 PHP versions.
- **Linting**: We use [PHPStan](https://github.com/phpstan/phpstan) for consistency. The project’s CI runs lint checks automatically.
    ```bash
    ./vendor/bin/phpstan analyze src
    ```
- **Formatting**: Run the following commands before committing.
    ```bash
    PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix src
    PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix tests
    ```

## Testing
- We rely on the [PHPUnit](https://github.com/sebastianbergmann/phpunit) framework to simplify and improve readability of our tests.
- Unit Tests: Include thorough coverage where possible, testing with mocks to avoid external dependencies.
- Run the tests locally:
    ```bash
    ./vendor/bin/phpunit ./tests
    ```
- The CI (GitHub Actions) will also run tests automatically on each pull request.

## Community and Code of Conduct

We adhere to the standard [Contributor Covenant](https://www.contributor-covenant.org/) to foster a welcoming and inclusive community. Please be respectful and constructive when discussing or reviewing code.

---

Thank you again for your interest in contributing to the Temp Mail PHP Client. We look forward to working with you!
# Temp Mail Go Client
[![PHP](https://github.com/temp-mail-io/temp-mail-php/actions/workflows/ci.yml/badge.svg)](https://github.com/temp-mail-io/temp-mail-php/actions)

The **official PHP Client** for [Temp Mail](https://temp-mail.io). This library provides developers a straightforward way to create and manage temporary email addresses, retrieve and delete messages, all via the Temp Mail API.

## Table of Contents
- [Features](#features)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Usage Examples](#usage-examples)
    - [Listing Domains](#listing-domains)
    - [Getting Rate Limits](#getting-rate-limits)
    - [Creating Temporary Email](#creating-temporary-email)
    - [Fetching and Deleting Messages](#fetching-and-deleting-messages)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)
- [Support](#support)

## Features
- **Create** temporary email addresses with optional domain specifications
- **Get** current rate limits after API request
- **Delete** a temporary email along with all its messages
- **Retrieve** all messages for a specified email
- **Get** a specific message or download its attachment

## Installation
To install this PHP package, run:
```bash
composer require temp-mail-io/temp-mail-php
```

## Quick Start
Below is a simple example to get started:
```php
<?php
$emailClient = \TempMailIo\TempMailPhp\Factory::createEmailClient('YOUR_API_KEY');

$createResponse = $emailClient->create();

if ($createResponse->errorResponse !== null) {
    echo "Code: " . $response->errorResponse->error->code . PHP_EOL;
    echo "Detail: " . $response->errorResponse->error->detail . PHP_EOL;
    echo "Type: " . $response->errorResponse->error->type . PHP_EOL;
    exit;
}

// Use the created temporary email on the website, service, etc...

$messagesResponse = $emailClient->getMessages($createResponse->successResponse->email);

if ($messagesResponse->errorResponse !== null) {
    echo "Code: " . $messagesResponse->errorResponse->error->code . PHP_EOL;
    echo "Detail: " . $messagesResponse->errorResponse->error->detail . PHP_EOL;
    echo "Type: " . $messagesResponse->errorResponse->error->type . PHP_EOL;
    exit;
}

foreach ($messagesResponse->successResponse->messages as $message) {
    // Iterate over messages
}
```

## Usage Examples
### Listing Domains
```php
$response = \TempMailIo\TempMailPhp\Factory::createDomainClient('YOUR_API_KEY')->getAvailableDomains();

if ($response->errorResponse !== null) {
    // handle error
}

foreach ($response->successResponse->domains as $domain) {
    // Iterate over domains
}
```

### Getting Rate Limits
```php
$response = \TempMailIo\TempMailPhp\Factory::createRateLimitClient('YOUR_API_KEY')->getStatus();

if ($response->errorResponse !== null) {
    // handle error
}

echo "Rate limit: " . $response->successResponse->limit . PHP_EOL;
echo "Remaining limit: " . $response->successResponse->remaining . PHP_EOL;
echo "Used limit: " . $response->successResponse->used . PHP_EOL;
echo "Reset limit: " . $response->successResponse->reset . PHP_EOL;
```

You can also get rate limits from each success response:
```php
$response = \TempMailIo\TempMailPhp\Factory::createEmailClient('YOUR_API_KEY')->create();

if ($response->errorResponse !== null) {
    // handle error
}

echo "Rate limit: " . $response->successResponse->rateLimit->limit . PHP_EOL;
echo "Remaining limit: " . $response->successResponse->rateLimit->remaining . PHP_EOL;
echo "Used limit: " . $response->successResponse->rateLimit->used . PHP_EOL;
echo "Reset limit: " . $response->successResponse->rateLimit->reset . PHP_EOL;
```

### Creating Temporary Email
```php
$response = \TempMailIo\TempMailPhp\Factory::createEmailClient('YOUR_API_KEY')->create();

if ($response->errorResponse !== null) {
    // handle error
}

echo "Email: " . $response->successResponse->email . PHP_EOL;
```

### Fetching and Deleting Messages
```php
$emailClient = \TempMailIo\TempMailPhp\Factory::createEmailClient('YOUR_API_KEY');

$response = $emailClient->getMessages('your_email@example.com');

if ($response->errorResponse !== null) {
    // handle error
}

foreach ($response->successResponse->messages as $message) {
    // Iterate over messages
}

$messageClient = \TempMailIo\TempMailPhp\Factory::createMessageClient('YOUR_API_KEY');

$deleteResponse = $messageClient->delete($response->successResponse->messages[0]->id);

if ($deleteResponse->errorResponse !== null) {
    // handle error
}
```

## Testing
We use the PHPUnit testing framework.

Run tests locally:
```bash
./vendor/bin/phpunit ./tests
```

In the CI, the tests and linters are automatically executed via [GitHub Actions](https://github.com/temp-mail-io/temp-mail-php/actions).

## Contributing
We welcome and appreciate contributions! Please see our CONTRIBUTING.md for guidelines on how to open issues, submit pull requests, and follow our coding standards.

## License
This project is licensed under the MIT License.

## Support
If you encounter any issues, please open [an issue](https://github.com/temp-mail-io/temp-mail-php/issues) on GitHub. We are happy to help you!
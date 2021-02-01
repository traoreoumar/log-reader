# Log Reader

[![Latest Stable Version](https://poser.pugx.org/oumartraore/log-reader/v)](//packagist.org/packages/oumartraore/log-reader)
[![Total Downloads](https://poser.pugx.org/oumartraore/log-reader/downloads)](//packagist.org/packages/oumartraore/log-reader)
[![Latest Unstable Version](https://poser.pugx.org/oumartraore/log-reader/v/unstable)](//packagist.org/packages/oumartraore/log-reader)
[![License](https://poser.pugx.org/oumartraore/log-reader/license)](//packagist.org/packages/oumartraore/log-reader)

## Installation

### Using composer

In your project, call ```bash composer require oumartraore/log-reader```.

## Usage

### Basic Usage

```php
use OumarTraore\LogReader\LogService;

$path = '...';

$logService = new LogService();
$logService->getLogsFromFile($path);
```

### Paginate

```php
use OumarTraore\LogReader\LogService;

$path = '...';

$logService = new LogService();

$logFilterDto = new LogFilterDto();
$logFilterDto->setDirection(LogFilterDto::DIRECTION_BEFORE); // or LogFilterDto::DIRECTION_AFTER
$logFilterDto->setLimit(25);
$logFilterDto->setOffset(50);

$logService->getLogsFromFile($path, $logFilterDto);
```

### With Filter

You can filter logs by:

- channels
- levels
- date

```php
use OumarTraore\LogReader\LogService;

$path = '...';

$logService = new LogService();

$logFilterDto = new LogFilterDto();
$logFilterDto->setChannels(['channel_1', 'channel_2']);
$logFilterDto->setLevels(['level_1', 'level_2']);
$logFilterDto->setDateFrom(new \DateTime('2021-01-01 10:10:45'));
$logFilterDto->setDateTo(new \DateTime('2021-01-01 12:10:45'));

$logService->getLogsFromFile($path, $logFilterDto);
```

### With a custom pattern

```php
use OumarTraore\LogReader\LogService;

$path = '...';

$logService = new LogService();
$pattern = '/\[(?P<date>.*)\] \[(?P<logger>[\w-]+)\] \[(?P<level>\w+)\]: (?P<message>[^\[\{]+) (?P<context>[\[\{].*[\]\}]) (?P<extra>[\[\{].*[\]\}])/';

$logService->getLogsFromFile($path, null, $pattern);
```

<?php

namespace OumarTraore\LogReader\Tests\Service;

use OumarTraore\LogReader\Dto\LogDto;
use OumarTraore\LogReader\Dto\LogFilterDto;
use OumarTraore\LogReader\Service\LogService;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class LogServiceTest extends TestCase
{
    /**
     * @var LogService
     */
    private $logService;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->logService = new LogService();
    }

    public function testKeys()
    {
        $path = './tests/fixtures/log/sample.log';
        $logFilterDto = new LogFilterDto();

        $result = $this->logService->getLogsFromFile($path, $logFilterDto);

        $this->assertArrayHasKey('channels', $result);
        $this->assertArrayHasKey('logs', $result);
    }

    public function testChannels()
    {
        $path = './tests/fixtures/log/sample.log';
        $logFilterDto = new LogFilterDto();

        $result = $this->logService->getLogsFromFile($path, $logFilterDto);

        $this->assertCount(2, $result['channels']);
        $this->assertContains('channel', $result['channels']);
        $this->assertContains('channel2', $result['channels']);
    }

    public function testLogs()
    {
        $path = './tests/fixtures/log/sample.log';
        $logFilterDto = new LogFilterDto();

        $result = $this->logService->getLogsFromFile($path, $logFilterDto);

        $this->assertCount(3, $result['logs']);
        $this->assertEquals(new LogDto(
            $path,
            0,
            new \DateTime('2018-07-05 11:10:35'),
            'channel',
            'INFO',
            'This is a log entry!',
            [],
            [],
        ), $result['logs'][2]);
    }

    public function testLogsOrder()
    {
        $path = './tests/fixtures/log/sample.log';
        $logFilterDto = new LogFilterDto();

        $result = $this->logService->getLogsFromFile($path, $logFilterDto);

        $this->assertEquals(new LogDto(
            $path,
            2,
            new \DateTime('2018-07-09 02:58:27'),
            'channel2',
            'ERROR',
            'This is a log error! ^_^',
            [],
            [],
        ), $result['logs'][0]);

        $this->assertEquals(new LogDto(
            $path,
            1,
            new \DateTime('2018-07-09 02:58:27'),
            'channel',
            'WARNING',
            'This is a log warning! ^_^',
            [],
            [],
        ), $result['logs'][1]);

        $this->assertEquals(new LogDto(
            $path,
            0,
            new \DateTime('2018-07-05 11:10:35'),
            'channel',
            'INFO',
            'This is a log entry!',
            [],
            [],
        ), $result['logs'][2]);
    }

    public function testWithLogFilterDtoDirection()
    {
        $path = './tests/fixtures/log/sample.log';
        $logFilterDto = (new LogFilterDto())
            ->setDirection(LogFilterDto::DIRECTION_AFTER)
        ;

        $result = $this->logService->getLogsFromFile($path, $logFilterDto);

        $this->assertCount(3, $result['logs']);

        $this->assertEquals(new LogDto(
            $path,
            2,
            new \DateTime('2018-07-09 02:58:27'),
            'channel2',
            'ERROR',
            'This is a log error! ^_^',
            [],
            [],
        ), $result['logs'][2]);

        $this->assertEquals(new LogDto(
            $path,
            1,
            new \DateTime('2018-07-09 02:58:27'),
            'channel',
            'WARNING',
            'This is a log warning! ^_^',
            [],
            [],
        ), $result['logs'][1]);

        $this->assertEquals(new LogDto(
            $path,
            0,
            new \DateTime('2018-07-05 11:10:35'),
            'channel',
            'INFO',
            'This is a log entry!',
            [],
            [],
        ), $result['logs'][0]);
    }

    public function testWithLogFilterDtoLimit()
    {
        $path = './tests/fixtures/log/sample.log';
        $logFilterDto = (new LogFilterDto())
            ->setLimit(2)
        ;

        $result = $this->logService->getLogsFromFile($path, $logFilterDto);

        $this->assertCount(2, $result['logs']);

        $this->assertEquals(new LogDto(
            $path,
            2,
            new \DateTime('2018-07-09 02:58:27'),
            'channel2',
            'ERROR',
            'This is a log error! ^_^',
            [],
            [],
        ), $result['logs'][0]);

        $this->assertEquals(new LogDto(
            $path,
            1,
            new \DateTime('2018-07-09 02:58:27'),
            'channel',
            'WARNING',
            'This is a log warning! ^_^',
            [],
            [],
        ), $result['logs'][1]);
    }

    public function testWithLogFilterDtoOffset()
    {
        $path = './tests/fixtures/log/sample.log';
        $logFilterDto = (new LogFilterDto())
            ->setOffset(2)
        ;

        $result = $this->logService->getLogsFromFile($path, $logFilterDto);

        $this->assertCount(2, $result['logs']);

        $this->assertEquals(new LogDto(
            $path,
            1,
            new \DateTime('2018-07-09 02:58:27'),
            'channel',
            'WARNING',
            'This is a log warning! ^_^',
            [],
            [],
        ), $result['logs'][0]);

        $this->assertEquals(new LogDto(
            $path,
            0,
            new \DateTime('2018-07-05 11:10:35'),
            'channel',
            'INFO',
            'This is a log entry!',
            [],
            [],
        ), $result['logs'][1]);
    }

    public function testLogsCustomPattern()
    {
        $path = './tests/fixtures/log/sample_custom_pattern.log';
        $logFilterDto = new LogFilterDto();

        $pattern = '/\[(?P<date>.*)\] \[(?P<logger>[\w-]+)\] \[(?P<level>\w+)\]: (?P<message>[^\[\{]+) (?P<context>[\[\{].*[\]\}]) (?P<extra>[\[\{].*[\]\}])/';
        $result = $this->logService->getLogsFromFile($path, $logFilterDto, $pattern);

        $this->assertCount(3, $result['logs']);
        $this->assertEquals(new LogDto(
            $path,
            0,
            new \DateTime('2018-07-05 11:10:35'),
            'channel',
            'INFO',
            'This is a log entry!',
            [],
            [],
        ), $result['logs'][2]);
    }
}

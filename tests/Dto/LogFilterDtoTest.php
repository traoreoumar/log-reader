<?php

namespace OumarTraore\LogReader\Tests\Dto;

use OumarTraore\LogReader\Dto\LogFilterDto;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class LogFilterDtoTest extends TestCase
{
    /**
     * @var LogFilterDto
     */
    private $logFilterDto;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->logFilterDto = new LogFilterDto();
    }

    public function testExceptionCausedBySetLimitMethod()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->logFilterDto->setLimit(-1);
    }

    public function testExceptionCausedBySetOffsetMethod()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->logFilterDto->setOffset(-1);
    }
}

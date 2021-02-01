<?php

/*
 * This file is part of the log-reader package.
 *
 * (c) Oumar Traore <oumar.traore.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OumarTraore\LogReader\Dto;

/**
 * @author Oumar Traore <oumar.traore.dev@gmail.com>
 */
class LogFilterDto
{
    public const DIRECTION_BEFORE = 'before';
    public const DIRECTION_AFTER = 'after';

    /**
     * @var array
     */
    private $channels = [];

    /**
     * @var string
     */
    private $direction = self::DIRECTION_BEFORE;

    /**
     * @var array
     */
    private $levels = [];

    /**
     * @var int
     */
    private $limit = 10;

    /**
     * @var null|int
     */
    private $offset;

    public function getChannels(): array
    {
        return $this->channels;
    }

    public function setChannels(array $channels): self
    {
        $this->channels = $channels;

        return $this;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function getLevels(): array
    {
        return $this->levels;
    }

    public function setLevels(array $levels): self
    {
        $this->levels = $levels;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setLimit(int $limit): self
    {
        if (0 > $limit) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The $limit parameter of setLimit method only accepts a value greater than or equal to 0, Input was: ',
                    $limit
                )
            );
        }

        $this->limit = $limit;

        return $this;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setOffset(?int $offset): self
    {
        if (0 > $offset) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The $offset parameter of setOffset method only accepts a value greater than or equal to 0, Input was: ',
                    $offset
                )
            );
        }

        $this->offset = $offset;

        return $this;
    }
}

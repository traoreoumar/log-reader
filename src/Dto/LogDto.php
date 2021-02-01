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
class LogDto
{
    /**
     * @var string
     */
    private $channel;

    /**
     * @var null|array
     */
    private $context;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var null|array
     */
    private $extra;

    /**
     * @var string
     */
    private $level;

    /**
     * @var int
     */
    private $lineNumber;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $path;

    public function __construct(string $path, int $lineNumber, \DateTime $date, string $channel, string $level, string $message, ?array $context, ?array $extra)
    {
        $this->path = $path;
        $this->lineNumber = $lineNumber;
        $this->date = $date;
        $this->channel = $channel;
        $this->level = $level;
        $this->message = $message;
        $this->context = $context;
        $this->extra = $extra;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getPrintableLineNumber(): int
    {
        return $this->lineNumber + 1;
    }

    public function getLineNumber(): int
    {
        return $this->lineNumber;
    }

    public function setLineNumber(int $lineNumber): self
    {
        $this->lineNumber = $lineNumber;

        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }

    public function setContext(?array $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getExtra(): ?array
    {
        return $this->extra;
    }

    public function setExtra(?array $extra): self
    {
        $this->extra = $extra;

        return $this;
    }
}

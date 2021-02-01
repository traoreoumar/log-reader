<?php

/*
 * This file is part of the log-reader package.
 *
 * (c) Oumar Traore <oumar.traore.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OumarTraore\LogReader\Reader;

use OumarTraore\LogReader\Parser\LogParser;
use OumarTraore\LogReader\Parser\LogParserInterface;

/**
 * Based on Dubture\Monolog\Reader\LogReader.
 *
 * @author Oumar Traore <oumar.traore.dev@gmail.com>
 */
class LogReader implements LogReaderInterface
{
    public const DEFAULT_PATTERN = 'default';
    public const CUSTOM_PATTERN = 'custom';

    public const DEFAULT_PARSER = LogParser::class;

    /**
     * @var SplFileObject
     */
    protected $file;

    /**
     * @var LogParserInterface
     */
    protected $parser;

    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var int
     */
    protected $lineCount;

    public function __construct($path, string $pattern = null)
    {
        $this->file = new \SplFileObject($path, 'r');
        $this->file->setFlags(\SplFileObject::SKIP_EMPTY | \SplFileObject::READ_AHEAD);

        $lineCount = 0;
        while (!$this->file->eof()) {
            $this->file->current();
            $this->file->next();
            ++$lineCount;
        }

        $this->lineCount = $lineCount;

        $this->pattern = $pattern;

        $parserFqcn = static::DEFAULT_PARSER;
        $this->setParser(new $parserFqcn());
    }

    /**
     * {@inheritdoc}
     */
    public function getParser(): LogParserInterface
    {
        return $this->parser;
    }

    /**
     * {@inheritdoc}
     */
    public function setParser(LogParserInterface $logParser): self
    {
        $this->parser = $logParser;

        if (null !== $this->pattern) {
            $this->parser->registerPattern(static::CUSTOM_PATTERN, $this->pattern);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function sof(): bool
    {
        return 0 === $this->file->key();
    }

    /**
     * {@inheritdoc}
     */
    public function eof(): bool
    {
        return $this->file->eof();
    }

    /**
     * {@inheritdoc}
     */
    public function seek(int $position)
    {
        $this->file->seek($position);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->parser->parse(
            $this->file->current(),
            0,
            $this->pattern ? static::CUSTOM_PATTERN : static::DEFAULT_PATTERN
        );
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->file->key();
    }

    public function prev(): void
    {
        $previousKey = $this->key() - 1;

        if (0 <= $previousKey) {
            $this->seek($previousKey);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->file->next();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->file->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->file->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->lineCount < $offset;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        $currentKey = $this->file->key();

        $this->file->seek($offset);

        $logLine = $this->current();

        $this->file->seek($currentKey);

        $this->file->current();

        return $logLine;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException
     */
    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('LogReader is read-only.');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('LogReader is read-only.');
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->lineCount;
    }
}

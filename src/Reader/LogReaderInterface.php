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

use OumarTraore\LogReader\Parser\LogParserInterface;

/**
 * @author Oumar Traore <oumar.traore.dev@gmail.com>
 */
interface LogReaderInterface extends \ArrayAccess, \Countable, \SeekableIterator
{
    /**
     * Get LogParser instance.
     */
    public function getParser(): LogParserInterface;

    /**
     * Set LogParser instance.
     */
    public function setParser(LogParserInterface $logParser): self;

    /**
     * Check if current line is first.
     */
    public function sof(): bool;

    /**
     * Check if current line is last.
     */
    public function eof(): bool;

    /**
     * Go to previous line.
     */
    public function prev(): void;
}

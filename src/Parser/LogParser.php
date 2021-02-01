<?php

/*
 * This file is part of the log-reader package.
 *
 * (c) Oumar Traore <oumar.traore.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OumarTraore\LogReader\Parser;

use Dubture\Monolog\Parser\LineLogParser;

/**
 * @author Oumar Traore <oumar.traore.dev@gmail.com>
 */
class LogParser extends LineLogParser implements LogParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function registerPattern($name, $patternName)
    {
        $this->pattern[$name] = $patternName;
    }
}

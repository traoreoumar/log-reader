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

use Dubture\Monolog\Parser\LogParserInterface as DubtureLogParserInterface;

/**
 * @author Oumar Traore <oumar.traore.dev@gmail.com>
 */
interface LogParserInterface extends DubtureLogParserInterface
{
    /**
     * Register a pattern. Update it if already exists.
     *
     * @param string $name
     * @param string $patternName
     */
    public function registerPattern($name, $patternName);
}

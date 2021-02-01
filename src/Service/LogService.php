<?php

/*
 * This file is part of the log-reader package.
 *
 * (c) Oumar Traore <oumar.traore.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OumarTraore\LogReader\Service;

use OumarTraore\LogReader\Dto\LogDto;
use OumarTraore\LogReader\Reader\LogReader;
use OumarTraore\LogReader\Reader\LogReaderInterface;

/**
 * @author Oumar Traore <oumar.traore.dev@gmail.com>
 */
class LogService
{
    public const LEVELS = ['DEBUG', 'INFO', 'NOTICE', 'WARNING', 'ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY'];

    /**
     * Read log file and get logs.
     */
    public function getLogsFromFile(string $path, string $pattern = null)
    {
        $channels = [];
        $logs = [];

        $logReader = $this->createLogReader($path, $pattern);
        $logReader->seek($logReader->count() - 1);

        $outOfBounds = false;
        while (!$outOfBounds) {
            $lineNumber = $logReader->key();
            $line = $logReader->current();

            if ($line) {
                $date = $line['date'];
                $channel = $line['logger'];
                $level = $line['level'];
                $message = $line['message'];
                $context = $line['context'];
                $extra = $line['extra'];

                $channels[] = $channel;

                $logs[] = new LogDto(
                    $path,
                    $lineNumber,
                    $date,
                    $channel,
                    $level,
                    $message,
                    $context,
                    $extra
                );
            }

            if ($logReader->sof()) {
                $outOfBounds = true;
            }

            $logReader->prev();
        }

        return [
            'channels' => array_unique($channels),
            'logs' => $logs,
        ];
    }

    protected function createLogReader($path, $pattern): LogReaderInterface
    {
        return new LogReader($path, $pattern);
    }

    /**
     * Sort (desc) logs by date.
     *
     * @param LogDto[] $logs
     */
    protected function sortLogs($logs)
    {
        usort(
            $logs,
            function (LogDto $a, LogDto $b) {
                $valueA = $a->getDate()->getTimestamp();
                $valueB = $b->getDate()->getTimestamp();

                if ($valueA === $valueB) {
                    return 0;
                }

                return ($valueA < $valueB) ? -1 : 1;
            }
        );

        return $logs;
    }
}

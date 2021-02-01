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
use OumarTraore\LogReader\Dto\LogFilterDto;
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
    public function getLogsFromFile(string $path, LogFilterDto $logFilterDto = null, string $pattern = null)
    {
        if (null === $logFilterDto) {
            $logFilterDto = new LogFilterDto();
        }

        $channels = [];
        $logs = [];

        $logReader = $this->createLogReader($path, $pattern);

        $filterChannels = $logFilterDto->getChannels();
        $filterDateFrom = $logFilterDto->getDateFrom();
        $filterDateTo = $logFilterDto->getDateTo();
        $filterDirection = $logFilterDto->getDirection();
        $filterLevels = $logFilterDto->getLevels();
        $filterLimit = $logFilterDto->getLimit();
        $filterOffset = $logFilterDto->getOffset();

        if (null !== $filterOffset) {
            if (LogFilterDto::DIRECTION_BEFORE === $filterDirection) {
                $logReader->seek($filterOffset - 1);
            } elseif (LogFilterDto::DIRECTION_AFTER === $filterDirection) {
                $logReader->seek($filterOffset + 1);
            }
        } elseif (LogFilterDto::DIRECTION_BEFORE === $filterDirection) {
            $logReader->seek($logReader->count() - 1);
        } else {
            $logReader->seek(0);
        }

        $outOfBounds = false;
        while (\count($logs) < $filterLimit && !$outOfBounds) {
            $lineNumber = $logReader->key();
            $line = $logReader->current();

            if ($line) {
                $date = $line['date'];
                $channel = $line['logger'];
                $level = $line['level'];
                $message = $line['message'];
                $context = $line['context'];
                $extra = $line['extra'];

                if (
                    (
                        null === $date
                        || (
                            (null === $filterDateFrom || $filterDateFrom->getTimestamp() <= $date->getTimestamp())
                            && (null === $filterDateTo || $date->getTimestamp() <= $filterDateTo->getTimestamp())
                        )
                    )
                    && (!$filterChannels || in_array($channel, $filterChannels))
                    && (!$filterLevels || in_array($level, $filterLevels))
                ) {
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
            }

            if (LogFilterDto::DIRECTION_BEFORE === $filterDirection) {
                if ($logReader->sof()) {
                    $outOfBounds = true;
                }

                $logReader->prev();
            } elseif (LogFilterDto::DIRECTION_AFTER === $filterDirection) {
                if ($logReader->eof()) {
                    $outOfBounds = true;
                }

                $logReader->next();
            }
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

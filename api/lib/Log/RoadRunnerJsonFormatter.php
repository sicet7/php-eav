<?php

namespace Sicet7\Log;

use Monolog\Formatter\JsonFormatter;
use Monolog\LogRecord;

class RoadRunnerJsonFormatter extends JsonFormatter
{
    /**
     * @param bool $includeStacktraces
     */
    public function __construct(
        bool $includeStacktraces = false
    ) {
        parent::__construct(self::BATCH_MODE_NEWLINES, true, true, $includeStacktraces);
        $this->dateFormat = 'Uu000';
        $this->setJsonPrettyPrint(false);
    }

    protected function normalizeRecord(LogRecord $record): array
    {
        $data = $record->toArray();

        if (isset($data['level'])) {
            unset($data['level']);
        }

        if (isset($data['level_name'])) {
            if (is_string($data['level_name'])) {
                $data['level'] = strtolower($data['level_name']);
            }
            unset($data['level_name']);
        }

        if (isset($data['datetime'])) {
            $data['ts'] = $data['datetime'];
            unset($data['datetime']);
        }

        if (isset($data['message'])) {
            $data['msg'] = $data['message'];
            unset($data['message']);
        }

        if (isset($data['channel'])) {
            if (is_string($data['channel'])) {
                $data['logger'] = strtolower($data['channel']);
            }
            unset($data['channel']);
        }

        return $this->normalize($data);
    }

    protected function normalize(mixed $data, int $depth = 0): mixed
    {
        if ($data instanceof \DateTimeInterface) {
            return (int) $this->formatDate($data);
        }
        return parent::normalize($data, $depth);
    }
}
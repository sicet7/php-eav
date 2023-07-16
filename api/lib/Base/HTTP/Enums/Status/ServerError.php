<?php

namespace Sicet7\Base\HTTP\Enums\Status;

enum ServerError: int
{
    case INTERNAL_SERVER_ERROR = 500;
    case NOT_IMPLEMENTED = 501;
    case BAD_GATEWAY = 502;
    case SERVICE_UNAVAILABLE = 503;
    case GATEWAY_TIMEOUT = 504;
    case VERSION_NOT_SUPPORTED = 505;
    case VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506; // RFC2295
    case INSUFFICIENT_STORAGE = 507; // RFC4918
    case LOOP_DETECTED = 508; // RFC5842
    case NOT_EXTENDED = 510; // RFC2774
    case NETWORK_AUTHENTICATION_REQUIRED = 511; // RFC6585

    public const REASONS = [
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates', // RFC2295
        507 => 'Insufficient Storage', // RFC4918
        508 => 'Loop Detected', // RFC5842
        510 => 'Not Extended', // RFC2774
        511 => 'Network Authentication Required',
    ];

    /**
     * @return string
     */
    public function getReason(): string
    {
        return self::REASONS[$this->value];
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->value . ' ' . $this->getReason();
    }
}

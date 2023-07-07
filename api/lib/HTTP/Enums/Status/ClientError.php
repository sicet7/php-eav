<?php

namespace Sicet7\HTTP\Enums\Status;

enum ClientError: int
{
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case PAYMENT_REQUIRED = 402;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case NOT_ACCEPTABLE = 406;
    case PROXY_AUTHENTICATION_REQUIRED = 407;
    case REQUEST_TIMEOUT = 408;
    case CONFLICT = 409;
    case GONE = 410;
    case LENGTH_REQUIRED = 411;
    case PRECONDITION_FAILED = 412;
    case REQUEST_ENTITY_TOO_LARGE = 413;
    case REQUEST_URI_TOO_LONG = 414;
    case UNSUPPORTED_MEDIA_TYPE = 415;
    case REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    case EXPECTATION_FAILED = 417;
    case I_AM_A_TEAPOT = 418; // RFC2324
    case MISDIRECTED_REQUEST = 421; // RFC7540
    case UNPROCESSABLE_ENTITY = 422; // RFC4918
    case LOCKED = 423; // RFC4918
    case FAILED_DEPENDENCY = 424; // RFC4918
    case TOO_EARLY = 425; // RFC-ietf-httpbis-replay-04
    case UPGRADE_REQUIRED = 426; // RFC2817
    case PRECONDITION_REQUIRED = 428; // RFC6585
    case TOO_MANY_REQUESTS = 429; // RFC6585
    case REQUEST_HEADER_FIELDS_TOO_LARGE = 431; // RFC6585
    case UNAVAILABLE_FOR_LEGAL_REASONS = 451; // RFC7725

    public const REASONS = [
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Content Too Large', // RFC-ietf-httpbis-semantics
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot', // RFC2324
        421 => 'Misdirected Request', // RFC7540
        422 => 'Unprocessable Content', // RFC-ietf-httpbis-semantics
        423 => 'Locked', // RFC4918
        424 => 'Failed Dependency', // RFC4918
        425 => 'Too Early', // RFC-ietf-httpbis-replay-04
        426 => 'Upgrade Required', // RFC2817
        428 => 'Precondition Required', // RFC6585
        429 => 'Too Many Requests', // RFC6585
        431 => 'Request Header Fields Too Large', // RFC6585
        451 => 'Unavailable For Legal Reasons', // RFC7725
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

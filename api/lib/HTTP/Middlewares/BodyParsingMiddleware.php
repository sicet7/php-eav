<?php

namespace Sicet7\HTTP\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use Sicet7\Base\HTTP\Interfaces\BodyParserInterface;

/**
 * @see https://github.com/slimphp/Slim/blob/4.x/Slim/Middleware/BodyParsingMiddleware.php
 */
class BodyParsingMiddleware implements MiddlewareInterface
{
    /**
     * @var BodyParserInterface[]
     */
    private array $bodyParsers = [];

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $parsedBody = $request->getParsedBody();

        if (empty($parsedBody)) {
            $parsedBody = $this->parseBody($request);
            $request = $request->withParsedBody($parsedBody);
        }

        return $handler->handle($request);
    }

    /**
     * @param string $mediaType An HTTP media type (excluding content-type params).
     * @param BodyParserInterface $bodyParser
     * @return BodyParsingMiddleware
     */
    public function registerBodyParser(string $mediaType, BodyParserInterface $bodyParser): self
    {
        $this->bodyParsers[$mediaType] = $bodyParser;
        return $this;
    }

    /**
     * @param string $mediaType
     */
    public function hasBodyParser(string $mediaType): bool
    {
        return isset($this->bodyParsers[$mediaType]);
    }

    /**
     * @param string $mediaType
     * @throws RuntimeException
     * @return BodyParserInterface
     */
    public function getBodyParser(string $mediaType): BodyParserInterface
    {
        if (!isset($this->bodyParsers[$mediaType])) {
            throw new RuntimeException('No parser for type ' . $mediaType);
        }
        return $this->bodyParsers[$mediaType];
    }

    /**
     * @return null|array|object
     */
    protected function parseBody(ServerRequestInterface $request)
    {
        $mediaType = $this->getMediaType($request);
        if ($mediaType === null) {
            return null;
        }

        // Check if this specific media type has a parser registered first
        if (!isset($this->bodyParsers[$mediaType])) {
            // If not, look for a media type with a structured syntax suffix (RFC 6839)
            $parts = explode('+', $mediaType);
            if (count($parts) >= 2) {
                $mediaType = 'application/' . $parts[count($parts) - 1];
            }
        }

        if (isset($this->bodyParsers[$mediaType])) {
            $body = (string)$request->getBody();
            $parsed = $this->bodyParsers[$mediaType]?->parse($body);

            if ($parsed !== null && !is_object($parsed) && !is_array($parsed)) {
                throw new RuntimeException(
                    'Request body media type parser return value must be an array, an object, or null'
                );
            }

            return $parsed;
        }

        return null;
    }

    /**
     * @return string|null The serverRequest media type, minus content-type params
     */
    protected function getMediaType(ServerRequestInterface $request): ?string
    {
        $contentType = $request->getHeader('Content-Type')[0] ?? null;

        if (is_string($contentType) && trim($contentType) !== '') {
            $contentTypeParts = explode(';', $contentType);
            return strtolower(trim($contentTypeParts[0]));
        }

        return null;
    }
}
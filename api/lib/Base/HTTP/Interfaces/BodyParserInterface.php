<?php

namespace Sicet7\Base\HTTP\Interfaces;

interface BodyParserInterface
{
    /**
     * @param string $body
     * @return array|object|null
     */
    public function parse(string $body): null|array|object;
}
<?php

namespace Sicet7\Contracts\HTTP;

interface HasIdentifierInterface
{
    /**
     * @return string
     */
    public function getIdentifier(): string;
}
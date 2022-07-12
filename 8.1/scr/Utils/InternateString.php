<?php

namespace Tests\Optimacros\Forest\Utils;

class InternateString
{
    private array $strings = [];
    private int $key = 0;

    function internate(string $string) : ?int
    {
        if (strlen($string) > 0)
        {
            $saved_string = array_search($string, $this->strings);
            if ($saved_string)
            {
                return $saved_string;
            }

            $this->key++;

            $this->strings[$this->key] = $string;

            return $this->key;
        }

        return null;
    }

    function getinternatedString(bool|int $key) : ?string
    {
        if ($key)
        {
            return $this->strings[$key];
        }

        return null;
    }
}
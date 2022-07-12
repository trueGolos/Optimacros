<?php

namespace Tests\Optimacros\Forest\Utils;

class FileUtils
{
    static function checkCSV(string $filename) : bool
    {
        if (preg_match('/^[a-z0-9\.\/]+.csv$/', $filename) !== 1)
        {
            return false;
        }

        return true;
    }

    static function checkJSON(string $filename) : bool
    {
        if (!preg_match('/^[a-zA-Z0-9\-\_]+.json$/', $filename))
        {
            return false;
        }

        return true;
    }
}
<?php
require_once __DIR__.'/vendor/autoload.php';

use Tests\Optimacros\Forest\Forest;

use Tests\Optimacros\Forest\Utils\FileUtils;
use Tests\Optimacros\Forest\Utils\InternateString;
use Tests\Optimacros\Forest\Utils\TreeJsonBuilder;

$input_csv_file = $argv[1];
$output_json_file = $argv[2];
$errors = [];

if (!FileUtils::checkCSV($input_csv_file))
{
    $errors[] = 'bad input CSV file';
}

if (!FileUtils::checkJSON($output_json_file))
{
    $errors[] = 'bad input JSON file';
}

if (count($errors) === 0)
{
    $csv_file = new SplFileObject($input_csv_file);
    $internated_strings = new InternateString();

    $forest = (new Forest())->generateForestFromCsv($csv_file,
        $internated_strings);

    $json_builder = new TreeJsonBuilder($output_json_file,
        $forest,
        $internated_strings);

    $json_builder->toJson();
}

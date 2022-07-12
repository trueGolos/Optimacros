<?php
namespace Tests\Optimacros\Forest\Tests;

use Tests\Optimacros\Forest\Forest;
use Tests\Optimacros\Forest\TreeNode;

use Tests\Optimacros\Forest\Utils\FileUtils;
use Tests\Optimacros\Forest\Utils\InternateString;
use Tests\Optimacros\Forest\Utils\TreeJsonBuilder;

use PHPUnit\Framework\TestCase;

class OutputFileEquivalentTest extends TestCase
{
    protected string $script_file;
    protected string $command;

    protected string $input_file;
    protected string $output_file;
    protected string $output_file_sample;

    protected function setUp(): void
    {
        $base_dir = getcwd();

        $this->input_file = $base_dir.'/../input/input.csv';
        $this->output_file = $base_dir.'/../output/test_output.json';
        $this->output_file_sample = $base_dir.'/../output/output.json';

        $this->script_file = $base_dir.'/../csv_convert.php';
        $this->command = 'php '.$this->script_file.' '.$this->input_file.' '.$this->output_file;
    }

    public function testConvertCsv()
    {
        $bad_chars = [' ', "\r", "\n"];

        echo exec($this->command);

        $json_generated = str_replace($bad_chars, '', file_get_contents($this->output_file));
        $json_sample = str_replace($bad_chars, '', file_get_contents($this->output_file_sample));

        $this->assertEquals($json_generated, $json_sample);
    }
}
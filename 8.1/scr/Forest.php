<?php

namespace Tests\Optimacros\Forest;

use Tests\Optimacros\Forest\Utils\InternateString;

class Forest
{
    private array $nodes;
    private TreeNode|null $current_node;

    private array $trees;

    public function __construct()
    {
        $this->nodes = [];
        $this->trees = [];
    }

    function addNode(int $name, TreeNode $node) : Forest
    {
        $this->current_node = $node;
        $this->nodes[$name] = $node;

        $parent = $this->current_node->getParent();

        if ($parent)
        {
            $parent->setChild($node);
        }
        else
        {
            $this->trees[$name] = &$node;
        }

        return $this;
    }

    function getForest() : bool|array
    {
        if (count($this->trees) > 0)
        {
            return $this->trees;
        }

        return false;
    }

    function getNodeByName(?int $key) : ?TreeNode
    {
        if (isset($this->nodes[$key]))
        {
            return $this->nodes[$key];
        }

        return null;
    }

    function generateForestFromCsv(&$csv_file, InternateString &$internated_strings) : Forest
    {
        $row = 0;

        while (!$csv_file->eof())
        {
            $new_csv_string = $csv_file->fgetcsv();

            if (!$new_csv_string[0])
            {
                continue;
            }

            $csv_data = explode(';', $new_csv_string[0]);

            if ($row > 0 AND count($csv_data) === 4)
            {
                $name = $internated_strings->internate(trim($csv_data[0], '"'));
                $type = $internated_strings->internate(trim($csv_data[1], '"'));
                $parent_name = $internated_strings->internate(trim($csv_data[2], '"'));
                $parent = $this->getNodeByName($parent_name);
                $relation = $internated_strings->internate(trim($csv_data[3], '"'));


                $this->addNode($name, new TreeNode( name: $name,
                                                    type: $type,
                                                    parent: $parent,
                                                    relation: $relation));
            }

            $row++;
        }

        return $this;
    }

    function rewind() : Forest|bool
    {
        if (count($this->nodes) > 0)
        {
            $base_node_key = array_key_first($this->nodes);

            $this->current_node = $this->nodes[$base_node_key];

            return $this;
        }

        return false;
    }
}
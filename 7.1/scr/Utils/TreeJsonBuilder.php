<?php

namespace Tests\Optimacros\Forest\Utils;

use Tests\Optimacros\Forest\Forest;
use Tests\Optimacros\Forest\TreeNode;

class TreeJsonBuilder
{
    private $internate_strings;
    private $forest;

    private $filename;
    private $file_mode = 'w+';
    private $file_handle;
    private $spaces_for_tab = '  ';

    public function __construct(string $filename, Forest &$forest, InternateString &$internate_strings)
    {
        $this->internate_strings = &$internate_strings;
        $this->forest = &$forest;
        $this->filename = $filename;
    }

    private function fileOpen() : void
    {
        $this->file_handle = fopen($this->filename, $this->file_mode);
    }

    private function fileWrite(string $str_to_write, $tab_str = false, bool $pre_eol = false) : void
    {
        if ($pre_eol)
        {
            fwrite($this->file_handle, PHP_EOL);
        }

        if ($tab_str)
        {
            fwrite($this->file_handle, $tab_str.$str_to_write);
        }
        else
        {
            fwrite($this->file_handle, $str_to_write);
        }
    }

    private function fileClose() : void
    {
        fclose($this->file_handle);
    }

    private function tabStr(int $tab_level) : string
    {
        $tab = '';

        for ($l = 0; $l < $tab_level; $l++)
        {
            $tab .= $this->spaces_for_tab;
        }

        return $tab;
    }

    function toJson() : void
    {
        $this->forest->rewind();
        $this->fileOpen();

        $tab_level = 0;

        $this->fileWrite('[', $this->tabStr($tab_level));

        if ($this->forest->getForest())
        {
            foreach ($this->forest->getForest() as $tree_key => $one_tree)
            {
                if ($tree_key !== (array_keys($this->forest->getForest()))[0])
                {
                    $this->fileWrite(',');
                }

                $this->printJsonObject($one_tree, $tab_level);
            }
        }

        $this->fileWrite(']', $this->tabStr(0), true);

        $this->fileClose();
    }

    protected function printJsonObject(TreeNode $node, int $tab, $change_parent = false) : void
    {
        $tab++;

        $this->fileWrite('{', $this->tabStr($tab), true);

        $tab++;

        $node_name = $this->internate_strings->getinternatedString($node->getName());

        $parent_name = 'null';
        if ($node->getParent())
        {
            $parent_name = $this->getParentName($node->getParent()->getName(), $change_parent);
        }

        $this->fileWrite('"itemName": "'.$node_name.'",', $this->tabStr($tab), true);
        $this->fileWrite('"parent": '.$parent_name.',', $this->tabStr($tab), true);
        $this->fileWrite('"children": [', $this->tabStr($tab), true);

        $inner_block = 0;
        $end_arr_pre_eol = false;
        if ($node->getChildren() OR $node->getRelation())
        {
            if ($this->printChildren($node, $tab) OR $this->printRelation($node, $tab))
            {
                $inner_block = $tab;
                $end_arr_pre_eol = true;
            }
        }

        $this->fileWrite(']', $this->tabStr($inner_block), $end_arr_pre_eol);

        $tab--;
        $this->fileWrite('}', $this->tabStr($tab), true);
    }

    private function printChildren(&$node, $tab) : bool
    {
        $children = $node->getChildren();

        if ($children)
        {
            foreach ($children as $key => $child)
            {
                if ($key !== (array_keys($children))[0])
                {
                    $this->fileWrite(',');
                }

                $this->printJsonObject($child, $tab);
            }

            return true;
        }

        return false;
    }

    private function printRelation($node, $tab) : bool
    {
        if ($node->getRelation())
        {
            $relation = $this->forest->getNodeByName($node->getRelation());

            if ($relation)
            {
                $rel_childrens = $relation->getChildren();

                if ($rel_childrens)
                {
                    foreach ($rel_childrens as $key => $child)
                    {
                        if ($this->checkType($node->getType()))
                        {
                            if ($key !== (array_keys($rel_childrens))[0])
                            {
                                $this->fileWrite(',');
                            }

                            $this->printJsonObject($child, $tab, $node->getName());
                        }
                    }

                    return true;
                }
            }
        }

        return false;
    }

    private function checkType(int $node_type)
    {
        $intern_str = $this->internate_strings->internate("Прямые компоненты");

        if ($node_type === $intern_str)
        {
            return true;
        }

        return false;
    }

    protected function getParentName(int $parent_key, $change_parent = false) : string
    {
        $parent_name = "null";

        if ($change_parent)
        {
            $parent_name = $this->internate_strings->getinternatedString($change_parent);
        }
        elseif ($parent_key > 0)
        {
            $parent_name = $this->internate_strings->getinternatedString($parent_key);
        }

        return '"'.$parent_name.'"';
    }
}
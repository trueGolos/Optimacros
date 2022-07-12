<?php

namespace Tests\Optimacros\Forest;

class TreeNode
{
    private $name;
    private $type;
    private $relation = null;

    private $parent = null;
    private $childrens = [];

    public function __construct(int $name, int $type, ?TreeNode $parent = null, ?int $relation = null)
    {
        $this->name = $name;
        $this->setType($type);
        $this->setParent($parent);
        $this->setRelation($relation);
    }

    function getName() : int
    {
        return $this->name;
    }

    function setType(int $type) : void
    {
        $this->type = $type;
    }

    function getType() : int
    {
        return $this->type;
    }

    function getParent()
    {
        if ($this->parent)
        {
            return $this->parent;
        }

        return false;
    }

    function setParent(?TreeNode &$parent) : void
    {
        if ($parent)
        {
            $this->parent = &$parent;
        }
    }

    function setRelation($relation = null) : void
    {
        $this->relation = $relation;
    }

    function getRelation() : ?int
    {
        return $this->relation;
    }

    function setChild(TreeNode &$children_node) : void
    {
        $this->childrens[$children_node->getName()] = &$children_node;
    }

    function getChildren()
    {
        if (count($this->childrens) > 0)
        {
            return $this->childrens;
        }

        return false;
    }
}
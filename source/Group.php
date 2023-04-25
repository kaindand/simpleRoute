<?php

namespace SimpleRoute;

class Group
{
    private $parentGroup;

    public function __construct($parentGroup)
    {
        $this->parentGroup = $parentGroup;
    }

    public function getParentGroup()
    {
        return $this->parentGroup;
    }
}

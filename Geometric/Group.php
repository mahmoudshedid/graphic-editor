<?php

namespace App\Geometric;

require_once 'GraphicsObject.php';

class Group extends GraphicsObject
{
    private $z;
    protected $members = array();

    public function __construct($z)
    {
        $this->z = $z;
    }

    public function add($member)
    {
        $this->members [] = $member;
    }

    public function render($ge)
    {
        usort($this->members, array($this, "zsort"));
        foreach ($this->members as $gobj) {
            $gobj->render($ge);
        }
    }

    public function z()
    {
        return $this->z;
    }

    private function zsort($a, $b)
    {
        if ($a->z() < $b->z()) return -1;
        if ($a->z() > $b->z()) return 1;
        return 0;
    }
}
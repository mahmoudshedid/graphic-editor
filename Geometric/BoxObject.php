<?php

namespace App\Geometric;

require_once 'GraphicsObject.php';

abstract class BoxObject extends GraphicsObject
{
    protected $color;
    protected $sx;
    protected $sy;
    protected $ex;
    protected $ey;
    protected $z;

    public function __construct($z, $color, $sx, $sy, $ex, $ey)
    {
        $this->z = $z;
        $this->color = $color;
        $this->sx = $sx;
        $this->sy = $sy;
        $this->ex = $ex;
        $this->ey = $ey;
    }

    public function z()
    {
        return $this->z;
    }
}
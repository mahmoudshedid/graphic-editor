<?php

namespace App\Shapes;

require_once  dirname(__DIR__) . '/Geometric/BoxObject.php';

use App\geometric\BoxObject;

class Oval extends BoxObject
{
    public function render($ge)
    {
        $w = $this->ex - $this->sx;
        $h = $this->ey - $this->sy;
        imagefilledellipse($ge->getGraphicObject(), $this->sx + ($w / 2), $this->sy + ($h / 2), $w, $h, $ge->getColor($this->color));
    }
}
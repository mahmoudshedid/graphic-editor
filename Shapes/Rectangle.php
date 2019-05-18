<?php

namespace App\Shapes;

require_once  dirname(__DIR__) . '/Geometric/BoxObject.php';

use App\geometric\BoxObject;

class Rectangle extends BoxObject
{

    public function render($ge)
    {
        imagefilledrectangle($ge->getGraphicObject(), $this->sx, $this->sy, $this->ex, $this->ey, $ge->getColor($this->color));
    }
}
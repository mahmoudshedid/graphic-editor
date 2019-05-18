<?php

namespace App\Shapes;

require_once  dirname(__DIR__) . '/Geometric/BoxObject.php';

use App\geometric\BoxObject;

class Line extends BoxObject
{
    public function render($ge)
    {
        imageline($ge->getGraphicObject(), $this->sx, $this->sy, $this->ex, $this->ey, $ge->getColor($this->color));
    }
}
<?php

namespace App\Geometric;

abstract class GraphicsObject
{
    abstract public function render($ge);

    abstract public function z();
}
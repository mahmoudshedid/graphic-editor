<?php

namespace App\Shapes;

require_once dirname(__DIR__) . '/Geometric/Group.php';
require_once 'Rectangle.php';
require_once 'Oval.php';


use App\Geometric\Group;

class DrawShape
{
    protected $base64 = "";
    protected $fillColorName = "red";
    protected $borderColorName = "black";
    protected $imageSize = "400";
    protected $shape = "";
    protected $graphicsEnvironment = "";

    /**
     * DrawShape constructor.
     * @param $shape
     * @param $imageSize
     * @param $shapeName
     * @param $graphicsEnvironment
     */
    public function __construct($shape, $imageSize, $shapeName, $graphicsEnvironment)
    {
        $this->fillColorName = $this->getColorName($shape->fillColor, $this->fillColorName, $shapeName, $graphicsEnvironment);
        $this->borderColorName = $this->getColorName($shape->border->color, $this->borderColorName, $shapeName, $graphicsEnvironment);
        $this->graphicsEnvironment = $graphicsEnvironment;
        $this->imageSize = $imageSize;
        $this->shape = $shape;
    }

    /**
     * Call this function, will draw rectangle shape.
     */
    public function drawRectangle()
    {
        $this->rectangle();

        $this->handleFile();
    }

    /**
     * Call this function, will draw circle shape.
     */
    public function drawCircle()
    {
        $this->circle();

        $this->handleFile();
    }

    /**
     * To get image as base 64.
     * @return string
     */
    public function getBase64()
    {
        return $this->base64;
    }

    /**
     * To render rectangle.
     */
    protected function rectangle()
    {
        $group = new Group(0);

        $recSideLength = $this->shape->sideLength;
        $newRecSideLength = $recSideLength / 2;
        $borderWidth = $this->shape->border->width;

        $group->add(new Rectangle(200, $this->fillColorName, ($this->imageSize / 2) - $newRecSideLength, ($this->imageSize / 2) - $newRecSideLength, ($this->imageSize / 2) + $newRecSideLength, ($this->imageSize / 2) + $newRecSideLength));
        $group->add(new Rectangle(100, $this->borderColorName, (($this->imageSize / 2) - $newRecSideLength) - $borderWidth, (($this->imageSize / 2) - $newRecSideLength) - $borderWidth, (($this->imageSize / 2) + $newRecSideLength) + $borderWidth, (($this->imageSize / 2) + $newRecSideLength) + $borderWidth));

        $group->render($this->graphicsEnvironment);
    }

    /**
     * To render circle.
     */
    protected function circle()
    {
        $group = new Group(0);

        $recSideLength = $this->shape->perimeter;
        $newRecSideLength = $recSideLength / 2;
        $borderWidth = $this->shape->border->width;

        $group->add(new Oval(200, $this->fillColorName, ($this->imageSize / 2) - $newRecSideLength, ($this->imageSize / 2) - $newRecSideLength, ($this->imageSize / 2) + $newRecSideLength, ($this->imageSize / 2) + $newRecSideLength));
        $group->add(new Oval(100, $this->borderColorName, (($this->imageSize / 2) - $newRecSideLength) - $borderWidth, (($this->imageSize / 2) - $newRecSideLength) - $borderWidth, (($this->imageSize / 2) + $newRecSideLength) + $borderWidth, (($this->imageSize / 2) + $newRecSideLength) + $borderWidth));

        $group->render($this->graphicsEnvironment);
    }

    /**
     * Add new color in graphics environment array "image" if not exist or convert hex color fo rgb.
     * @param $color
     * @param $defaultColor
     * @param $shapeName
     * @return string
     */
    protected function getColorName($color, $defaultColor, $shapeName, $graphicsEnvironment)
    {
        $fillColorName = $defaultColor;

        //var_dump($graphicsEnvironment->getColors());

        if ($color[0] == '#') {
            $hex = $color;
            list($red, $green, $blue) = sscanf($hex, "#%02x%02x%02x");
            $fillColorName = $shapeName . '-color';
            $graphicsEnvironment->addColor($fillColorName, $red, $green, $blue);
        } else if (array_search($color, $graphicsEnvironment->getColors())) $fillColorName = $color;

        return $fillColorName;
    }

    /**
     * Save image to disk "tmp folder" then delete it after convert to base 64.
     */
    protected function handleFile()
    {
        $fileName = date("Ymd") . round(microtime(true) * 1000) . rand(1, 1000000);

        $this->graphicsEnvironment->saveAsPng("tmp/" . $fileName . ".png");

        $path = "tmp/" . $fileName . ".png";
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        $this->base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $myFile = "tmp/" . $fileName . ".png";
        unlink($myFile) or die("Couldn't delete file");
    }
}
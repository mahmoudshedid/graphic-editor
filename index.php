<?php
require_once 'Geometric/GraphicsEnvironment.php';
require_once 'Shapes/DrawShape.php';

use App\Geometric\GraphicsEnvironment;
use App\Shapes\DrawShape;

function exception_error_handler($errno, $errstr, $errfile, $errline)
{
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}

set_error_handler("exception_error_handler");

ini_set("allow_url_fopen", 1);

header('Content-Type: application/json');

try{
    $configs = include('config.php');
}catch (Exception $exception){
    echo '{"status": "error", "message" : "' . $exception->getMessage() . '. Please check if config.php is exist and have right configurations, so if not exist rename config.php.example to config.php and set your configurations.", "shapes": []}';
    exit();
}

$jsonUrl = $configs['jsonUrl'];
$maxGenerateShapes = $configs['maxGenerateShapes'];
$imageSize = $configs['imageSize'];

try {
    $file_headers = @get_headers($jsonUrl);
    if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
        echo '{"status": "error", "message" : "Json file or url is wong.", "shapes": []}';
        exit();
    }
} catch (Exception $exception) {
    echo '{"status": "error", "message" : "' . $exception->getMessage() . '", "shapes": []}';
    exit();
}

try {
    $graphicsEnvironment = new GraphicsEnvironment($imageSize, $imageSize);

    $graphicsEnvironment->addColor("black", 0, 0, 0);
    $graphicsEnvironment->addColor("red", 255, 0, 0);
    $graphicsEnvironment->addColor("green", 0, 255, 0);
    $graphicsEnvironment->addColor("blue", 0, 0, 255);
} catch (Exception $exception) {
    echo '{"status": "error", "message" : "' . $exception->getMessage() . '", "shapes": []}';
    exit();
}

try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $jsonUrl);
    $result = curl_exec($ch);
    curl_close($ch);

    $obj = json_decode($result);

} catch (Exception $exception) {
    echo '{"status": "error", "message" : "' . $exception->getMessage() . '", "shapes": []}';
    exit();
}

$drawShape = "";
$image = "";

try {

    if (count($obj->shapes) > $maxGenerateShapes) {
        echo '{"status": "error", "message" : "Can\'t create more than 20 shapes.", "shapes": []}';
        exit();
    }

    ob_start();
    echo '{"status": "success", "message" : "Success", "shapes": [';
    foreach ($obj->shapes as $key => $shape) {
        $fillColorName = "red";
        $borderColorName = "black";
        //echo $shape->type;
        echo '{';
        echo '"type": "' . $shape->type . '",';
        echo '"fillColor": "red",';
        echo '"border": {';
        echo '"color": "' . $shape->border->color . '",';
        echo '"width": ' . $shape->border->width . '';
        echo '},';

        switch ($shape->type) {

            case "circle":
                $shapeName = $shape->type . '-' . $key;
                $drawShape = new DrawShape($shape, $imageSize, $shapeName, $graphicsEnvironment);
                $drawShape->drawCircle();

                $image = $drawShape->getBase64();
                break;

            case "square":
                $shapeName = $shape->type . '-' . $key;
                $drawShape = new DrawShape($shape, $imageSize, $shapeName, $graphicsEnvironment);
                $drawShape->drawRectangle();

                $image = $drawShape->getBase64();
                break;

            default:
                //echo 'default';
                break;
        }


        echo '"image":"' . $image . '"';
        echo '}';

        if (count($obj->shapes) != $key + 1) echo ',';
    }
    echo ']}';
} catch (Exception $exception) {
    ob_end_clean();
    echo '{"status": "error", "message" : "' . $exception->getMessage() . '", "shapes": []}';
}

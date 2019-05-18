# PHP Graphic Editor
Graphic Editor a simple web app which draws geometric shapes such as circle and square. Each shape have various attributes like border color, border size, size and fill color.

## Installation
First make clone from graphic-editor
```bash
git clone https://github.com/mahmoudshedid/graphic-editor.git
```
Create new dir in graphic-editor call it 'tmp' and make it readable and writable with full permission.
```bash
cd graphic-editor
mkdir tmp
chmod 777 tmp
```
If you have SELinux:
```bash
sudo setsebool -P httpd_read_user_content 1
```
If your httpdoc '/var/www/html' in another dir:
```bash
sudo chcon -R --reference=/var/www/html /path/to/graphic-editor
```
Rename config.php.example to config.php, then open it.
```php
return array(
    'jsonUrl' => 'json.php', // For json url
    'maxGenerateShapes' => '20', // For max generate images
    'imageSize' => '400', // For image size
);
```

## Usage
Open json.php file and you will see JSON input format to receives.

```json
{
	"shapes": [{
		"type": "circle",
		"perimeter": 80,
		"fillColor": "#7766ff",
		"border": {
			"color": "black",
			"width": 10
		}
	}]
}
```

## References
[Create graphics the smart way with PHP](https://www.ibm.com/developerworks/library/os-objorient/)

[Render Text and Shapes on Images in PHP](https://code.tutsplus.com/tutorials/rendering-text-and-basic-shapes-using-gd--cms-31767)

[Convert Your Base64 to Image, for check Base64](https://codebeautify.org/base64-to-image-converter)

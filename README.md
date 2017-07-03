# image-url-parser
Download image from remote host


## Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run

```
php composer.phar require dikiy-roman/image-url-parser "1.0.0.x-dev"
```

or add

```
"dikiy-roman/image-url-parser": "1.0.0.x-dev"
```

to the require section of your ```composer.json```

## Usage

```php
    $img_ldr = new ImageDownloader('http://olx.ua/');
    $result = $img_ldr->save("D:\\Test\\");
    echo ($result == true)?'All Image saved':$img_ldr->getError();
```

## Author

[Dykyi Roman](https://github.com/dikiy-roman/), e-mail: [mr.dukuy@gmail.com](mailto:mr.dukuy@gmail.com)

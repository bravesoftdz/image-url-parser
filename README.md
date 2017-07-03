# image-downloader
Download image from remote host


## Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run

```
php composer.phar require dikiy-roman/imagedownloader "1.0.0.x-dev"
```

or add

```
"dikiypac/imagedownloader": "1.0.0.x-dev"
```

to the require section of your ```composer.json```

## Usage

```php
    $img_ldr = new ImageDownloader('http://olx.ua/');
    $result = $img_ldr->save("D:\\Test\\");
    echo ($result == true)?'All Image saved':$img_ldr->getError();
```

## Author

[Dikiy Roman](https://github.com/dikiy-roman/), e-mail: [mr.dukuy@gmail.com](mailto:mr.dikiy@gmail.com)

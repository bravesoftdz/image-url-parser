<?php

require_once dirname(__DIR__).'/Dykyi/BaseUrl.php';
require_once dirname(__DIR__).'/Dykyi/ParseUrl.php';
require_once dirname(__DIR__).'/Dykyi/GetImage.php';
require_once dirname(__DIR__).'/Dykyi/ImageDownloader.php';

class ImageDownloaderTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }

    public function testValideUrlError()
    {
       try {
           try {
               new \Dykyi\ImageDownloader('safsafsa');
           } catch(ErrorException $e) {
               $this->assertTrue(true);
           }
       } finally {
           $this->assertTrue(false);
       }
    }

    public function testHttpParse()
    {
        $img_ldr = new \Dykyi\ImageDownloader('http://olx.ua/');
        $result = $img_ldr->save("image/");
        $this->assertTrue($result);
    }

    public function testHttpsParse()
    {
        $img_ldr = new \Dykyi\ImageDownloader('https://habid.com');
        $result = $img_ldr->save("image/");
        $this->assertTrue($result);
    }

}

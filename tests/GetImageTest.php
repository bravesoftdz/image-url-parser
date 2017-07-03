<?php

require_once dirname(__DIR__).'/src/GetImage.php';

class GetImageTest extends PHPUnit_Framework_TestCase
{

    /** @var  \Dykyi\GetImage */
    private $_image;

    private $_source = 'https://habid.com/img/logo.png';

    public function setUp()
    {
        parent::setUp();
        $this->_image = new \Dykyi\GetImage();
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }

    public function testMimeType()
    {
        $this->_image->source = 'habid.com/img/logo.png';
        $this->_image->save_to = 'image/';
        $this->assertTrue($this->_image->download() !== false);
    }

    public function testDownloadImageCurl()
    {
        $this->_image->source = $this->_source;
        $this->_image->save_to = 'image/';
        $this->assertTrue($this->_image->download() !== false);
    }

    public function testDownloadImageGD()
    {
        $this->_image->source = $this->_source;
        $this->_image->save_to = 'image/';
        $this->assertTrue($this->_image->download(\Src\GetImage::SAVE_FROM_GD) !== false);
    }

}

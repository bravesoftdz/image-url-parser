<?php

require_once dirname(__DIR__).'/Dykyi/BaseUrl.php';
require_once dirname(__DIR__).'/Dykyi/ParseUrl.php';


class ParseUrlTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }

    public function testParseImage()
    {
        $obj = new \Dykyi\ParseUrl('http://olx.com');
        $this->assertTrue(!empty($obj->result));
    }


    public function testExcaptionFormat()
    {
        $obj = new \Dykyi\ParseUrl('http://olx.com', 1321);
        $this->assertTrue(!empty($obj->result));
    }

}


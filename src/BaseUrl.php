<?php

namespace Dykyi;

/**
 * Base class work with url
 *
 * Class BaseUrl
 * @package Dikiypac\ImageDownload
 */
class BaseUrl{

    /**
     * Url validator
     *
     * @param string $url
     *
     * @return bool
     */
    protected function checkUrl($url){
        return (!filter_var($url, FILTER_VALIDATE_URL) === false);
    }

    /**
     * @param string $url
     *
     * @throws \ErrorException
     */
    public function __construct($url)
    {
        if(!$this->checkUrl($url)) {
            throw new \ErrorException('not validate url');
        }
    }
}

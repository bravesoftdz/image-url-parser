<?php

namespace Dykyi;

/**
 * Class ImageDownloader
 *
 * Usage:
 * ```
 * $img_ldr = new ImageDownloader('http://olx.ua/');
 * $result = $img_ldr->save("D:\\Test\\");
 * echo ($result == true)?'All Image saved':$img_ldr->getError();
 * ```
 *
 * @package image-url-parser
 * @author Dykyi Roman
 * @version 1.0.0
 * @copyright never
 */
class ImageDownloader extends BaseUrl
{
    /** @var array - Error list */
    public $errors = [];

    /**
     * @param string $url Parse url path
     */
    public function __construct($url)
    {
        parent::__construct($url);
        $this->url = $url;
    }

    /**
     * Ger list error
     *
     * @return string
     */
    public function getError()
    {
        $err = '';
        if(!empty($this->errors)) {
            foreach($this->errors as $error) {
                $err .= $error.'<br>';
            }
        }
        return $err;
    }

    /**
     * Save find image
     *
     * @param string $path - The path to the folder where the image will be saved
     *
     * @return bool
     */
    public function save($path)
    {
        $this->errors = [];

        if(!is_dir($path)) {
            array_push($this->errors, 'Not found dir:'.$path);
            return false;
        }

        $image_list = new ParseUrl($this->url);
        if(empty($image_list->result)) {
            array_push($this->errors, 'Not found image');
            return false;
        }

        $image = new GetImage();
        $image->save_to = $path;
        foreach($image_list->result as $link) {
            $image->source = $link;
            if(!$image->download()) {
                array_push($this->errors, $image->error);
            }
        }
        return empty($this->error);
    }
}

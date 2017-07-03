<?php

namespace Dykyi;

/**
 * Class download image from remote
 *
 *
 * Usage:
 * ```
 * $image = new GetImage();
 * $image->source = 'https://habid.com/img/logo.png';
 * $image->save_to = 'D:\\Test\\'; // with trailing slash at the end
 *
 * if($image->download()){
 *    echo 'The image has been saved.'
 * else
 *    echo $image->error;
 * }
 * ```
 *
 * /**
 * Class GetImage
 * @package dykyi-roman/image-url-parser
 */
class GetImage
{
    const SAVE_FROM_CURL = 'curl';
    const SAVE_FROM_GD = 'gd';

    /** @var string The URL to the image (ex: http://www.domain.com/images/logo.jpg). */
    public $source;

    /** @var  string New Save name */
    public $save_name;

    /** @var string The path to the folder where the image will be saved (with trailing slash at the end) */
    public $save_to;

    /** @var  bool Are there any cases when the images you’re downloading have a wrong extension or do not have an extension at all?
     * Consider turning this to “true”. Based on the mime type of the image, an extension is automatically assigned to the file.
     */
    public $set_extension;

    /** @var int This is the 3rd argument that is passed to the image saver function. It is used for JPEG (from 0 to 100) or PNG (from 0 to 9) images */
    public $quality;

    /** @var string Error message */
    public $error;


    /**
     * Save Images
     *
     * @param $save_to
     *
     * @return bool
     */
    private function _LoadImageCURL($save_to)
    {
        $ch = curl_init($this->source);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $rawdata = curl_exec($ch);
        curl_close($ch);

        try {
            if(file_exists($save_to)) {
                unlink($save_to);
            }
            $fp = @fopen($save_to, 'x');
            if(!$fp) {
                $this->error = 'Failed to open stream(No such file or directory)';
                return false;
            }
            fwrite($fp, $rawdata);
            fclose($fp);
        } catch(\Exception $e) {
            $this->error = $e;
        }

        return true;
    }

    /**
     * Save images
     *
     * @param string $method - Save method (param - 'curl' or 'gd')
     *
     * @return bool|void
     */
    public function download($method = self::SAVE_FROM_CURL) // default method: cURL
    {
        $quality = null;
        $info = @GetImageSize($this->source);
        $mime = $info['mime'];

        if(!$mime) {
            $this->error = 'Could not obtain mime-type information. Make sure that the remote file is actually a valid image.';
            return false;
        }

        // What sort of image?
        $type = substr(strrchr($mime, '/'), 1);

        switch($type) {
            case 'jpeg':
                $image_create_func = 'ImageCreateFromJPEG';
                $image_save_func = 'ImageJPEG';
                $new_image_ext = 'jpg';

                // Best Quality: 100
                $quality = isset($this->quality)?$this->quality:100;
                break;

            case 'png':
                $image_create_func = 'ImageCreateFromPNG';
                $image_save_func = 'ImagePNG';
                $new_image_ext = 'png';

                // Compression Level: from 0  (no compression) to 9
                $quality = isset($this->quality)?$this->quality:0;
                break;

            case 'bmp':
                $image_create_func = 'ImageCreateFromBMP';
                $image_save_func = 'ImageBMP';
                $new_image_ext = 'bmp';
                break;

            case 'gif':
                $image_create_func = 'ImageCreateFromGIF';
                $image_save_func = 'ImageGIF';
                $new_image_ext = 'gif';
                break;

            case 'vnd.wap.wbmp':
                $image_create_func = 'ImageCreateFromWBMP';
                $image_save_func = 'ImageWBMP';
                $new_image_ext = 'bmp';
                break;

            case 'xbm':
                $image_create_func = 'ImageCreateFromXBM';
                $image_save_func = 'ImageXBM';
                $new_image_ext = 'xbm';
                break;

            default:
                $image_create_func = 'ImageCreateFromJPEG';
                $image_save_func = 'ImageJPEG';
                $new_image_ext = 'jpg';
        }

        if(isset($this->set_extension)) {
            $ext = strrchr($this->source, ".");
            $strlen = strlen($ext);
            $new_name = basename(substr($this->source, 0, -$strlen)).'.'.$new_image_ext;
        } else {
            $new_name = basename($this->source);
        }

        $save_to = $this->save_to.$new_name;

        return $this->_save($method, $image_create_func, $image_save_func, $save_to, $quality);
    }

    /**
     * Save method
     *
     * @param string $method
     * @param string $image_create_func
     * @param string $image_save_func
     * @param string $save_to
     * @param int $quality
     *
     * @return bool
     */
    private function _save($method, $image_create_func, $image_save_func, $save_to, $quality)
    {
        $save_image = false;
        if($method == self::SAVE_FROM_CURL) {
            $save_image = $this->_LoadImageCURL($save_to);
        } elseif($method == self::SAVE_FROM_GD) {
            $img = $image_create_func($this->source);
            if(isset($quality)) {
                $save_image = $image_save_func($img, $save_to, $quality);
            } else {
                $save_image = $image_save_func($img, $save_to);
            }
        }
        return $save_image;
    }
}

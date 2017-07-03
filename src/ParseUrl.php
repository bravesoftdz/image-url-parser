<?php

namespace Dykyi;

/**
 * Class parse image from remote
 *
 * Usage:
 * ```
 *
 * $parse_url = new ParseUrl('http://olx.com');
 * if (!$par->display()){
 *    echo $par->error;
 * }
 * ```
 *
 * Class ParseUrl
 * @package dykyi-roman/image-url-parser
 */
class ParseUrl extends BaseUrl{

    /** @var string - Error message */
    public $error = '';

    /** @var array Image list*/
    public $result;

    /**
     * @param string $format - Format image
     *
     * @param $url
     */
    public function __construct($url, $format = 'jpeg|png|jpg')
    {
        parent::__construct($url);

        // Pull in the external HTML contents
        $header = $this->_get_web_page($url);
        $content = $this->_get_content($header);
        if($content !== false) {
            $this->result = $this->_parse_content($content, $url, $format);
        }
    }

    /**
     * Get web page http or https protocol
     *
     * @param string $url - Web page link
     *
     * @return mixed
     */
    private function _get_web_page($url)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_USERAGENT      => "spider", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = $content;
        return $header;
    }

    /**
     *
     *
     * @param array $header - Response from curl
     *
     * @return bool|array
     */
    private function _get_content(array $header)
    {
        if($header['errno'] > 0) {
            $this->error = $header['errmsg'];
            return false;
        }
        return $header['content'];
    }

    /**
     * Parse curl content
     *
     * @param string $content
     * @param string $url
     * @param string $format
     *
     * @return array
     */
    private function _parse_content($content, $url, $format)
    {
        $parse = parse_url($url);
        // Use Regular Expressions to match all <img src="???" />
        preg_match_all('/src="(?P<image>.*\.('.$format.'))"/', $content, $out, PREG_PATTERN_ORDER);

        $array_link = [];
        foreach($out[1] as $k => $v) {
            // Prepend the URL with the $base URL (if needed)
            if(strpos($v, $parse['scheme'].'://') === false) {
                $v = $parse['host'].$v;
            }
            array_push($array_link, $v);
        }
        return $array_link;
    }

    /**
     * Display find images
     *
     * @return bool
     */
    public function display()
    {
        $result = !empty($this->result);
        if($result) {
            foreach($this->result as $link) {
                echo '<a href="'.$link.'">'.$link.'</a><br/>';
            }
        }
        return $result;
    }
}

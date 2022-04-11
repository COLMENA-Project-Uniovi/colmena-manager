<?php


namespace App\Utility;

class CurlPost
{
    private $url;
    private $options;

    /**
     * @param string $url     Request URL
     * @param array  $options cURL options
     */
    public function __construct($url, array $options = [])
    {
        $this->url = $url;
        $this->options = $options;
        $this->status = 200;
    }

    public function getUrl(){
        return $this->url;
    }
    public function setStatus($status){
        $this->status = $status;
    }
    public function getStatus(){
        return $this->status;
    }

    /**
     * Get the response
     * @return string
     * @throws \RuntimeException On cURL error
     */
    public function executePost($post = null, $headers=null)
    {
        $ch = curl_init($this->url);

        foreach ($this->options as $key => $val) {
            curl_setopt($ch, $key, $val);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        if(sizeof($headers)>0){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $errno    = curl_errno($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (is_resource($ch)) {
            curl_close($ch);
        }

        if (0 !== $errno) {
            throw new \RuntimeException($error, $errno);
        }
        $this->setStatus($status);

        return $response;
    }

    public function executePut($post = null, $headers=null)
    {
        $ch = curl_init($this->url);

        foreach ($this->options as $key => $val) {
            curl_setopt($ch, $key, $val);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        if(sizeof($headers)>0){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $errno    = curl_errno($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (is_resource($ch)) {
            curl_close($ch);
        }

        if (0 !== $errno) {
            throw new \RuntimeException($error, $errno);
        }
        $this->setStatus($status);

        return $response;
    }



    public function executeGet($headers)
    {
        $ch = curl_init($this->url);

        foreach ($this->options as $key => $val) {
            curl_setopt($ch, $key, $val);
        }
        curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $errno    = curl_errno($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (is_resource($ch)) {
            curl_close($ch);
        }

        if (0 !== $errno) {
            throw new \RuntimeException($error, $errno);
        }

        $this->setStatus($status);
        return $response;
    }

    public function executeDelete($headers)
    {
        $ch = curl_init($this->url);

        foreach ($this->options as $key => $val) {
            curl_setopt($ch, $key, $val);
        }
        curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $error    = curl_error($ch);
        $errno    = curl_errno($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (is_resource($ch)) {
            curl_close($ch);
        }

        if (0 !== $errno) {
            throw new \RuntimeException($error, $errno);
        }

        $this->setStatus($status);
        return $response;
    }
}

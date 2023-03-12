<?php
namespace Http\Request;

class Request
{
    private $post = [];
    private $pathVariable = [];
    private $queryParam = [];

    public function setPost($post)
    {
        $this->post = $post;
    }

    public function setPathVariable($pathVariable)
    {
        $this->pathVariable = $pathVariable;
    }

    public function setQueryParam($queryParam)
    {
        $this->queryParam = $queryParam;
    }

    public function getParam($param)
    {
        return $this->pathVariable[$param];
    }

    public function getPost($var)
    {
        return $this->post[$var];
    }

    public function getQueryParam($var)
    {
        return $this->queryParam[$var];
    }
}
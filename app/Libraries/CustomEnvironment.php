<?php
namespace App\Libraries;

class CustomEnvironment extends \Twig_Environment
{
    public function render($template, $context = array()) {

        define("DATATWIG", $context);
        return $this->load($template)->render($context);
    }
}
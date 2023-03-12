<?php
namespace Http\Controllers;

use Twig\Loader\FilesystemLoader;
use App\Libraries\CustomEnvironment;

class Controller extends \Twig_Environment
{
    public $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__."/../../Views/");
        $this->twig = new CustomEnvironment($loader, []);
    }
}
<?php
namespace Http\Controllers;

use Http\Controllers\Controller;
use Http\Request\Request;

class WelcomeController extends Controller
{
    public function Index()
    {
        $data = [
            'logo' => APP_LOGO,
            'bootstrapCss' => APP_BOOTSTRAP_CSS,
            'bootstrapJS' => APP_BOOTSTRAP_JS
        ];

        echo $this->twig->render('Welcome.html.twig', $data);
    }
}
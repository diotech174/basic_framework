<?php
namespace App\BasicExceptions;

class BasicException extends \Exception
{
    private function remove_html_tags($text) {
        $clean = '/<[^>]*>/';
        return preg_replace($clean, '', $text);
    }


    private function showMessage($message)
    {
        $html = "<!DOCTYPE html>\n";
        $html .= "\t</header>\n";
        $html .= "\t\t<meta charset='UTF-8'>\n";
        $html .= "\t\t</header>\n";
        $html .= "\t\t<meta http-equiv='X-UA-Compatible' content='IE=edge'>\n";
        $html .= "\t\t<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
        $html .= "\t\t<title>Basic Framework Exception</title>\n";
        $html .= "\t\t<link rel='icon' type='image/x-icon' href='data:image/png;base64,".APP_LOGO."'>\n";
        $html .= "\t\t<link href='data:text/css;base64,".APP_BOOTSTRAP_CSS."' rel='stylesheet'>\n";
        $html .= "\t\t<script src='data:text/javascript;base64,".APP_BOOTSTRAP_JS."'></script>\n";
        $html .= "\t</header>\n";
        $html .= "\t</body>";
        $html .=  "\t\t<div class='container-fluid'>\n";
        $html .=  "\t\t\t<div class='row'>\n";
        $html .=  "\t\t\t\t<div class='col-md-12'>\n";
        $html .=  "\t\t\t\t\t".$message."\n";
        $html .=  "\t\t\t\t</div>\n";
        $html .=  "\t\t\t</div>\n";
        $html .=  "\t\t</div>\n";
        $html .= "\t</body>\n";
        $html .= "</html>";

        echo $html;
    }

    public function __construct($mensagem = "", $codigo = 0, \Exception $anterior = null) {

        parent::__construct($mensagem, $codigo, $anterior);
    }

    public function __toString() {
        $this->showMessage($this->message);

        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 0);

        return "[BasicException] Ocorreu uma exceção: [{$this->code}]: {$this->remove_html_tags($this->message)}\n";
    }
}
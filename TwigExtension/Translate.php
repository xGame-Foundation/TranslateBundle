<?php
/**
 * Created by PhpStorm.
 * User: tdubuffet
 * Date: 30/06/15
 * Time: 17:29
 */

namespace TranslateBundle\TwigExtension;

class Translate extends \Twig_Extension
{

    private $translateService;

    public function __construct(\TranslateBundle\Services\Translate $translate)
    {
        $this->translateService = $translate;
    }

    public function getGlobals() {
        return array(
            'locale' => $this->translateService
        );
    }


    public function getName()
    {
        return 'ter_translate_extension';
    }
}

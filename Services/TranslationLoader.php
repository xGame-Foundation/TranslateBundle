<?php
/**
 * Created by PhpStorm.
 * User: tdubuffet
 * Date: 02/07/15
 * Time: 15:06
 */

namespace TranslateBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

class TranslationLoader implements LoaderInterface{

    private $em;

    public function __construct(EntityManager $em)
    {

        $this->em = $em;

    }


    /**
     * Insére les traductions en base de donnée dans le catalogue
     *
     * @param $resource
     * @param $locale
     * @param string $domain
     * @return MessageCatalogue
     */
    public function load($resource, $locale, $domain = 'messages')
    {

        $catalogue = new MessageCatalogue($locale);

        $wordings = $this->em->getRepository('TerTranslateBundle:Wording')->findAll();

        foreach($wordings as $trans) {
            $catalogue->set($trans->getCode(), $trans->translate($locale, false)->getValue(), $trans->getDomain()->getCode());
        }

        return $catalogue;
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: tdubuffet
 * Date: 30/06/15
 * Time: 12:57
 */

namespace TranslateBundle\Services;

use Symfony\Component\DependencyInjection\Container;

class Translate {

    /** @var  Container */
    private $container;

    private $languages = array();

    public function __construct(Container $container)
    {

        $this->container = $container;

        $this->initLanguage();
    }

    /**
     * Charge les locales
     */
    protected function initLanguage()
    {
        $this->languages = $this->container->get('doctrine')->getRepository('TerTranslateBundle:Language')->findAll();
    }

    /**
     * Retourne les locales du back et de la config
     *
     * @param bool $published
     * @return array
     */
    public function getLocalesConfig($published = true)
    {

        $tabLocale = [];

        // On regarde si il y a une configuration pour le bundle ter_translate
        if ( $this->container->hasParameter('ter_translate') ) {

            $locales = $this->getLocales(true);
            $terTranslate = $this->container->getParameter('ter_translate');

            foreach($terTranslate['host_name'] as $host => $locale) {
                if (array_search($locale, $locales) !== false) {

                    $request = $this->container->get('request');

                    $envUrl = '/';
                    if ($this->container->get('kernel')->getEnvironment() != "prod") {
                        $envUrl ="/app_" . $this->container->get('kernel')->getEnvironment() . ".php/";
                    }

                    $tabLocale[$locale] = array(
                        'code' => $locale,
                        'host' => $request->getScheme() . "://" . $host . $envUrl
                    );
                }
            }
        }

        return $tabLocale;
    }

    /**
     * Retourne la liste des locales
     *
     * @param $published boolean True = Retourne les locales publiées sur le front - False = Retourne toutes les locales
     * @return array Liste des locales
     */
    public function getLocales($published = false)
    {

        $locales = array();

        foreach($this->languages as $lang) {

            if ($published == true) {
                if ($lang->isPublished()) {
                    $locales[] = $lang->getCode();
                }

                continue;
            }

            $locales[] = $lang->getCode();
        }

        return $locales;
    }

    /**
     * Retourne la locale par defaut
     *
     * @return string
     */
    public function getDefautLocale()
    {
        foreach($this->languages as $lang) {

            if ($lang->isDefault()) {
                return $lang->getCode();
            }

        }

        return null;
    }

}
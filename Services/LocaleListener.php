<?php
/**
 * Created by PhpStorm.
 * User: tdubuffet
 * Date: 26/06/15
 * Time: 14:56
 */

namespace TranslateBundle\Services;


use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Ce listener permet de retrouver la locale en fonction du domaine de la requête et la configuration de l'application
 *
 * Class LocaleListener
 * @package TranslateBundle\Services
 */
class LocaleListener implements EventSubscriberInterface
{

    private $containerAware;

    public function __construct(Container $container)
    {

        $this->containerAware = $container;

    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $hostName = $event->getRequest()->getHost();

        // On regarde si il y a une configuration pour le bundle ter_translate
        if ( $this->containerAware->hasParameter('ter_translate') ) {

            $terTranslate = $this->containerAware->getParameter('ter_translate');

            // On regarde si un hostname est configuré pour cet environnement
            if (isset($terTranslate['host_name'][$hostName] )) {
                $locale = $terTranslate['host_name'][$hostName];

                // on set la locale
                $event->getRequest()->setLocale($locale);
                $this->containerAware->get('session')->set('_locale', $locale);
                $this->containerAware->get('session')->set('_localeLoader', $locale);

            }

        } else {
            throw new Exception('Hostname is not defined.');
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            // on enregistre l'événement avant de setter la locale donc avant le 18
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }
}
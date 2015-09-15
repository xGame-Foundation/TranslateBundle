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
 * Supprimer le cache
 *
 * Class ClarCache
 * @package TranslateBundle\Services
 */
class ClearCache
{

    private $containerAware;

    public function __construct(Container $container)
    {

        $this->containerAware = $container;

    }

    /**
     * Supprimer le cache des traductions
     * @return bool
     */
    public function clear()
    {

        $cacheDir = $this->containerAware->get('kernel')->getCacheDir() . '/translations/';

        if (is_dir($cacheDir)) {
            return $this->delTree($cacheDir);

        }

        return false;


    }

    /**
     * Supprimer le noeud de dossier
     *
     * @param $dir
     * @return bool
     */
    private function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }
}
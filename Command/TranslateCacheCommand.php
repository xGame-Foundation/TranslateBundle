<?php
/**
 * Created by PhpStorm.
 * User: tdubuffet
 * Date: 01/07/15
 * Time: 12:18
 */

namespace TranslateBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TranslateBundle\Entity\Domain;
use TranslateBundle\Entity\Wording;
use TranslateBundle\Entity\WordingTranslation;

class TranslateCacheCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('xgame:translate:cache')
            ->setDescription('Nettoyer le cache des traductions')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $dialog = $this->getHelperSet()->get('dialog');

        if (!$dialog->askConfirmation(
            $output,
            '<question>Confirmez-vous la suppression du cache des traductions ? (Y/N)</question>',
            false
        )) {
            return;
        }


        $cacheDir = $this->getContainer()->get('kernel')->getCacheDir() . '/translations/';

        if (is_dir($cacheDir)) {
            $this->delTree($cacheDir);

        }

        $output->writeln('Suppression terminé.');

    }

    private function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }
}
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

class TranslateResetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('xgame:translate:drop')
            ->setDescription('Supprimer tous les traductions & domain');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $dialog = $this->getHelperSet()->get('dialog');

        if (!$dialog->askConfirmation(
            $output,
            '<question>Confirmez-vous la suppression des traductions et des domaines ? (Y/N)</question>',
            false
        )) {
            return;
        }

        $domains = $this->getContainer()->get('doctrine')->getRepository("TerTranslateBundle:Domain")->findAll();

        foreach($domains as $domain)
        {

            $this->getContainer()->get('doctrine')->getManager()->remove($domain);

        }

        $this->getContainer()->get('doctrine')->getManager()->flush();

        $output->writeln('Suppression terminé.');

    }
}
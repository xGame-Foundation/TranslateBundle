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

class TranslateImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('xgame:translate:import')
            ->setDescription('Import les traductions des bundles pour les mettres en bases de données');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $dialog = $this->getHelperSet()->get('dialog');

        if (!$dialog->askConfirmation(
            $output,
            '<question>Confirmez-vous l\'import des traductions ? (Y/N)</question>',
            false
        )) {
            return;
        }

        $translate = $this->getContainer()->get('ter_translate.translate');

        foreach($translate->getLocales() as $locale) {

            $messages = $this->getContainer()->get('translator')->getCatalogue($locale);

            foreach ($messages->all() as $domain => $values) {

                $repoDomain = $this->getContainer()->get('doctrine')->getRepository('TerTranslateBundle:Domain');

                $addDomain = $repoDomain->findOneByCode($domain);

                if (!$addDomain) {

                    $addDomain = new Domain();
                    $addDomain->setName('[BACK] Domain System - ' . $domain)
                        ->setCode($domain)
                        ->setDescription('Domain system import');

                    $this->getContainer()->get('doctrine')->getManager()->persist($addDomain);

                    $output->writeln('<fg=green>Import du domaine ' . $domain . '</fg=green>');

                }


                foreach($values as $key => $value) {

                    $wording = $this->getContainer()->get('doctrine')->getRepository('TerTranslateBundle:Wording')->findOneByCode($key);

                    if (!$wording) {
                        $wording = new Wording();
                        $wording->setCode($key);
                        $wording->setDomain($addDomain);

                        $output->writeln('<fg=blue>Import du wording ' . $key . '</fg=blue>');
                    }

                    $wordingExists = $this->getContainer()->get('doctrine')->getRepository('TerTranslateBundle:WordingTranslation')->findOneBy(
                        array(
                            'locale' => $locale,
                            'translatable' => $wording
                        )
                    );

                    if (!$wordingExists) {
                        $trans = (new WordingTranslation())
                            ->setLocale($locale)
                            ->setValue($value)
                            ->setTranslatable($wording);

                        $wording->addTranslation($trans);


                        $output->writeln('<fg=yellow>Import de la traduction [' . $locale . ' - ' . $key . ']</fg=yellow>');
                    }
                    $this->getContainer()->get('doctrine')->getManager()->persist($wording);
                }
            }


            $this->getContainer()->get('doctrine')->getManager()->flush();
        }
    }
}
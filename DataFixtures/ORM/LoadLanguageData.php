<?php
/**
 * Created by PhpStorm.
 * User: tdubuffet
 * Date: 24/06/15
 * Time: 09:37
 */

namespace TranslateBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TranslateBundle\Entity\Language;

class LoadLanguageData implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $langFr = (new Language())
                ->setCode('fr')
                ->setDefault(true)
                ->setLocked(true)
                ->setName('Français')
                ->setPublished(true);


        $langEn = (new Language())
            ->setCode('en')
            ->setDefault(false)
            ->setLocked(true)
            ->setName('English')
            ->setPublished(true);


        $manager->persist($langFr);
        $manager->persist($langEn);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
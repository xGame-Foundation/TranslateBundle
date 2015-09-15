<?php

namespace TranslateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TranslateBundle\Entity\Language;
use TranslateBundle\Form\LanguageType;

/**
 * Class LanguageBackController
 * @package TranslateBundle\Controller
 * @Route("/language")
 */
class LanguageBackController extends Controller
{
    /**
     * Liste des langues
     *
     * @Route("/list", name="ter_back_language_list")
     * @Template()
     */
    public function listAction()
    {

        $languagues = $this->getDoctrine()->getRepository('TerTranslateBundle:Language')->findBy(array(), array(
            'name' => 'asc'
        ));

        return array(
            'languages' => $languagues
        );
    }


    /**
     * Ajouter un langue
     *
     * @Route("/add", name="ter_back_language_add")
     * @Template()
     */
    public function addAction()
    {

        $language = new Language();
        $form = $this->createForm(new LanguageType(), $language);
        $form->handleRequest($this->get('request'));

        if ($form->isValid()) {

            $this->getDoctrine()->getManager()->persist($language);
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success',
                $this->get('translator')->trans('flashmessage.add_language', array(), 'TerTranslateBundle')
            );

            return $this->redirect($this->generateUrl('ter_back_language_list'));

        }


        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Editer une langue
     *
     * @Route("/edit/{slug}", name="ter_back_language_edit")
     * @Template()
     */
    public function editAction(Language $language)
    {

        /** Si la langue est verouillé ou si c'est la langue par defaut, on interdit l'édition */
        if ($language->isLocked() || $language->isDefault()) {
            $this->get('session')->getFlashBag()->add('warning',
                $this->get('translator')->trans('flashmessage.lock_language', array(), 'TerTranslateBundle')
            );

            return $this->redirect($this->generateUrl('ter_back_language_list'));
        }

        $form = $this->createForm(new LanguageType(), $language);
        $form->handleRequest($this->get('request'));

        if ($form->isValid()) {

            $this->getDoctrine()->getManager()->persist($language);
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success',
                $this->get('translator')->trans('flashmessage.save', array(), 'TerAdminBundle')
            );

            return $this->redirect($this->generateUrl('ter_back_language_list'));

        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Supprimer une langue
     *
     * @Route("/delete/{slug}", name="ter_back_language_delete")
     * @Template()
     */
    public function deleteAction(Language $language)
    {
        $typeFlash = "danger";

        if ($language->isPublished()) {

            $flashMessage =  $this->get('translator')->trans('flashmessage.published_language', array(), 'TerTranslateBundle');

        } elseif($language->isLocked() || $language->isDefault()) {

           $flashMessage =  $this->get('translator')->trans('flashmessage.lock_language', array(), 'TerTranslateBundle');

        } else {
            $typeFlash    = "success";
            $flashMessage =  $this->get('translator')->trans('flashmessage.save', array(), 'TerAdminBundle');


            $this->getDoctrine()->getManager()->remove($language);
            $this->getDoctrine()->getManager()->flush();

        }


        $this->get('session')->getFlashBag()->add($typeFlash, $flashMessage);

        return $this->redirect($this->generateUrl('ter_back_language_list'));
    }

}

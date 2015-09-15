<?php

namespace TranslateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TranslateBundle\Entity\Domain;
use TranslateBundle\Form\DomainType;

/**
 * Class DomainBackController
 * @package TranslateBundle\Controller
 * @Route("/domain")
 */
class DomainBackController extends Controller
{

    /**
     * @Route("/add", name="ter_back_domain_add")
     * @Template()
     */
    public function addAction()
    {
        $domain = new Domain();
        $form   = $this->createForm(new DomainType(), $domain);
        $form->handleRequest($this->get('request'));

        if ($form->isValid()) {

            $this->getDoctrine()->getManager()->persist($domain);
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success',
                $this->get('translator')->trans('flashmessage.save', array(), 'TerAdminBundle')
            );

            return $this->redirectToRoute('ter_back_domain_list');
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/edit/{id}", name="ter_back_domain_edit")
     * @Template()
     */
    public function editAction(Domain $domain)
    {
        $form   = $this->createForm(new DomainType(), $domain);
        $form->handleRequest($this->get('request'));

        if ($form->isValid()) {

            $this->getDoctrine()->getManager()->persist($domain);
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success',
                $this->get('translator')->trans('flashmessage.save', array(), 'TerAdminBundle')
            );

            return $this->redirectToRoute('ter_back_domain_list');
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/delete/{id}", name="ter_back_domain_delete")
     * @Template()
     */
    public function deleteAction(Domain $domain)
    {

        $this->getDoctrine()->getManager()->remove($domain);
        $this->getDoctrine()->getManager()->flush($domain);

        return $this->redirectToRoute('ter_back_domain_list');
    }

    /**
     * @Route("/list", name="ter_back_domain_list")
     * @Template()
     */
    public function listAction()
    {
        $domains = $this->getDoctrine()->getRepository('TerTranslateBundle:Domain')->findBy(array(), array(
            'name' => 'asc'
        ));

        return array(
            'domains' => $domains
        );
    }
}

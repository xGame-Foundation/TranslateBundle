<?php

namespace TranslateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TranslateBundle\Entity\Domain;
use TranslateBundle\Entity\Wording;
use TranslateBundle\Entity\WordingTranslation;
use TranslateBundle\Form\WordingType;

/**
 * Class DomainBackController
 * @package TranslateBundle\Controller
 * @Route("/wording")
 */
class WordingBackController extends Controller
{

    /**
     * Liste des traduction par domain
     *
     * @Route("/list/domain/{code}", name="ter_back_wording_by_domain")
     * @Template("TerTranslateBundle:WordingBack:list_wording_by_domain.html.twig")
     */
    public function showWordingDomainAction(Domain $domain)
    {

        $wordings = $this->get('doctrine')->getRepository('TerTranslateBundle:Wording')->findBy(array(
            'domain' => $domain
        ));

        return array(
            'wordings' => $wordings
        );
    }

    /**
     * @Route("/add", name="ter_back_wording_add")
     * @Route("/add/domain/{code}", name="ter_back_wording_add_by_domain")
     * @Template()
     */
    public function addAction($code = null)
    {
        $wording = new Wording();

        if ($code) {

            $domain = $this->getDoctrine()->getRepository('TerTranslateBundle:Domain')->findOneByCode($code);
            if (!$domain) {
                throw new NotFoundHttpException('Domain is not found');
            } else {
                $wording->setDomain($domain);
            }

        }

        $translate = $this->get('ter_translate.translate');

        $form = $this->createForm(
            new WordingType(
                $translate->getLocales(),
                $translate->getDefautLocale(),
                (isset($domain) ? false : true)
            )
        , $wording);

        $form->handleRequest($this->get('request'));

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($wording);
            $this->getDoctrine()->getManager()->flush();


            $this->get('session')->getFlashBag()->add('success',
                $this->get('translator')->trans('flashmessage.save', [], 'TerAdminBundle')
            );

            /** On supprime le cache */
            $this->get('ter.translation.cache')->clear();

            $codeRedirect = $wording->getDomain()->getCode();

            return $this->redirectToRoute("ter_back_wording_by_domain", array('code' => $codeRedirect));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/edit/{id}", name="ter_back_wording_edit")
     * @Template()
     */
    public function editAction(Wording $wording)
    {
        $translate = $this->get('ter_translate.translate');

        $form = $this->createForm(new WordingType(
            $translate->getLocales(),
            $translate->getDefautLocale())
        , $wording);


        $form->handleRequest($this->get('request'));

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($wording);
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success',
                $this->get('translator')->trans('flashmessage.save', array(), 'TerAdminBundle')
            );

            /** On supprime le cache */
            $this->get('ter.translation.cache')->clear();

            return $this->redirectToRoute("ter_back_wording_by_domain", array('code' => $wording->getDomain()->getCode()));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/delete/{id}", name="ter_back_wording_delete")
     * @Template()
     */
    public function deleteAction(Wording $wording)
    {


        $this->getDoctrine()->getManager()->remove($wording);
        $this->getDoctrine()->getManager()->flush();


        //@todo Ajouter flash Message

        return $this->redirectToRoute("ter_back_wording_by_domain", array('code' => $wording->getDomain()->getCode()));
    }

    /**
     * @Route("/dashboard", name="ter_back_wording_dashboard")
     * @Template()
     */
    public function dashboardAction()
    {

        $translate = $this->get('ter_translate.translate');

        $form = $this->createForm(new WordingType(
            $translate->getLocales(),
            $translate->getDefautLocale()
        ));


        return array(
            'wordingForm' => $form->createView()
        );
    }

    /**
     * @Route("/export", name="ter_back_wording_export")
     */
    public function exportAction()
    {

        $em = $this->getDoctrine()->getEntityManager();

        $wordings = $em->getRepository('TerTranslateBundle:Wording')->findAll();
        $handle = fopen('php://memory', 'r+');
        $header = array(
            'Code', 'Domaine'
        );

        foreach($this->get('ter_translate.translate')->getLocales() as $locale) {
            $header[] = "Langue [" . strtoupper($locale)."]";
        }

        fputcsv($handle, $header);

        foreach ($wordings as $wording) {

            $row = array(
                $wording->getCode(),
                $wording->getDomain()->getCode()
            );

            foreach($this->get('ter_translate.translate')->getLocales() as $locale) {
                $row[] = $wording->translate($locale)->getValue();
            }

            fputcsv($handle, $row);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response($content, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="export_traduction_' . date('d-m-Y_H-i-s') . '.csv"'
        ));


    }

    /**
     * @Route("/clear-cache", name="ter_back_wording_cache")
     */
    public function clearCacheAction()
    {


        $this->get('ter.translation.cache')->clear();

        return $this->redirectToRoute('ter_back_wording_dashboard');

    }

}

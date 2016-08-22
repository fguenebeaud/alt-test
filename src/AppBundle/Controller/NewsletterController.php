<?php
/**
 * Created by PhpStorm.
 * User: Florent
 * Date: 21/08/2016
 * Time: 21:44
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Newsletter;
use AppBundle\Type\NewsletterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NewsletterController
 * @package AppBundle\Controller
 */
class NewsletterController extends Controller
{
    /**
     * @param Newsletter $newsletter
     * @return \Symfony\Component\Form\Form
     */
    private function    createCreateForm(Newsletter $newsletter)
    {
        $form = $this->createForm(new NewsletterType(), $newsletter, [
            'action' => $this->generateUrl('newsletter_create'),
            'method' => 'PUT'
        ]);

        $form->add(
            'submit',
            'submit',
            array('label' => 'Valider', 'attr' => ['class' => 'btn btn-sm btn-success'])
        );

        return $form;
    }


    /**
     * @Route("/admin/newsletter/new", name="newsletter_new")
     */
    public function newAction()
    {
        $entity = new Newsletter();
        $form   = $this->createCreateForm($entity);

        return $this->render('@App/Newsletter/new.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/newsletter/create", name="newsletter_create")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Newsletter();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            // TODO Créer un flashbag

            return $this->redirect($this->generateUrl('newsletter'));
        }

        return $this->render('@App/Newsletter/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/newsletter", name="newsletter")
     */
    public function indexAction()
    {
        $newsletters = $this->getDoctrine()->getRepository('AppBundle:Newsletter')->findAll();

        return $this->render('@App/Newsletter/index.html.twig', array(
            'newsletters' => $newsletters,
        ));
    }

    /**
     * @Route("/admin/newsletter/delete/{id}", requirements={"id" = "\d+"}, name="newsletter_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $newsletter = $em->getRepository('AppBundle:Newsletter')->find($id);

        $em->remove($newsletter);
        $em->flush();

        //TODO Ajouter un flashbag

        return $this->redirect($this->generateUrl('newsletter'));
    }
}

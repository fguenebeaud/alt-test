<?php
/**
 * Created by PhpStorm.
 * User: Florent
 * Date: 21/08/2016
 * Time: 21:44
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\Newsletter;
use AppBundle\Type\ContactType;
use AppBundle\Type\NewsletterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NewsletterController
 * @package AppBundle\Controller
 */
class NewsletterController extends Controller
{
    /**
     * @Route("/admin/newsletter/new", name="newsletter_new")
     * @return Response
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
     * @param Request $request
     * @return RedirectResponse|Response
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

            $this->get('session')->getFlashBag()->add(
                'success',
                'La newsletter a été enregistrée avec succès.'
            );

            return $this->redirect($this->generateUrl('newsletter'));
        }

        return $this->render('@App/Newsletter/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/newsletter/create", name="newsletter_front_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createFrontAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Newsletter();

        $action = $this->generateUrl('newsletter_front_create');

        $form = $this->createCreateForm($entity, $action);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'La newsletter a été enregistrée avec succès.'
            );

            return $this->redirect($this->generateUrl('homepage'));
        }

        $contactForm = $this->createCreateContactForm(new Contact());

        // Récupération des 4 dernières news // TODO Créer une fonction dans le repository
        $news = $em->getRepository('AppBundle:News')->findBy(
            [],
            ['createdAt' => 'DESC'],
            4
        );

        return $this->render('@App/Default/index.html.twig', array(
            'contact_form' => $contactForm->createView(),
            'newsletter_form' => $form->createView(),
            'news'   => $news,
        ));
    }

    /**
     * @Route("/admin/newsletter", name="newsletter")
     * @return Response
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
     * @param integer $id
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $newsletter = $em->getRepository('AppBundle:Newsletter')->find($id);

        $em->remove($newsletter);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'success',
            'La newsletter a été supprimée avec succès.'
        );

        return $this->redirect($this->generateUrl('newsletter'));
    }

    /**
     * @param Newsletter  $newsletter
     * @param string|null $action
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(Newsletter $newsletter, $action = null)
    {
        if (!$action) {
            $action = $this->generateUrl('newsletter_create');
        }
        $form = $this->createForm(new NewsletterType(), $newsletter, [
            'action' => $action,
            'method' => 'POST',
        ]);

        $form->add(
            'submit',
            'submit',
            array('label' => 'OK', 'attr' => ['class' => 'btn btn-sm btn-success'])
        );

        return $form;
    }

    /**
     * @param Contact $contact
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateContactForm(Contact $contact)
    {
        $form = $this->createForm(new ContactType(), $contact, [
            'action' => $this->generateUrl('contact_front_create'),
            'method' => 'POST',
        ]);

        $form->add(
            'submit',
            'submit',
            array('label' => 'OK', 'attr' => ['class' => 'btn btn-sm btn-success'])
        );

        return $form;
    }
}

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
 * Class ContactController
 * @package AppBundle\Controller
 */
class ContactController extends Controller
{
    /**
     * @Route("/admin/contact/new", name="contact_new")
     * @return Response
     */
    public function newAction()
    {
        $entity = new Contact();
        $form   = $this->createCreateForm($entity);

        return $this->render('@App/Contact/new.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/contact/create", name="contact_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Contact();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Le contact a été enregistré avec succès.'
            );

            return $this->redirect($this->generateUrl('contact'));
        }

        return $this->render('@App/Contact/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/contact/create", name="contact_front_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createFrontAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Contact();
        $action = $this->generateUrl('contact_front_create');
        $form = $this->createCreateForm($entity, $action);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Le contact a été enregistré avec succès.'
            );

            return $this->redirect($this->generateUrl('homepage'));
        }

        $newsletterForm = $this->createCreateNewsletterForm(new Newsletter());

        // Récupération des 4 dernières news // TODO Créer une fonction dans le repository
        $news = $em->getRepository('AppBundle:News')->findBy(
            [],
            ['createdAt' => 'DESC'],
            4
        );

        return $this->render('@App/Default/index.html.twig', array(
            'contact_form' => $form->createView(),
            'newsletter_form' => $newsletterForm->createView(),
            'news'   => $news,
        ));
    }

    /**
     * @Route("/admin/contact", name="contact")
     * @return Response
     */
    public function indexAction()
    {
        $contacts = $this->getDoctrine()->getRepository('AppBundle:Contact')->findAll();

        return $this->render('@App/Contact/index.html.twig', array(
            'contacts' => $contacts,
        ));
    }

    /**
     * @Route("/admin/contact/delete/{id}", requirements={"id" = "\d+"}, name="contact_delete")
     * @return RedirectResponse
     * @param integer $id
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('AppBundle:Contact')->find($id);

        $em->remove($contact);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'success',
            'Le contact a été supprimé avec succès.'
        );

        return $this->redirect($this->generateUrl('contact'));
    }

    /**
     * @Route("/admin/contact/{id}", requirements={"id" = "\d+"}, name="contact_show")
     * @return Response
     * @param integer $id
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Contact')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        return $this->render('@App/Contact/show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * @param Contact $contact
     * @param string|null $action
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateForm(Contact $contact, $action = null)
    {
        if (!$action) {
            $action = $this->generateUrl('contact_create');
        }
        $form = $this->createForm(new ContactType(), $contact, [
            'action' => $action,
            'method' => 'POST',
        ]);

        $form->add(
            'submit',
            'submit',
            array('label' => 'Envoyer', 'attr' => ['class' => 'btn btn-sm btn-success'])
        );

        return $form;
    }

    /**
     * @param Newsletter $contact
     * @return \Symfony\Component\Form\Form
     */
    private function createCreateNewsletterForm(Newsletter $contact)
    {
        $form = $this->createForm(new NewsletterType(), $contact, [
            'action' => $this->generateUrl('newsletter_front_create'),
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

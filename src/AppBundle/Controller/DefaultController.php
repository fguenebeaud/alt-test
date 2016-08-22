<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\Newsletter;
use AppBundle\Type\ContactType;
use AppBundle\Type\NewsletterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // Initialisation des deux formulaires
        $contactForm = $this->createCreateContactForm(new Contact());
        $newsletterForm = $this->createCreateNewsletterForm(new Newsletter());

        // Récupération des 4 dernières news // TODO Créer une fonction dans le repository
        $news = $em->getRepository('AppBundle:News')->findBy(
            [],
            ['createdAt' => 'DESC'],
            4
        );

        return $this->render('@App/Default/index.html.twig', array(
            'contact_form'      => $contactForm->createView(),
            'newsletter_form'   => $newsletterForm->createView(),
            'news'              => $news,
        ));
    }

    /**
     * @Route("/admin", name="admin")
     * @return Response
     */
    public function adminAction()
    {
        return $this->render('@App/Admin/index.html.twig');
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

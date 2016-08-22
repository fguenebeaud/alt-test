<?php
/**
 * Created by PhpStorm.
 * User: Florent
 * Date: 21/08/2016
 * Time: 21:44
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Type\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ContactController
 * @package AppBundle\Controller
 */
class ContactController extends Controller
{
    /**
     * @param Contact $contact
     * @return \Symfony\Component\Form\Form
     */
    private function    createCreateForm(Contact $contact)
    {
        $form = $this->createForm(new ContactType(), $contact, [
            'action' => $this->generateUrl('contact_create'),
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
     * @Route("/admin/contact/new", name="contact_new")
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

            // TODO Créer un flashbag

            return $this->redirect($this->generateUrl('contact'));
        }

        return $this->render('@App/Contact/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/contact", name="contact")
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
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('AppBundle:Contact')->find($id);

        $em->remove($contact);
        $em->flush();

        //TODO Ajouter un flashbag

        return $this->redirect($this->generateUrl('contact'));
    }
}

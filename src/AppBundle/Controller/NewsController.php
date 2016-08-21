<?php
/**
 * Created by PhpStorm.
 * User: Florent
 * Date: 21/08/2016
 * Time: 21:44
 */

namespace AppBundle\Controller;

use AppBundle\Entity\News;
use AppBundle\Type\NewsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NewsController
 * @package AppBundle\Controller
 */
class NewsController extends Controller
{
    /**
     * @param News $news
     * @return \Symfony\Component\Form\Form
     */
    private function    createCreateForm(News $news)
    {
        $form = $this->createForm(new NewsType(), $news, [
            'action' => $this->generateUrl('news_create'),
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
     * @Route("/admin/news/new", name="news_new")
     */
    public function newAction()
    {
        $entity = new News();
        $form   = $this->createCreateForm($entity);

        return $this->render('@App/News/new.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/news/create", name="news_create")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new News();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($entity->getPicture()) {
                // Get picture
                $file = $entity->getPicture();
                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                // Move the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('picture_directory'),
                    $fileName
                );
                $entity->setPicture($fileName);
            }

            $entity->setAuthor($this->getUser());
            $em->persist($entity);
            $em->flush();
            // TODO Créer un flashbag
            return $this->redirect($this->generateUrl('news'));
        }

        return $this->render('@App/News/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/news", name="news")
     */
    public function indexAction()
    {
        $news = $this->getDoctrine()->getRepository('AppBundle:News')->findAll();

        return $this->render('@App/News/index.html.twig', array(
            'news' => $news,
        ));
    }

    /**
     * @Route("/admin/news/delete/{id}", requirements={"id" = "\d+"}, name="news_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $news = $em->getRepository('AppBundle:News')->find($id);

        $em->remove($news);
        $em->flush();

        //TODO Ajouter un flashbag

        return $this->redirect($this->generateUrl('news'));
    }

    /**
     * @param News $news
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(News $news)
    {
        $form = $this->createForm(new NewsType(), $news, array(
            'action' => $this->generateUrl('news_edit', array('id' => $news->getId())),
            'method' => 'POST'
        ));

        $form->add(
            'submit',
            'submit',
            array('label' => 'Valider', 'attr' => ['class' => 'btn btn-sm btn-success'])
        );

        return $form;
    }

    /**
     * @Route("/admin/news/edit/{id}", requirements={"id" = "\d+"}, name="news_edit")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $news = $em->getRepository('AppBundle:News')->find($id);
        if (!$news) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $editForm = $this->createEditForm($news);

        if ($request->getMethod() == 'POST')
        {
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                if ($news->getPicture()) {
                    // Get picture
                    $file = $news->getPicture();
                    // Generate a unique name for the file before saving it
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();
                    // Move the file to the directory where brochures are stored
                    $file->move(
                        $this->getParameter('picture_directory'),
                        $fileName
                    );

                    $news->setPicture($fileName);
                }

                $em->flush();

                return $this->redirect($this->generateUrl('news'));
                // TODO Créer un flashbag
            }
        }

        return $this->render('@App/News/edit.html.twig', array(
            'edit_form' => $editForm->createView(),
            'news'      => $news,
        ));
    }
}

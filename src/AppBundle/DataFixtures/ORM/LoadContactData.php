<?php
/**
 * Created by PhpStorm.
 * User: Florent
 * Date: 21/08/2016
 * Time: 20:47
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Contact;

/**
 * Class LoadContactData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadContactData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface Container
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $array = array(
            'contact-1' => array(
                'firstname' => 'Florent',
                'lastname'  => 'Guenebeaud',
                'email'     => 'florent.guenebeaud@altima.com',
                'content'   => 'Bonjour, je recherche un emploi en tant que développeur Symfony',
                'object'    => Contact::OBJET_RECRUTEMENT,
            ),
            'contact-2' => array(
                'firstname' => 'Alexandre',
                'lastname'  => 'Miranda',
                'email'     => 'alexandre.miranda@altima.com',
                'content'   => 'Bonjour, je souhaiterai savoir comment fonctionne votre entreprise',
                'object'    => Contact::OBJET_DEMANDE_RENSEIGNEMENT,
            ),
            'contact-3' => array(
                'firstname' => 'Robert',
                'lastname'  => 'Polita',
                'email'     => 'robert.polita@altima.com',
                'content'   => 'Bonjour, je teste seulement le formulaire',
                'object'    => Contact::OBJET_AUTRE,
            ),
        );

        foreach ($array as $contactId => $contactData) {
            $contact = new Contact();
            $contact->setEmail($contactData['email']);
            $contact->setFirstname($contactData['firstname']);
            $contact->setLastname($contactData['lastname']);
            $contact->setContent($contactData['content']);
            $contact->setObject($contactData['object']);

            $manager->persist($contact);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 100;
    }
}

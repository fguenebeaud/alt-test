<?php
/**
 * Created by PhpStorm.
 * User: Florent
 * Date: 21/08/2016
 * Time: 20:47
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\News;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadNewsData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadNewsData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
            'news-1' => array(
                'title' => 'news 1',
                'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s.',
                'user'  => 'user-admin',
            ),
            'news-2' => array(
                'title' => 'news 2',
                'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s.',
                'user' => 'user-admin',
            ),
            'news-3' => array(
                'title' => 'news 3',
                'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s.',
                'user' => 'user-admin',
            ),
        );

        foreach ($array as $newsId => $newsData) {
            $news = new News();
            $news->setContent($newsData['content']);
            $news->setTitle($newsData['title']);

            $user = $manager->merge($this->getReference($newsData['user']));
            if ($user instanceof User) {
                $news->setAuthor($user);
            }

            $manager->persist($news);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 150;
    }
}

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
use AppBundle\Entity\User;

/**
 * Class LoadUserData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
            'admin' => array(
                'password'  => 'test',
                'salt'      => '45p8ubucam044gg8c4w4ocwccc88sko',
                'email'     => 'admin@admin.com',
                'roles'     => [
                    'ROLE_ADMIN',
                ],
            ),
        );

        $userManager = $this->container->get('fos_user.user_manager');

        foreach ($array as $userName => $userData) {
            $user = new User();
            $user->setUsername($userName);
            $user->setPlainPassword($userData['password']);
            $user->setEmail($userData['email']);
            $user->setEnabled(true);
            $user->setSalt($userData['salt']);

            foreach ($userData['roles'] as $role) {
                $user->addRole($role);
            }

            $userManager->updatePassword($user);

            $manager->persist($user);

            $this->addReference(
                sprintf('user-%s', $userName),
                $user
            );
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

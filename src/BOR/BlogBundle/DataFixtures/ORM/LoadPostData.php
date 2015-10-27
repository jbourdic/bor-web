<?php
namespace BOR\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BOR\BlogBundle\Entity\Post;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use BOR\UserBundle\Entity\User;

/**
 * Class LoadPostData
 *
 * @package BOR\BlogBundle\DataFixtures\ORM
 */
class LoadPostData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
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

        $user = $this->getReference('admin');
        $post = new Post();
        $post->setUser($user);
        $post->setTitle('Rénover sa salle de bain');
        $post->setContent('Voici comment rénover sa salle de bain');
        $post->setType('renovate');
        $post->setActive(1);
        $post->setMetaTitle('renovation de salle de bain');
        $post->setMetaDescription('explication renovation de salle de bain');

        $manager->persist($post);

        $post2 = new Post();
        $post2->setUser($this->getReference('expert'));
        $post2->setTitle('Quelques nouvelles du secteur immo');
        $post2->setContent('Voici Quelques nouvelles du secteur immo');
        $post2->setType('news');
        $post2->setActive(1);
        $post2->setMetaTitle('Quelques nouvelles du secteur immo');
        $post2->setMetaDescription('explication Quelques nouvelles du secteur immo');

        $manager->persist($post2);


        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 10; // l'ordre dans lequel les fichiers sont chargés
    }
}

<?php


namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // Creates admin Anaïs
        $user = new User();
        $user->setFirstname('Anaïs');
        $user->setLastname('Gounet');
        $user->setEmail('anais.gounet@nemea.fr');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setRole($this->getReference('role_2'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $this->addReference('admin', $user);
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [RoleFixtures::class];
    }
}

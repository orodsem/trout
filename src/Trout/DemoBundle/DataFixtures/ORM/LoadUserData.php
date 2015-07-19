<?php

namespace Trout\DemoBundle\DataFixtures\ORM;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Trout\DemoBundle\Entity\Position;
use Trout\DemoBundle\Entity\Profile;
use Trout\DemoBundle\Entity\ProfileLanguage;
use Trout\DemoBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $alice = new User();
        $alice->setUsername('alice');
        $alice->setPassword('alice123');
        $alice->setEmail('alice@email.com');

        $bob = new User();
        $bob->setUsername('bob');
        $bob->setPassword('bob123');
        $bob->setEmail('bob@email.com');

        $manager->persist($alice);
        $manager->persist($bob);

        // trout data
        $this->loadTrout($manager);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadTrout(ObjectManager $manager)
    {
        // load positions
        $uiDesigner = new Position();
        $uiDesigner->setTitle('User Experience & User Interface Designers (web and mobile)');
        $manager->persist($uiDesigner);

        $developer = new Position();
        $developer->setTitle('Developers / Programmers / Analysts (front-end, back-end open source, .NET & mobile)');
        $manager->persist($developer);

        $creativeTech = new Position();
        $creativeTech->setTitle('Creative Technologists');
        $manager->persist($creativeTech);

        $pm = new Position();
        $pm->setTitle('Digital Producers / Project Managers');
        $manager->persist($pm);

        $ba = new Position();
        $ba->setTitle('Digital Account Managers, Business Development Managers & BAs');
        $manager->persist($ba);

        $sme = new Position();
        $sme->setTitle('SEM / SEO / Social Media & Digital Marketing experts');
        $manager->persist($sme);

        $else = new Position();
        $else->setTitle('anything else in digital as roles are being redefined almost daily!');
        $manager->persist($else);
        $manager->flush();

        // adding alice profile
        $aliceProfile = new Profile();
        $aliceProfile->setFirstName('alice');
        $aliceProfile->setLastName('Smith');
        $aliceProfile->setProfilePhoto('/Users/orodsem/Downloads/image_04.jpg');
        $aliceProfile->setPosition($uiDesigner);
        $languageDetails = array(
            'en_Au' => ProfileLanguage::FULL_PROFESSIONAL,
            'fr_BE' => ProfileLanguage::LIMITED_WORKING,
        );
        $manager->persist($aliceProfile);
        $manager->flush();
        $this->createProfileLanguages($manager, $aliceProfile, $languageDetails);

        // adding bob profile
        $bobProfile = new Profile();
        $bobProfile->setFirstName('bob');
        $bobProfile->setLastName('Sem');
        $bobProfile->setProfilePhoto('/Users/orodsem/Downloads/image_04.jpg');
        $bobProfile->setPosition($uiDesigner);
        $languageDetails = array(
            'fa_IR' => ProfileLanguage::NATIVE,
            'en_AS' => ProfileLanguage::FULL_PROFESSIONAL,
            'de_DE' => ProfileLanguage::MIN_PROFESSIONAL,
        );
        $manager->persist($bobProfile);
        $manager->flush();
        $this->createProfileLanguages($manager, $bobProfile, $languageDetails);
    }

    /**
     * @param ObjectManager $manager
     * @param Profile $profile
     * @param $languageDetails
     * @return ArrayCollection
     */
    private function createProfileLanguages(ObjectManager $manager, Profile $profile, $languageDetails)
    {
        $languages = new ArrayCollection();

        foreach ($languageDetails as $localeCode => $proficiency) {
            $profileLanguage = new ProfileLanguage();
            $profileLanguage->setProfile($profile);
            $profileLanguage->setLanguageCode($localeCode);
            $profileLanguage->setProficiency($proficiency);

            $manager->persist($profileLanguage);
            $languages->add($profileLanguage);
        }

        $manager->flush();

        return $languages;
    }
}
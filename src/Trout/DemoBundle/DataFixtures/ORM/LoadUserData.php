<?php

namespace Trout\DemoBundle\DataFixtures\ORM;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Trout\DemoBundle\Entity\JobOffer;
use Trout\DemoBundle\Entity\Profile;
use Trout\DemoBundle\Entity\ProfileExperience;
use Trout\DemoBundle\Entity\ProfileLanguage;
use Trout\DemoBundle\Entity\Profilespeciality;
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
        // trout data
        $this->loadTrout($manager);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadTrout(ObjectManager $manager)
    {
        // adding Alice profile
        $aliceProfile = new Profile();
        $aliceProfile->setFirstName('Alice');
        $aliceProfile->setLastName('Smith');

        $manager->persist($aliceProfile);
        $manager->flush();

        // adding languages
        $languageDetails = array(
            'en_Au' => ProfileLanguage::FULL_PROFESSIONAL,
            'fr_BE' => ProfileLanguage::LIMITED_WORKING,
        );
        $this->createProfileLanguages($manager, $aliceProfile, $languageDetails);

        // adding experiences
        $experienceDetails = array(
            'User Experience & User Interface Designers (web and mobile)',
            'Developers / Programmers / Analysts (front-end, back-end open source, .NET & mobile)'
        );
        $this->createProfileExperience($manager, $aliceProfile, $experienceDetails);

        $specialityDetails = array(
            'UX / UI / CX',
            'OOP'
        );
        $this->createProfileSpeciality($manager, $aliceProfile, $specialityDetails);



        // adding Bob profile
        $bobProfile = new Profile();
        $bobProfile->setFirstName('Bob');
        $bobProfile->setLastName('Sem');

        $manager->persist($bobProfile);
        $manager->flush();

        // adding languages
        $languageDetails = array(
            'fa_IR' => ProfileLanguage::NATIVE,
            'en_AS' => ProfileLanguage::FULL_PROFESSIONAL,
            'de_DE' => ProfileLanguage::MIN_PROFESSIONAL,
        );
        $this->createProfileLanguages($manager, $bobProfile, $languageDetails);

        // adding experiences
        $experienceDetails = array(
            'Digital Account Managers, Business Development Managers & BAs',
            'SEM / SEO / Social Media & Digital Marketing experts',
            'Digital Producers / Project Managers'
        );
        $this->createProfileExperience($manager, $bobProfile, $experienceDetails);

        $specialityDetails = array(
            'SME',
            'SEO'
        );
        $this->createProfileSpeciality($manager, $bobProfile, $specialityDetails);

        // job offer
        $jobOfferDeveloper = new JobOffer();
        $jobOfferDeveloper->setCompany('trout');
        $jobOfferDeveloper->setSalaryMinimum(55000);
        $jobOfferDeveloper->setSalaryMaximum(66000);
        $manager->persist($jobOfferDeveloper);
        $manager->flush();

        $jobOfferSME = new JobOffer();
        $jobOfferSME->setCompany('foo co');
        $jobOfferSME->setSalaryMinimum(100000);
        $jobOfferSME->setSalaryMaximum(110000);

        $manager->persist($jobOfferSME);
        $manager->flush();
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

    /**
     * @param ObjectManager $manager
     * @param Profile $profile
     * @param $experienceDetails
     * @return ArrayCollection
     */
    private function createProfileExperience(ObjectManager $manager, Profile $profile, $experienceDetails)
    {
        $experiences = new ArrayCollection();

        foreach ($experienceDetails as $experience) {
            $profileExperience = new ProfileExperience();
            $profileExperience->setExperience($experience);
            $profileExperience->setProfile($profile);

            $manager->persist($profileExperience);
            $experiences->add($profileExperience);
        }

        $manager->flush();

        return $experiences;
    }

    /**
     * @param ObjectManager $manager
     * @param Profile $profile
     * @param $specialityDetails
     * @return ArrayCollection
     */
    private function createProfileSpeciality(ObjectManager $manager, Profile $profile, $specialityDetails)
    {
        $experiences = new ArrayCollection();

        foreach ($specialityDetails as $speciality) {
            $profileSpeciality = new ProfileSpeciality();
            $profileSpeciality->setSpeciality($speciality);
            $profileSpeciality->setProfile($profile);

            $manager->persist($profileSpeciality);
            $experiences->add($profileSpeciality);
        }

        $manager->flush();

        return $experiences;
    }
}
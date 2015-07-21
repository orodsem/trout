<?php

namespace Trout\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProfileLanguage
 *
 * @ORM\Table(name="trout_profile_language")
 * @ORM\Entity
 */
class ProfileLanguage
{
    const NO_PRACTICAL =  0;
    const ELEMENTARY =  1;
    const LIMITED_WORKING =  2;
    const MIN_PROFESSIONAL =  3;
    const FULL_PROFESSIONAL =  4;
    const NATIVE =  5;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="language_code", type="string", length=30, nullable=true)
     */
    private $languageCode;

    /**
     * @var string
     *
     * @ORM\Column(name="proficiency", type="string", length=255)
     */
    private $proficiency;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    /**
     * @param string $languageCode
     */
    public function setLanguageCode($languageCode)
    {
        $this->languageCode = $languageCode;
    }

    /**
     * @return string
     */
    public function getProficiency()
    {
        return $this->proficiency;
    }

    /**
     * @param string $proficiency
     */
    public function setProficiency($proficiency)
    {
        $this->proficiency = $proficiency;
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param Profile $profile
     */
    public function setProfile(Profile $profile)
    {
        $this->profile = $profile;
    }
}
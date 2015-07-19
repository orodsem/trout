<?php

namespace Trout\DemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 *
 * @ORM\Table(name="trout_profile")
 * @ORM\Entity
 */
class Profile
{
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
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;


    /**
     * @var string
     *
     * @ORM\Column(name="profile_photo", type="string", nullable=true)
     */
    private $profilePhoto;

    /**
     * Current position of this profile
     *
     * @ORM\ManyToOne(targetEntity="Position", inversedBy="profiles", cascade={"persist"})
     * @ORM\JoinColumn(name="position_id", referencedColumnName="id")
     */
    private $position;

    /**
     * @ORM\ManyToMany(targetEntity="JobOffer", mappedBy="profiles")
     */
    private $jobOffers;

    /**
     * @ORM\OneToMany(targetEntity="ProfileLanguage", mappedBy="profile", cascade={"persist"})
     */
    protected $languages;

    public function __construct()
    {
        $this->jobOffers = new ArrayCollection();
        $this->languages = new ArrayCollection();
    }

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
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getProfilePhoto()
    {
        return $this->profilePhoto;
    }

    /**
     * @param string $profilePhoto
     */
    public function setProfilePhoto($profilePhoto)
    {
        $this->profilePhoto = $profilePhoto;
    }

    /**
     * @return Position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param Position $position
     */
    public function setPosition(Position $position)
    {
        $this->position = $position;
    }

    /**
     * @return ProfileLanguage[]
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param ProfileLanguage[] $languages
     */
    public function setLanguages($languages)
    {
        foreach ($languages as $language) {
            $this->addLanguage($language);
        }
    }

    /**
     * @param ProfileLanguage $language
     */
    public function addLanguage(ProfileLanguage $language)
    {
        $this->languages->add($language);
    }

    /**
     * @return JobOffer[]
     */
    public function getJobOffers()
    {
        return $this->jobOffers;
    }

    /**
     * @param array $jobOffers
     */
    public function setJobOffers($jobOffers)
    {
        foreach ($jobOffers as $jobOffer) {
            if ($jobOffer instanceof JobOffer) {
                $this->jobOffers->add($jobOffer);
            }
        }
    }
}
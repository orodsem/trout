<?php

namespace Trout\DemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * Current position
     *
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=255, nullable=true)
     */
    private $position;

    /**
     * @ORM\OneToOne(targetEntity="File")
     * @ORM\JoinColumn(name="profile_photo_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     **/
    private $profilePhoto;

    /**
     * @ORM\ManyToMany(targetEntity="JobOffer", mappedBy="profiles")
     */
    private $jobOffers;

    /**
     * @ORM\OneToMany(targetEntity="ProfileLanguage", mappedBy="profile")
     */
    protected $languages;

    /**
     * @ORM\OneToMany(targetEntity="ProfileExperience", mappedBy="profile")
     */
    protected $experiences;

    public function __construct()
    {
        $this->jobOffers = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->experiences = new ArrayCollection();
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
     * @return File
     */
    public function getProfilePhoto()
    {
        return $this->profilePhoto;
    }

    /**
     * @param File $profilePhoto
     */
    public function setProfilePhoto(File $profilePhoto)
    {
        $this->profilePhoto = $profilePhoto;
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
     * @return ProfileExperience[]
     */
    public function getExperiences()
    {
        return $this->experiences;
    }

    /**
     * @param ProfileExperience[] $experiences
     */
    public function setExperiences($experiences)
    {
        foreach ($experiences as $experience) {
            $this->addExperience($experience);
        }
    }

    /**
     * @param ProfileExperience $experience
     */
    public function addExperience(ProfileExperience $experience)
    {
        $this->experiences->add($experience);
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

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}
<?php

namespace Trout\DemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 *
 * @ORM\Table(name="trout_job_offer")
 * @ORM\Entity
 */
class JobOffer
{
    const STATUS_DRFT = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_CLOSED = 2;

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
     * @ORM\Column(name="company", type="string", length=255)
     */
    private $company;

    /**
     * @var float
     *
     * @ORM\Column(name="salary_min", type="float")
     */
    private $salaryMinimum;

    /**
     * @var float
     *
     * @ORM\Column(name="salary_max", type="float")
     */
    private $salaryMaximum;

    /**
     * @var int
     *
     * @ORM\Column(name="expiry_date", type="integer", nullable=true)
     */
    private $expiryDate;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @ORM\JoinTable(name="trout_job_offers_profiles")
     * @ORM\ManyToMany(targetEntity="Profile", inversedBy="jobOffers")
     */
    private $profiles;

    public function __construct()
    {
        $this->status = self::STATUS_DRFT;
        $this->profiles = new ArrayCollection();
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
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return float
     */
    public function getSalaryMinimum()
    {
        return $this->salaryMinimum;
    }

    /**
     * @param float $salaryMinimum
     */
    public function setSalaryMinimum($salaryMinimum)
    {
        $this->salaryMinimum = $salaryMinimum;
    }

    /**
     * @return float
     */
    public function getSalaryMaximum()
    {
        return $this->salaryMaximum;
    }

    /**
     * @param float $salaryMaximum
     */
    public function setSalaryMaximum($salaryMaximum)
    {
        $this->salaryMaximum = $salaryMaximum;
    }

    /**
     * @return int
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @param int $expiryDate
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return Profile[]
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

    /**
     * @param mixed $profiles
     */
    public function setProfiles($profiles)
    {
        foreach ($profiles as $profile) {
            if ($profile instanceof Profile) {
                $this->profiles->add($profile);
            }
        }
    }
}
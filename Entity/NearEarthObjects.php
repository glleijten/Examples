<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NearEarthObjectsRepository")
 */
class NearEarthObjects
{
    const KILOMETERS = 'kilometers';
    const MILES = 'miles';
    const LUNAR = 'lunar';
    const ASTRONOMICAL = 'astronomical';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, name="name")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, name="nasa_jpl_url")
     */
    private $nasaJplUrl;

    /**
     * @var float
     * @ORM\Column(type="float" name="magnitude")
     */
    private $magnitude;

    /**
     * @var float
     * @ORM\Column(type="float", name="estimated_diameter_kilometers_min")
     */
    private $estimatedDiameterKilometersMin;

    /**
     * @var float
     * @ORM\Column(type="float", name="estimated_diameter_kilometers_max")
     */
    private $estimatedDiameterKilometersMax;

    /**
     * @var float
     * @ORM\Column(type="float", name="estimated_diameter_miles_min")
     */
    private $estimatedDiameterMilesMin;

    /**
     * @var float
     * @ORM\Column(type="float", name="estimated_diameter_miles_max")
     */
    private $estimatedDiameterMilesMax;

    /**
     * @var bool
     * @ORM\Column(type="bool", name="potentially_hazardous")
     */
    private $potentiallyHazardous;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="close_approach_date")
     */
    private $closeApproachDate;

    /**
     * @var int
     * @ORM\Column(type="bigint", name="epoch_date_close_approach")
     */
    private $epochDateCloseApproach;

    /** RELATIVE VELOCITY */

    /**
     * @var float
     * @ORM\Column(type="float", name="kilometers_per_second")
     */
    private $kilometersPerSecond;

    /**
     * @var float
     * @ORM\Column(type="float", name="kilometers_per_hour")
     */
    private $kilometersPerHour;

    /**
     * @var float
     * @ORM\Column(type="float", name="miles_per_hour")
     */
    private $milesPerHour;

    /** MISS DISTANCE */

    /**
     * @var float
     * @ORM\Column(type="float", name="astronomical")
     */
    private $astronomical;

    /**
     * @var float
     * @ORM\Column(type="float", name="lunar")
     */
    private $lunar;

    /**
     * @var int
     * @ORM\Column(type="bigint", name="kilometers")
     */
    private $kilometers;

    /**
     * @var int
     * @ORM\Column(type="bigint", name="miles")
     */
    private $miles;

    /**
     * @var string
     * @ORM\Column(type="string", name="orbiting_body")
     */
    private $orbiting_body;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getNasaJplUrl()
    {
        return $this->nasaJplUrl;
    }

    /**
     * @param mixed $nasaJplUrl
     */
    public function setNasaJplUrl($nasaJplUrl): void
    {
        $this->nasaJplUrl = $nasaJplUrl;
    }

    /**
     * @return float
     */
    public function getMagnitude(): float
    {
        return $this->magnitude;
    }

    /**
     * @param float $magnitude
     */
    public function setMagnitude(float $magnitude): void
    {
        $this->magnitude = $magnitude;
    }

    /**
     * @return float
     */
    public function getEstimatedDiameterKilometersMin(): float
    {
        return $this->estimatedDiameterKilometersMin;
    }

    /**
     * @param float $estimatedDiameterKilometersMin
     */
    public function setEstimatedDiameterKilometersMin(float $estimatedDiameterKilometersMin): void
    {
        $this->estimatedDiameterKilometersMin = $estimatedDiameterKilometersMin;
    }

    /**
     * @return float
     */
    public function getEstimatedDiameterKilometersMax(): float
    {
        return $this->estimatedDiameterKilometersMax;
    }

    /**
     * @param float $estimatedDiameterKilometersMax
     */
    public function setEstimatedDiameterKilometersMax(float $estimatedDiameterKilometersMax): void
    {
        $this->estimatedDiameterKilometersMax = $estimatedDiameterKilometersMax;
    }

    /**
     * @return float
     */
    public function getEstimatedDiameterMilesMin(): float
    {
        return $this->estimatedDiameterMilesMin;
    }

    /**
     * @param float $estimatedDiameterMilesMin
     */
    public function setEstimatedDiameterMilesMin(float $estimatedDiameterMilesMin): void
    {
        $this->estimatedDiameterMilesMin = $estimatedDiameterMilesMin;
    }

    /**
     * @return float
     */
    public function getEstimatedDiameterMilesMax(): float
    {
        return $this->estimatedDiameterMilesMax;
    }

    /**
     * @param float $estimatedDiameterMilesMax
     */
    public function setEstimatedDiameterMilesMax(float $estimatedDiameterMilesMax): void
    {
        $this->estimatedDiameterMilesMax = $estimatedDiameterMilesMax;
    }

    /**
     * @return bool
     */
    public function isPotentiallyHazardous(): bool
    {
        return $this->potentiallyHazardous;
    }

    /**
     * @param bool $potentiallyHazardous
     */
    public function setPotentiallyHazardous(bool $potentiallyHazardous): void
    {
        $this->potentiallyHazardous = $potentiallyHazardous;
    }

    /**
     * @return \DateTime
     */
    public function getCloseApproachDate(): \DateTime
    {
        return $this->closeApproachDate;
    }

    /**
     * @param \DateTime $closeApproachDate
     */
    public function setCloseApproachDate(\DateTime $closeApproachDate): void
    {
        $this->closeApproachDate = $closeApproachDate;
    }

    /**
     * @return int
     */
    public function getEpochDateCloseApproach(): int
    {
        return $this->epochDateCloseApproach;
    }

    /**
     * @param int $epochDateCloseApproach
     */
    public function setEpochDateCloseApproach(int $epochDateCloseApproach): void
    {
        $this->epochDateCloseApproach = $epochDateCloseApproach;
    }

    /**
     * @return float
     */
    public function getKilometersPerSecond(): float
    {
        return $this->kilometersPerSecond;
    }

    /**
     * @param float $kilometersPerSecond
     */
    public function setKilometersPerSecond(float $kilometersPerSecond): void
    {
        $this->kilometersPerSecond = $kilometersPerSecond;
    }

    /**
     * @return float
     */
    public function getKilometersPerHour(): float
    {
        return $this->kilometersPerHour;
    }

    /**
     * @param float $kilometersPerHour
     */
    public function setKilometersPerHour(float $kilometersPerHour): void
    {
        $this->kilometersPerHour = $kilometersPerHour;
    }

    /**
     * @return float
     */
    public function getMilesPerHour(): float
    {
        return $this->milesPerHour;
    }

    /**
     * @param float $milesPerHour
     */
    public function setMilesPerHour(float $milesPerHour): void
    {
        $this->milesPerHour = $milesPerHour;
    }

    /**
     * @return float
     */
    public function getAstronomical(): float
    {
        return $this->astronomical;
    }

    /**
     * @param float $astronomical
     */
    public function setAstronomical(float $astronomical): void
    {
        $this->astronomical = $astronomical;
    }

    /**
     * @return float
     */
    public function getLunar(): float
    {
        return $this->lunar;
    }

    /**
     * @param float $lunar
     */
    public function setLunar(float $lunar): void
    {
        $this->lunar = $lunar;
    }

    /**
     * @return int
     */
    public function getKilometers(): int
    {
        return $this->kilometers;
    }

    /**
     * @param int $kilometers
     */
    public function setKilometers(int $kilometers): void
    {
        $this->kilometers = $kilometers;
    }

    /**
     * @return int
     */
    public function getMiles(): int
    {
        return $this->miles;
    }

    /**
     * @param int $miles
     */
    public function setMiles(int $miles): void
    {
        $this->miles = $miles;
    }

    /**
     * @return string
     */
    public function getOrbitingBody(): string
    {
        return $this->orbiting_body;
    }

    /**
     * @param string $orbiting_body
     */
    public function setOrbitingBody(string $orbiting_body): void
    {
        $this->orbiting_body = $orbiting_body;
    }

    /**
     * @param $units
     *
     * @return float|int
     */
    public function getMissDistance($units)
    {
        switch ($units) {
            case self::KILOMETERS:
                return $this->getKilometers();
            case self::MILES:
                return $this->getMiles();
            case self::LUNAR:
                return $this->getLunar();
            case self::ASTRONOMICAL:
                return $this->getAstronomical();
            default:
                return $this->getKilometers();
        }
    }

    /**
     * @param $units
     *
     * @return float
     */
    public function getRelativeVelocityPerHour($units)
    {
        switch ($units) {
            case self::KILOMETERS:
                return $this->getKilometersPerHour();
            case self::MILES:
                return $this->getMilesPerHour();
            default:
                return $this->getKilometersPerHour();
        }
    }
}

<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 * @Vich\Uploadable
 */
class Location
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=7)
     */
    private $latitude;

    /**
     * @ORM\Column(type="decimal", precision=9, scale=7)
     */
    private $Longitude;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
    * @Vich\UploadableField(mapping="product_image", fileNameProperty="image")
    * @var File
    */
    private $imageFile;

    /**
    * @ORM\Column(type="datetime")
    * @var \DateTime
    */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Registration", mappedBy="location")
     */
    private $registrations;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude()
    {
        return $this->Longitude;
    }

    public function setLongitude($Longitude): self
    {
        $this->Longitude = $Longitude;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return Collection|Registration[]
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registration $registration): self
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations[] = $registration;
            $registration->setLocation($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): self
    {
        if ($this->registrations->contains($registration)) {
            $this->registrations->removeElement($registration);
            // set the owning side to null (unless already changed)
            if ($registration->getLocation() === $this) {
                $registration->setLocation(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
    
    public function __toString()
    {
        //TODO: Implement __toString() method.
        return $this->getName();
    }
}

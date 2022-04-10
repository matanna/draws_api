<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToMany(targetEntity: Draw::class, mappedBy: 'categories')]
    private $draws;

    public function __construct()
    {
        $this->draws = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Draw>
     */
    public function getDraws(): Collection
    {
        return $this->draws;
    }

    public function addDraw(Draw $draw): self
    {
        if (!$this->draws->contains($draw)) {
            $this->draws[] = $draw;
            $draw->addCategory($this);
        }

        return $this;
    }

    public function removeDraw(Draw $draw): self
    {
        if ($this->draws->removeElement($draw)) {
            $draw->removeCategory($this);
        }

        return $this;
    }
}

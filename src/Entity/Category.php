<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    const CATEGORY_FEATURED = "featured";
    const CATEGORY_GENERAL = "general";
    const CATEGORY_TRENDING = "trending";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    public string  $name;

    #[ORM\Column(type: 'string', length: 50)]
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setType($type)
    {
        if (!in_array($type, array(self::CATEGORY_FEATURED, self::CATEGORY_GENERAL, self::CATEGORY_TRENDING))) {
            throw new \InvalidArgumentException("Invalid type");
        }
        $this->type = $type;
    }
    public function getType(): string
    {
        return $this->type;
    }
}

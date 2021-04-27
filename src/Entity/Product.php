<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *         "get_list_products"={
 *             "method"="GET",
 *             "path"="/products",
 *              "normalizationContext" = {
 *                  "groups"={
 *                      "read:Product:collection"
 *                      }
 *              },
 *          },
 *          "post" = {
 *              "controller" = NotFoundAction::class,
 *              "read" = false,
 *              "output" = false,
 *          "openapi_context"={
 *                  "summary" = "hidden",
 *              },
 *          },
 *     },
 *     itemOperations={
 *          "get_products"={
 *              "method"="GET",
 *              "path"="/products/{id}",
 *              "requirements"={"id" = "\d+"},
 *              "normalizationContext" = {
 *                  "groups"={
 *                      "read:Customer:item"
 *                      }
 *                  },
 *              },
 *          "put" = {
 *              "controller" = NotFoundAction::class,
 *              "read" = false,
 *              "output" = false,
 *          "openapi_context"={
 *                  "summary" = "hidden",
 *              },
 *          },
 *          "patch" = {
 *              "controller" = NotFoundAction::class,
 *              "read" = false,
 *              "output" = false,
 *          "openapi_context"={
 *                  "summary" = "hidden",
 *              },
 *          },
 *          "delete" = {
 *              "controller" = NotFoundAction::class,
 *              "read" = false,
 *              "output" = false,
 *              "openapi_context"={
 *                  "summary" = "hidden",
 *              },
 *          },
 *     },
 * )
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:Product:collection"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     * @Assert\Length(
     *     max=30,
     *     maxMessage="Le nom doit contenir au maximum '{{ limit }}' caractères"
     * )
     * @Groups({"read:Product:item","read:Product:collection"})
     */
    private string $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull()
     * @Assert\Type(
     *     type="integer",
     *     message="le prix {{ value }} n'est pas valide, il doit être du type {{ type }}."
     * )
     * @Groups({"read:Product:item","read:Product:collection"})
     */
    private int $price;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read:Product:item"})
     */
    private string $description;

    /**
     * @ORM\Column(type="array")
     * @Groups({"read:Product:item","read:Product:collection"})
     */
    private array $category = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"read:Product:item"})
     */
    private ?array $propertys = [];

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:Product:item"})
     */
    private \DateTimeInterface $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"read:Product:item"})
     */
    private ?\DateTimeInterface $updated_at;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): array
    {
        return $this->category;
    }

    public function setCategory(array $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPropertys(): ?array
    {
        return $this->propertys;
    }

    public function setPropertys(?array $propertys): self
    {
        $this->propertys = $propertys;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ApiResource(
 *  attributes={
 *      "order"={"id":"DESC"},
 *      "pagination_items_per_page"=3,
 *  },
 *     itemOperations={
 *          "get_one_product"={
 *              "method"="GET",
 *              "path"="/products/{id}",
 *              "requirements"={"id" = "\d+"},
 *              "openapi_context"={
 *                   "summary" = "View the details of a product",
 *                   "description" = "Query to display a Bilemo product",
 *                   "tags" = {"One Product (Reseller/Admin)"}
 *              },
 *              "normalizationContext" = {
 *                  "groups"={
 *                      "read:Products:item"
 *                      }
 *                  },
 *              },
 *          "put_one_product" = {
 *              "method"="PUT",
 *              "path"="/products/{id}",
 *              "requirements"={"id" = "\d+"},
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Operation reserved for admin",
 *              "denormalization_context"={
 *                  "groups"={"put:Products:write"}
 *              },
 *              "openapi_context" = {
 *                  "summary" = "Put one product",
 *                  "description" = "Put by ID one product. Operation reserved for admin.",
 *                  "tags" = {"Put Product (Admin)"}
 *              }
 *          },
 *          "patch" = {
 *              "controller" = NotFoundAction::class,
 *              "read" = false,
 *              "output" = false,
 *          "openapi_context"={
 *                  "summary" = "hidden",
 *              },
 *          },
 *          "delete_product" = {
 *              "method"="DELETE",
 *              "path"="/products/{id}",
 *              "requirements"={"id" = "\d+"},
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Operation reserved for admin",
 *              "openapi_context" = {
 *                  "summary" = "Delete one product",
 *                  "description" = "Delete by ID one product. Operation reserved for admin.",
 *                  "tags" = {"Delete Product (Admin)"}
 *              }
 *          },
 *     },
 *     collectionOperations={
 *         "get_list_products"={
 *             "method"="GET",
 *             "path"="/products",
 *              "normalizationContext" = {
 *                  "groups"={
 *                      "read:Products:collection"
 *                      }
 *              },
 *             "openapi_context"={
 *                  "summary" = "list of products",
 *                  "description" = "Get the list of all products",
 *                  "tags" = {"Get Products (Reseller/Admin)"}
 *              },
 *          },
 *          "post_created_product" = {
 *          "method"="POST",
 *          "path"="/products",
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Operation reserved for admin",
 *          "denormalization_context"={
 *              "groups"={"post:Products:write"}
 *          },
 *          "openapi_context" = {
 *              "summary" = "Creates a new product",
 *              "description" = "Creates a new Bilemo product. Operation reserved for admin.",
 *              "tags" = {"Add Product (Admin)"}
 *              }
 *          },
 *     },
 * ),
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:Products:collection"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     * @Assert\Length(
     *     max=30,
     *     maxMessage="Le nom doit contenir au maximum '{{ limit }}' caractères"
     * )
     * @Groups({"read:Products:item","read:Products:collection","post:Products:write","put:Products:write"})
     */
    private string $name;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull()
     * @Assert\Type(
     *     type="float",
     *     message="le prix {{ value }} n'est pas valide, il doit être du type {{ type }}."
     * )
     * @Groups({"read:Products:item","read:Products:collection","post:Products:write","put:Products:write"})
     */
    private float $price;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read:Products:item","post:Products:write"})
     */
    private string $description;

    /**
     * @ORM\Column(type="array")
     * @Groups({"read:Products:item","read:Products:collection","post:Products:write","put:Products:write"})
     */
    private array $category = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"read:Products:item","post:Products:write","put:Products:write"})
     */
    private ?array $propertys = [];

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:Products:item"})
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"read:Products:item"})
     */
    private ?\DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
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
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}

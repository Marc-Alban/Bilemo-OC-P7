<?php
	
namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ApiResource(
 *  attributes={
 *      "order"={"id":"DESC"},
 *      "pagination_items_per_page"=3,
 * },
 *     itemOperations={
 *          "get_one_product"={
 *              "method"="GET",
 *              "path"="/products/{id}",
 *              "requirements"={"id" = "\d+"},
 *              "openapi_context"={
 *                   "summary" = "View the details of a product",
 *                   "description" = "Query to display a Bilemo product",
 *                   "tags" = {"One Product"}
 *              },
 *              "normalizationContext" = {
 *                  "groups"={
 *                      "read:Customer:item"
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
 *                  "tags" = {"Put Product"}
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
 *                  "tags" = {"Delete Product"}
 *              }
 *          },
 *     },
 *     collectionOperations={
 *         "get_list_products"={
 *             "method"="GET",
 *             "path"="/products",
 *              "normalizationContext" = {
 *                  "groups"={
 *                      "read:Product:collection"
 *                      }
 *              },
 *             "openapi_context"={
 *                  "summary" = "list of products",
 *                  "description" = "Get the list of all products",
 *                  "tags" = {"Get Products"}
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
 *              "tags" = {"Add Product (roles : Admin)"}
 *          }
 *          },
 *     },
 * ),
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "price": "exact", "name": "partial"})
 * ),
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
	private \DateTimeInterface $createdAt;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 * @Groups({"read:Product:item"})
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

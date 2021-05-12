<?php
	
	namespace App\Entity;
	
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\CustomerRepository;

/**
 * @ApiResource(
 *   attributes={
 *       "order"={"id":"DESC"}
 *   },
 * attributes={"pagination_items_per_page"=4},
 * itemOperations=
 * {
 *    "get_one_Customer"=
 *    {
 *          "method" = "GET",
 *			"route_name" = "customer_one_reseller",
 *          "requirements" ={"id" = "\d+"},
 *          "security"="is_granted('ROLE_RESELLER')",
 *          "security_message"="Resource reserved for Reseller",
 *          "normalization_context"={
 *              "groups"={"get:Customers:resellers"}
 *          },
 *          "openapi_context" = {
 *              "summary" = "Consult the details of a Customer linked to a client",
 *              "description" = "Query by identifier to consult Customer's informations. Resource reserved for Reseller.",
 *              "tags" = {"Single Customer (Reseller/Admin)"}
 *          }
 *      },
 *     "put" =
 *     {
 *       "controller" = NotFoundAction::class,
 *       "read" = false,
 *       "output" = false,
 *       "openapi_context"={
 *           "summary" = "hidden",
 *       },
 *     },
 *    "patch" =
 *    {
 *      "controller" = NotFoundAction::class,
 *      "read" = false,
 *      "output" = false,
 *      "openapi_context"={
 *              "summary" = "hidden",
 *          },
 *    },
 *    "delete_one_Customer"=
 *    {
 *      "method" = "DELETE",
 *      "path" = "/customers/{id}",
 *      "requirements" = {"id" = "\d+"},
 *      "security"="is_granted('ROLE_RESELLER')",
 *      "security_message"="Operation reserved for Reseller",
 *      "openapi_context" =
 *      {
 *          "summary" = "Delete one Customer",
 *          "description" = "Delete by ID one Customer. Operation reserved for Reseller.",
 *          "tags" = {"Remove Customer (Reseller/Admin)"}
 *      }
 *    },
 * },
 *
 *collectionOperations ={
 *	"get_list_Customers" =
 *	{
 *		"method" = "GET",
 *		"route_name" = "relation_customer_to_reseller",
 *      "requirements"={"id" = "\d+"},
 *		"security" = "is_granted('ROLE_RESELLER')",
 *		"security_message" = "Collection reserved for Reseller",
 *		"normalization_context" =
 *		{
 *			"groups" = {"get:Customer:collection"}
 *		},
 *		"openapi_context" =
 *		{
 *			"summary" = "Query to the list of Customers",
 *			"description" = "Displays the list of every Customers. You can also search with a filter by username. Collection reserved for Reseller.",
 *			"tags" ={"All Customers (Reseller/Admin)"}
 *		}
 *	},
 *	"post_created_Customer" =
 *	{
 *		"method" = "POST",
 *		"path" = "/customers",
 *		"security" = "is_granted('ROLE_RESELLER')",
 *		"security_message" = "Operation reserved for Reseller",
 *      "denormalization_context" =
 *		{
 *			"groups" = {"post:Customer:collection"}
 *		},
 *		"openapi_context" =
 *		{
 *			"summary" = "Creates a new Customer with your client reference",
 *			"description" = "Operation reserved for Reseller. Defines automatically the new Customer with your client reference.",
 *			"tags" = {"Add Customer (Reseller/Admin)"},
 *			"requestBody" =
 *			{
 *				"content" =
 *				{
 *					"application/json" =
 *					{
 *						"schema" =
 *						{
 *							"type" = "object","properties" =
 *							{
 *								"name" = {"type" = "string"},
 *								"lastName" = {"type" = "string"},
 *								"email" = {"type" = "string"},
 *								"password" = {"type" = "string"}
 *							},
 *							"example" =
 *							{
 *								"name" : "totot",
 *								"lastName" : "tatat",
 *								"email" : "reseller@gmail.fr",
 *								"password" : "123@..Text"
 *							},
 *						},
 *					},
 *				},
 *			},
 *		},
 *	},
 *	"manager_post_Customer" =
 *	{
 *		"security" = "is_granted('ROLE_ADMIN')",
 *		"security_message" = "Operation reserved for admin",
 *		"method" = "POST",
 *		"path" = "/admin/customers",
 *		"denormalization_context" =
 *		{
 *			"groups" =
 *			{
 *				"manager:Cutomer:write"
 *			}
 *		},
 *		"openapi_context" =
 *		{
 *			"summary" = "Creates a new Customer linked to a client",
 *			"description" = "Operation reserved for administrators. Defines automatically the new Customer with your client reference.",
 *			"tags" = {"Add Customer (Admin)"},
 *			"requestBody" =
 *			{
 *				"content" =
 *				{
 *					"application/json" =
 *					{
 *						"schema" =
 *						{
 *							"type" = "object","properties" =
 *							{
 *								"name" = {"type" = "string"},
 *								"lastName" = {"type" = "string"},
 *								"email" = {"type" = "string"},
 *								"password" = {"type" = "string"}
 *							},
 *							"example" =
 *							{
 *								"name" = "totot",
 *								"lastName" = "tatat",
 *								"email" = "reseller@orange.fr",
 *								"password" = "123@..Text"
 *							},
 *						},
 *					},
 *				},
 *			},
 *		},
 *	},
 *	},
 * ),
 * @ORM\Entity(repositoryClass = CustomerRepository::class),
 * @UniqueEntity(
 *   fields ={"email"},
 *   message = "Il existe déjà un Customer avec cette email: {{ value }} ! "
 *)
 * @UniqueEntity(
 *   fields ={"name"},
 *   message = "Il existe déjà un Customer avec ce nom: {{ value }} ! "
 *)
 * @UniqueEntity(
 *   fields ={"lastName"},
 *   message = "Il existe déjà un Customer avec ce prénom: {{ value }} ! "
 *)
 */
class Customer implements UserInterface
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 * @Groups({"get:Customers:resellers"})
	 */
	private int $id;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotNull()
	 * @Assert\Length(
	 *     min=3,
	 *     max=15,
	 *     minMessage="Le nom doit contenir au minimum {{ limit }} caractères",
	 *     maxMessage="Le nom doit contenir au maximum {{ limit }} caractères"
	 * )
	 * @Groups({"get:Customer:collection","post:Customer:collection","get:Customers:resellers"})
	 */
	private string $name;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotNull()
	 * @Assert\Length(
	 *     min=3,
	 *     max=15,
	 *     minMessage="Le prénom doit contenir au minimum {{ limit }} caractères",
	 *     maxMessage="Le prénom doit contenir au maximum {{ limit }} caractères"
	 * )
	 * @Groups({"get:Customer:collection","post:Customer:collection","get:Customers:resellers"})
	 */
	private string $lastName;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotNull()
	 * @Assert\Regex(
	 *     pattern="/^[a-zA-Z_.-]+@[a-zA-Z-]+\.[a-zA-Z-.]+$/",
	 *     match=true,
	 *     message="L'email doit être au format: test@live.fr …"
	 * )
	 * @Groups({"get:Customer:collection","post:Customer:collection","get:Customers:resellers"})
	 */
	private string $email;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotNull()
	 * @Assert\Regex(
	 *     pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{6,}$/",
	 *     message="mot de passe non valide, doit contenir la lettre majuscule et le numéro et les lettres "
	 * )
	 * @Assert\Length(min="5", max="20")
	 * @Groups({"post:Customer:collection"})
	 */
	private string $password;
	
	/**
	 * @ORM\Column(type="array", length=255)
	 * @Groups({"post:Customer:collection","get:Customers:resellers"})
	 */
	private array $roles;
	
	/**
	 * @ORM\Column(type="datetime")
	 * @Groups({"get:Customers:resellers"})
	 */
	private \DateTimeInterface $createdAt;
	
	
	/**
	 * @ORM\ManyToOne(targetEntity=Reseller::class, inversedBy="customers")
	 * @Groups({"get:Customers:resellers","get:Customer:collection"})
	 */
	private Reseller $customersResellers;
	
	
	public function __construct()
	{
		$this->setRoles(["ROLE_USER"]);
		$this->createdAt = new \DateTime();
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
	
	public function getLastName(): string
	{
		return $this->lastName;
	}
	
	public function setLastName(string $lastName): self
	{
		$this->lastName = $lastName;
		
		return $this;
	}
	
	public function getEmail(): string
	{
		return $this->email;
	}
	
	public function setEmail(string $email): self
	{
		$this->email = $email;
		
		return $this;
	}
	
	public function getPassword(): string
	{
		return $this->password;
	}
	
	public function setPassword(string $password): self
	{
		$this->password = $password;
		
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
	
	public function getRoles(): array
	{
		return $this->roles;
	}
	
	
	public function setRoles(?array $roles): self
	{
		$this->roles = $roles;
		return $this;
	}

	
	public function getSalt(): ?string
	{
		return null;
	}
	
	
	public function getUsername(): ?string
	{
		return $this->email;
	}
	
	public function eraseCredentials(): void
	{
	}
	

	public function getCustomersResellers():Reseller
	{
		return $this->customersResellers;
	}


	public function setCustomersResellers(Reseller $customersResellers): self
	{
		$this->customersResellers = $customersResellers;

		return $this;
	}
}

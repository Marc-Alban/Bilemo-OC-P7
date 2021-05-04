<?php
	
	namespace App\Entity;
	
use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
	
/**
 * @ApiResource(
 *   attributes={
 *       "order"={"id":"DESC"},
 *       "pagination_items_per_page"=3,
 *   },
 *
 * itemOperations=
 * {
 *    "get_one_Customer"=
 *    {
 *          "method" = "GET",
 *          "path" = "/customers/{id}",
 *          "requirements" ={"id" = "\d+"},
 *          "security"="is_granted('ROLE_RESELLER')",
 *          "security_message"="Resource reserved for Reseller",
 *          "normalization_context"={
 *              "groups"={"get:Customer:item"}
 *          },
 *          "openapi_context" = {
 *              "summary" = "Consult the details of a Customer linked to a client",
 *              "description" = "Query by identifier to consult Customer's informations. Resource reserved for Reseller.",
 *              "tags" = {"Single Customer"}
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
 *          "tags" = {"Remove Customer"}
 *      }
 *    },
 * },
 *
 *collectionOperations ={
 *	"get_list_Customers" =
 *	{
 *		"method" = "GET",
 *		"path" = "/customers",
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
 *			"tags" ={"All Customers"}
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
 *			"tags" = {"Add Customer (roles : Reseller)"},
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
 *								"last_name" = {"type" = "string"},
 *								"email" = {"type" = "string"},
 *								"password" = {"type" = "string"}
 *							},
 *							"example" =
 *							{
 *								"name" : "totot",
 *								"last_name" : "tatat",
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
 *			"tags" = {"Add Customer (roles : Admin)"},
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
 *								"last_name" = {"type" = "string"},
 *								"email" = {"type" = "string"},
 *								"password" = {"type" = "string"}
 *							},
 *							"example" =
 *							{
 *								"name" = "totot",
 *								"last_name" = "tatat",
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
 * @ApiFilter(
 *  SearchFilter::class,
 *  properties={
 *     "id" : "exact",
 *      "name":"ipartial",
 *      "last_name":"ipartial"
 *  }
 *),
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
 *   fields ={"last_name"},
 *   message = "Il existe déjà un Customer avec ce prénom: {{ value }} ! "
 *)
 */
class Customer implements UserInterface
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 * @Groups({"get:Customer:item"})
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
	 * @Groups({"get:Customer:collection","post:Customer:collection","get:Customer:item", "manager:Customer:write"})
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
	 * @Groups({"get:Customer:collection","post:Customer:collection","get:Customer:item", "manager:Customer:write"})
	 */
	private string $last_name;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotNull()
	 * @Assert\Regex(
	 *     pattern="/^[a-zA-Z_.-]+@[a-zA-Z-]+\.[a-zA-Z-.]+$/",
	 *     match=true,
	 *     message="L'email doit être au format: test@live.fr …"
	 * )
	 * @Groups({"get:Customer:collection","post:Customer:collection","get:Customer:item", "manager:Customer:write"})
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
	 * @Groups({"post:Customer:collection", "manager:Customer:write"})
	 */
	private string $password;
	
	/**
	 * @ORM\Column(type="array", length=255)
	 * @Groups({"post:Customer:collection","get:Customer:item", "manager:Customer:write"})
	 */
	private array $roles;
	
	/**
	 * @ORM\Column(type="datetime")
	 * @Groups({"get:Customer:item"})
	 */
	private \DateTimeInterface $created_at;
	
	
	/**
	 * @ORM\ManyToOne(targetEntity=Reseller::class, inversedBy="customers")
	 * @Groups({"get:Customer:item", "manager:Customer:write"})
	 */
	private Reseller $resellers;
	
	
	public function __construct()
	{
		$this->setRoles(["ROLE_USER"]);
		$this->created_at = new \DateTime();
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
		return $this->last_name;
	}
	
	public function setLastName(string $last_name): self
	{
		$this->last_name = $last_name;
		
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
		return $this->created_at;
	}
	
	public function setCreatedAt(\DateTimeInterface $created_at): self
	{
		$this->created_at = $created_at;
		
		return $this;
	}
	
	public function getRoles(): array
	{
		return ['ROLE_USER'];
	}
	
	
	public function setRoles(?array $roles): self
	{
		$this->roles = $roles;
		return $this;
	}

	public function getResellers(): Reseller
	{
		return $this->resellers;
	}
	
	public function setResellers(Reseller $resellers): self
	{
		$this->resellers = $resellers;
		
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
	
}

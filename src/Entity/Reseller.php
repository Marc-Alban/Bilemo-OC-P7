<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Admin;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Action\NotFoundAction;
use App\Repository\ResellerRepository;

/**
* @ApiResource(
*  attributes={
*      "order"={"id":"DESC"}
* },
*    itemOperations=
*    {
*          "get_one_reseller"={
*              "method"="GET",
*              "path"="/resellers/{id}",
*             "requirements" = {"id" = "\d+"},
*             "security"="is_granted('ROLE_ADMIN')",
*             "security_message"="Operation reserved for Admin",
*              "openapi_context"={
*                   "summary" = "View the details of a reseller",
*                   "description" = "Query to display a Bilemo reseller",
*                   "tags" = {"One Reseller (Admin)"}
*              },
*              "normalizationContext" = {
*                  "groups"={
*                      "get:oneReseller:read"
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
*          "openapi_context"={
*                  "summary" = "hidden",
*              },
*          },
*    },
*   collectionOperations=
*   {
*         "post_created_Reseller"=
*          {
*             "method"="POST",
*             "path"="/auth/register",
*             "openapi_context"={
*                  "summary" = "Sign up (Reseller)",
*                  "description" = "Sign a Reseller with datas",
*                  "tags" = {"Register (Reseller/Admin)"},
*                   "requestBody" = {
*                       "content" = {
*                           "application/json" = {
*                               "schema"  = {
*                                   "type"       =   "object",
*                                   "properties" =
*                                       {
*                                       "name"     = {"type" = "string"},
*                                       "email"    = {"type" = "string"},
*                                       "password" = {"type" = "string"},
*                                       },
*                                },
*                               "example" = {
*                                   "name"     = "name",
*                                   "email"    = "name@entreprise.fr",
*                                   "password" = "123@..text",
*                               },
*                           },
*                       },
*                   },
*              },
*          },
 *         "post_login_Reseller"=
 *          {
 *             "method"="POST",
 *             "path"="/auth/login",
 *             "openapi_context"={
 *                  "summary" = "Login",
 *                  "description" = "Log a Reseller/Admin",
 *                  "tags" = {"Login (Reseller/Admin)"},
 *                   "requestBody" = {
 *                       "content" = {
 *                           "application/json" = {
 *                               "schema"  = {
 *                                   "type"       =   "object",
 *                                   "properties" =
 *                                       {
 *                                       "email"    = {"type" = "string"},
 *                                       "password" = {"type" = "string"},
 *                                       },
 *                                },
 *                               "example" = {
 *                                   "email"    = "reseller@orange.fr",
 *                                   "password" = "123@..text",
 *                               },
 *                           },
 *                       },
 *                   },
 *              },
 *          },
*      "get_list_Reseller"={
*          "method"="GET",
*          "path"="/resellers",
*          "security"="is_granted('ROLE_ADMIN')",
*          "security_message"="Operation reserved for Admin",
*          "normalizationContext"={
*              "groups"={"get:Reseller:read"}
*          },
*          "openapi_context" = {
*              "summary" = "Query to the list of Resellers",
*              "description" = "This collection of resources displays the list of Bilemo Resellers.",
*              "tags" = {"List of Resellers (Admin)"}
*           }
*      }
*  },
* ),
* @ORM\Entity(repositoryClass=ResellerRepository::class),
*
* @UniqueEntity(
*     fields={"email"},
*     message="Il existe déjà un reseller avec cette email: '{{ value }}' ! "
* )
* @UniqueEntity(
*     fields={"name"},
*     message="Il existe déjà un reseller avec ce nom: {{ value }} ! "
* )
*/
class Reseller implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=3,
     *     max=30,
     *     minMessage="Le nom doit contenir au minimum '{{ limit }}' caractères",
     *     maxMessage="Le nom doit contenir au maximum '{{ limit }}' caractères"
     * )
     * @Groups({"get:Reseller:read","get:oneReseller:read"})
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z_.-]+@[a-zA-Z-]+\.[a-zA-Z-.]+$/",
     *     match=true,
     *     message="L'email doit être au format: test@entreprise.fr …"
     * )
     * @Groups({"get:Reseller:read","get:oneReseller:read"})
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{6,}$/",
     *     message="mot de passe non valide, doit contenir la lettre majuscule et le numéro et les lettres "
     * )
     * @Assert\Length(
     *     min=3,
     *     max=30,
     *     minMessage="Le password doit contenir au minimum '{{ limit }}' caractères",
     *     maxMessage="Le password doit contenir au maximum '{{ limit }}' caractères"
     * )
     */
    private string $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="array", length=255, nullable=true)
     * @Groups({"get:Reseller:read","get:oneReseller:read"})
     */
    private array $roles = ["ROLE_RESELLER"];


    /**
     * @ORM\OneToMany(targetEntity=Customer::class, mappedBy="customersResellers")
     * @Groups({"get:Reseller:read","get:oneReseller:read"})
     */
    private ?Collection $customers;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="adminResellers")
     * @Groups({"get:Reseller:read","get:oneReseller:read"})
     */
    private ?Admin $admin;



    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
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


    public function getRoles(): ?array
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

    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setCustomersResellers($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getCustomersResellers() === $this) {
                $customer->setCustomersResellers($this);
            }
        }

        return $this;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;

        return $this;
    }
}

<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Reseller;
use ApiPlatform\Core\Action\NotFoundAction;
use App\Repository\AdminRepository;

/**
* @ApiResource(
*    itemOperations=
*    {
*          "get" = {
*              "controller" = NotFoundAction::class,
*              "read" = false,
*              "output" = false,
*          "openapi_context"={
*                  "summary" = "hidden",
*              },
*          },
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
*         "get" = {
*              "controller" = NotFoundAction::class,
*              "read" = false,
*              "output" = false,
*          "openapi_context"={
*                  "summary" = "hidden",
*              },
*          },
*         "post" = {
*              "controller" = NotFoundAction::class,
*              "read" = false,
*              "output" = false,
*          "openapi_context"={
*                  "summary" = "hidden",
*              },
*          },
*   },
* ),
* @ORM\Entity(repositoryClass=AdminRepository::class)
* @UniqueEntity(
*     fields={"email"},
*     message="Il existe déjà un customer avec cette email: '{{ value }}' ! "
* )
* @UniqueEntity(
*     fields={"name"},
*     message="Il existe déjà un customer avec ce nom: {{ value }} ! "
* )
 */
class Admin implements UserInterface
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
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z_.-]+@[a-zA-Z-]+\.[a-zA-Z-.]+$/",
     *     match=true,
     *     message="L'email doit être au format: test@live.fr …"
     * )
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
     * @ORM\Column(type="array", length=255)
     */
    private array $roles = ["ROLE_ADMIN"];

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $createdAt;


    /**
     * @ORM\OneToMany(targetEntity=Reseller::class, mappedBy="admin")
     */
    private ?Collection $adminResellers;



    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->adminResellers = new ArrayCollection();
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
// -------------- userInterface

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


// -----------
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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

    /**
     * @return Collection|Reseller[]
     */
    public function getAdminResellers(): ?Collection
    {
        return $this->adminResellers;
    }

    public function addAdminResellers(Reseller $reseller): self
    {
        if (!$this->adminResellers->contains($reseller)) {
            $this->adminResellers[] = $reseller;
            $reseller->setAdmin($this);
        }

        return $this;
    }

    public function removeAdminResellers(Reseller $reseller): self
    {
        if ($this->adminResellers->removeElement($reseller)) {
            if ($reseller->getAdmin() === $this) {
                $reseller->setAdmin($this);
            }
        }
        return $this;
    }
}

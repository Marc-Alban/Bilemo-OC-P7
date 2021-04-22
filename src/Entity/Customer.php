<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @ApiResource(
 *     collectionOperations = {
 *          "get_list_customers" = {
 *              "method" = "GET",
 *              "path" = "/api/customers",
 *          },
 *          "post_created_customer" = {
 *              "method" = "POST",
 *              "path" = "/api/customers",
 *          },
 *    },
 *    itemOperations = {
 *          "get_customers" = {
 *              "method" = "GET",
 *              "path" = "/api/customers/{id}",
 *              "requirements" = {"id" = "\d+"},
 *              "acces_control" = "is_granted('ROLE_RESSELER')",
 *              ""
 *          },
            "put_customers" = {
 *              "method" = "PUT",
 *              "path" = "/api/customers/{id}",
 *              "requirements" = {"id" = "\d+"},
 *          },
 *          "patch_customers" = {
 *              "method" = "PATCH",
 *              "path" = "/api/customers/{id}",
 *              "requirements" = {"id" = "\d+"},
 *          },
 *          "delete_customers" = {
 *              "method" = "DELETE",
 *              "path" = "/api/customers/{id}",
 *              "requirements" = {"id" = "\d+"},
 *          },
 *     }
 * )
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Il existe déjà un customer avec cette email: {{ value }} ! "
 * )
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Il existe déjà un customer avec ce nom: {{ value }} ! "
 * )
 * @UniqueEntity(
 *     fields={"last_name"},
 *     message="Il existe déjà un customer avec ce prénom: {{ value }} ! "
 * )
 */
class Customer implements UserInterface
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
     * @Assert\NotNull()
     * @Assert\Length(
     *     min=3,
     *     max=15,
     *     minMessage="Le nom doit contenir au minimum {{ limit }} caractères",
     *     maxMessage="Le nom doit contenir au maximum {{ limit }} caractères"
     * )
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min=3,
     *     max=15,
     *     minMessage="Le prénom doit contenir au minimum {{ limit }} caractères",
     *     maxMessage="Le prénom doit contenir au maximum {{ limit }} caractères"
     * )
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private string $last_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\NotNull()
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
     * @Assert\NotNull()
     * @Assert\Regex(
     *     pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{6,}$/",
     *     message="mot de passe non valide, doit contenir la lettre majuscule et le numéro et les lettres "
     * )
     * @Assert\Length(min="5", max="20")
     */
    private string $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $created_at;


    /**
     * @ORM\ManyToOne(targetEntity=Reseller::class, inversedBy="customers")
     */
    private Reseller $resellers;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
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
        $this->password = $this->passwordEncoder->encodePassword($this, $password);

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

    public function getResellers(): Reseller
    {
        return $this->resellers;
    }

    public function setResellers(Reseller $resellers): self
    {
        $this->resellers = $resellers;

        return $this;
    }


    public function getRoles(): array
    {
        return ['ROLE_USER'];
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

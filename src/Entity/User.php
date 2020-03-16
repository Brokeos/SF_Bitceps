<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource(
 *     attributes={"access_control"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *         "post"={"access_control"="is_granted('ROLE_ADMIN')", "access_control_message"="Seuls les administrateurs peuvent ajouter des utilisateurs."},
 *         "get"={"access_control"="is_granted('ROLE_USER')", "access_control_message"="Vous devez être identifé."}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read", "write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Adresse email invalide"
     * )
     * @Assert\Email(
     *     message = "Adresse email invalide"
     * )
     * @Groups({"read", "write"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 6,
     *      max = 32,
     *      minMessage = "Le mot de passe doit contenir au minimum {{ limit }} caractères !",
     *      maxMessage = "Le mot de passe doit contenir au maximum {{ limit }} caractères !"
     * )
     * @Groups({"read", "write"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      max = 20,
     *      minMessage = "Le prénom doit contenir au minimum {{ limit }} caractères !",
     *      maxMessage = "Le prénom doit contenir au maximum {{ limit }} caractères !"
     * )
     * @Groups({"read", "write"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      max = 20,
     *      minMessage = "Le nom doit contenir au minimum {{ limit }} caractères !",
     *      maxMessage = "Le nom doit contenir au maximum {{ limit }} caractères !"
     * )
     * @Groups({"read", "write"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Choice({"male", "female"})
     * @Groups({"read", "write"})
     */
    private $gender;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Groups({"read", "write"})
     */
    private $birthdate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tarif", inversedBy="users")
     * @Groups({"read", "write"})
     */
    private $subscription;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Lesson", inversedBy="users")
     * @ORM\JoinTable(name="participations")
     * @Groups({"read", "write"})
     */
    private $participations;

    /**
     * @ORM\Column(type="json")
     * @Groups({"read", "write"})
     */
    private $roles = [];

    public function __construct()
    {
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getSubscription(): ?Tarif
    {
        return $this->subscription;
    }

    public function setSubscription(?Tarif $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Lesson[]
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Lesson $participation): self
    {
        if (!$this->participations->contains($participation)) {
            $this->participations[] = $participation;
        }

        return $this;
    }

    public function removeParticipation(Lesson $participation): self
    {
        if ($this->participations->contains($participation)) {
            $this->participations->removeElement($participation);
        }

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}

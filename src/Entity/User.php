<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(null, message: "Vous devez indiquer votre email")]
    #[Assert\Length(min: 5, max: 250, minMessage: 'Votre email doit contenir au moins 5 caractères', maxMessage: 'Votre email ne peut pas dépasser 250 caractère')]
    #[Assert\Email(message: "Votre email n'est pas valide")]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    // Décommenter les validations en prod
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(null, message: "Vous devez indiquer votre mot de passe")]
    //#[Assert\Regex(pattern: "#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#", message: "Votre mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spéciale.")]
    //#[Assert\Length(min: 8, minMessage: "Votre mot de passe doit contenir au moins 8 caractères.")]
    private $password;

    #[ORM\OneToMany(mappedBy: 'userComment', targetEntity: Comment::class)]
    private $comments;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isNewsletter;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUserComment($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUserComment() === $this) {
                $comment->setUserComment(null);
            }
        }

        return $this;
    }

    public function getIsNewsletter(): ?bool
    {
        return $this->isNewsletter;
    }

    public function setIsNewsletter(?bool $isNewsletter): self
    {
        $this->isNewsletter = $isNewsletter;

        return $this;
    }
}

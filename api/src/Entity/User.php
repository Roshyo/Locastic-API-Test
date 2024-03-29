<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}},
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Groups({"user:read", "user:write"})
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     *
     * @Groups({"user:read"})
     */
    private $roles = ['ROLE_USER'];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string Password
     *
     * @Groups({"user:write"})
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=127)
     *
     * @Groups({"user:read", "user:write"})
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=127)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=127)
     *
     * @Groups({"user:read", "user:write"})
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=127)
     */
    private $lastName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogPost", mappedBy="owner")
     *
     * @Assert\NotBlank()
     */
    private $blogPosts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserVerificationRequest", mappedBy="user", orphanRemoval=true)
     *
     * @Assert\NotBlank()
     */
    private $verificationRequests;

    public function __construct()
    {
        $this->blogPosts = new ArrayCollection();
        $this->verificationRequests = new ArrayCollection();
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
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): bool
    {
        if (!\in_array($role, $this->getRoles())) {
            $this->roles[] = $role;
        }

        return true;
    }

    public function removeRole(string $role): bool
    {
        $index = \array_search($role, $this->getRoles());
        if ($index === false) {
            return false;
        }

        unset($this->roles[$index]);

        return true;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection|BlogPost[]
     */
    public function getBlogPosts(): Collection
    {
        return $this->blogPosts;
    }

    public function addBlogPost(BlogPost $blogPost): self
    {
        if (!$this->blogPosts->contains($blogPost)) {
            $this->blogPosts[] = $blogPost;
            $blogPost->setOwner($this);
        }

        return $this;
    }

    public function removeBlogPost(BlogPost $blogPost): self
    {
        if ($this->blogPosts->contains($blogPost)) {
            $this->blogPosts->removeElement($blogPost);
            // set the owning side to null (unless already changed)
            if ($blogPost->getOwner() === $this) {
                $blogPost->setOwner(null);
            }
        }

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return Collection|UserVerificationRequest[]
     */
    public function getUserVerificationRequests(): Collection
    {
        return $this->verificationRequests;
    }

    public function addUserVerificationRequest(UserVerificationRequest $userVerificationRequest): self
    {
        if (!$this->verificationRequests->contains($userVerificationRequest)) {
            $this->verificationRequests[] = $userVerificationRequest;
            $userVerificationRequest->setUser($this);
        }

        return $this;
    }

    public function removeUserVerificationRequest(UserVerificationRequest $userVerificationRequest): self
    {
        if ($this->verificationRequests->contains($userVerificationRequest)) {
            $this->verificationRequests->removeElement($userVerificationRequest);
            // set the owning side to null (unless already changed)
            if ($userVerificationRequest->getUser() === $this) {
                $userVerificationRequest->setUser(null);
            }
        }

        return $this;
    }
}

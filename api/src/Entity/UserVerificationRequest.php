<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"user_verification_request:read"}},
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserVerificationRequestRepository")
 */
class UserVerificationRequest
{
    const STATUS_REQUESTED = 'requested';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=511)
     */
    private $IDImagePath;

    /**
     * @var File|null
     *
     * @Assert\NotNull()
     * @Vich\UploadableField(mapping="user_verification_request", fileNameProperty="IDImagePath")
     */
    private $IDImage;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userVerificationRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=63)
     */
    private $status = self::STATUS_REQUESTED;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIDImagePath(): ?string
    {
        return $this->IDImagePath;
    }

    public function setIDImagePath(string $IDImagePath): self
    {
        $this->IDImagePath = $IDImagePath;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}

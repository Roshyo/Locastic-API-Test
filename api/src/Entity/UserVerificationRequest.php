<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\AcceptUserVerficationRequestController;
use App\Controller\DeclineUserVerficationRequestController;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"user_verification_request:read"}},
 *     denormalizationContext={"groups"={"user_verification_request:write", "user_verification_request_decline:write"}},
 *     collectionOperations={
 *         "get"={
 *             "security_post_denormalize"="is_granted('ROLE_USER')",
 *             "denormalization_context"={"groups"={"user_verification_request:read"}},
 *         },
 *         "post"={
 *             "access_control"="is_granted('ROLE_USER') and is_granted('ROLE_BLOGGER') === false",
 *             "denormalization_context"={"groups"={"user_verification_request:write"}},
 *         },
 *     },
 *     itemOperations={
 *         "decline_request"={
 *             "access_control"="is_granted('ROLE_ADMIN')",
 *             "method"="PUT",
 *             "path"="/user_verification_requests/{id}/decline",
 *             "controller"=DeclineUserVerficationRequestController::class,
 *             "deserialize"=false,
 *             "denormalization_context"={"groups"={"user_verification_request_decline:write"}}
 *         },
 *         "accept_request"={
 *             "access_control"="is_granted('ROLE_ADMIN')",
 *             "method"="PUT",
 *             "path"="/user_verification_requests/{id}/accept",
 *             "controller"=AcceptUserVerficationRequestController::class,
 *             "deserialize"=false,
 *             "denormalization_context"={"groups"={}}
 *         },
 *         "get"={
 *             "access_control"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.getUser() == user)",
 *             "denormalization_context"={"groups"={"user_verification_request:read"}},
 *         },
 *         "put"={
 *             "access_control"="is_granted('ROLE_USER') and object.getUser() == user and object.getStatus() == 'requested'",
 *             "denormalization_context"={"groups"={"user_verification_request:write"}},
 *         },
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"status": "exact", "user": "exact"})
 * @ApiFilter(OrderFilter::class, properties={"createdAt": "ASC"})
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserVerificationRequestRepository")
 * @ORM\HasLifecycleCallbacks()
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
     *
     * @Groups({"user_verification_request:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=511, nullable=true)
     */
    private $IDImagePath;

    /**
     * @var File|null
     */
    private $IDImage;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Groups({"user_verification_request:read", "user_verification_request:write"})
     *
     * @Assert\Type(type="string")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="verificationRequests")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"user_verification_request:read"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=63)
     *
     * @Groups({"user_verification_request:read"})
     *
     * @Assert\Type(type="string")
     */
    private $status = self::STATUS_REQUESTED;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Groups({"user_verification_request:read"})
     *
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Groups({"user_verification_request:read"})
     *
     * @Assert\DateTime()
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Groups({"user_verification_request:read", "user_verification_request_decline:write"})
     *
     * @Assert\Type(type="string")
     */
    private $rejectionReason;

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

    public function getIDImage(): ?File
    {
        return $this->IDImage;
    }

    public function setIDImage(?File $IDImage): self
    {
        $this->IDImage = $IDImage;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function defineCreationDate(): void
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function defineModificationDate(): void
    {
        $this->setUpdatedAt(new \DateTime());
    }

    public function getRejectionReason(): ?string
    {
        return $this->rejectionReason;
    }

    public function setRejectionReason(?string $rejectionReason): self
    {
        $this->rejectionReason = $rejectionReason;

        return $this;
    }
}

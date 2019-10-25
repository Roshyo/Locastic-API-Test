<?php

namespace spec\App\Entity;

use App\Entity\User;
use App\Entity\UserVerificationRequest;
use PhpSpec\ObjectBehavior;

class UserVerificationRequestSpec extends ObjectBehavior
{
    public function it_is_initialisable()
    {
        $this->shouldBeAnInstanceOf(UserVerificationRequest::class);
    }

    public function it_has_nullable_id()
    {
        $this->getId()->shouldReturn(null);
    }

    public function it_has_comment()
    {
        $this->setComment('Test comment');
        $this->getComment()->shouldReturn('Test comment');
    }

    public function comment_is_nullable()
    {
        $this->setComment(null);
        $this->getComment()->shouldBeNull();
    }

    public function it_has_user(User $user)
    {
        $this->setUser($user);
        $this->getUser()->shouldReturn($user);
    }

    public function it_has_status()
    {
        $this->setStatus(UserVerificationRequest::STATUS_ACCEPTED);
        $this->getStatus()->shouldReturn(UserVerificationRequest::STATUS_ACCEPTED);
    }

    public function it_has_requested_status_by_default()
    {
        $this->getStatus()->shouldReturn(UserVerificationRequest::STATUS_REQUESTED);
    }
}

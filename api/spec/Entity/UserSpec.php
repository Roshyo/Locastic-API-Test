<?php

namespace spec\App\Entity;

use App\Entity\User;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\User\UserInterface;

class UserSpec extends ObjectBehavior
{
    public function it_is_initialisable()
    {
        $this->shouldBeAnInstanceOf(User::class);
    }

    public function it_implements_user_interface()
    {
        $this->shouldImplement(UserInterface::class);
    }

    public function it_has_id()
    {
        $this->getId()->shouldReturn(null);
    }

    public function it_has_email()
    {
        $this->setEmail('testEmail@example.com');
        $this->getEmail()->shouldReturn('testEmail@example.com');
    }

    public function it_has_roles()
    {
        $this->setRoles(['ROLE_DUMMY']);
        $this->getRoles()->shouldReturn(['ROLE_DUMMY']);
    }

    public function it_has_role_user_by_default()
    {
        $this->getRoles()->shouldReturn(['ROLE_USER']);
    }

    public function it_has_plain_password()
    {
        $this->setPlainPassword('testPasswordSecured!');
        $this->getPlainPassword()->shouldReturn('testPasswordSecured!');
    }

    public function it_reset_password()
    {
        $this->setPlainPassword('Test');
        $this->eraseCredentials();
        $this->getPlainPassword()->shouldReturn(null);
    }

    public function it_has_first_name()
    {
        $this->setFirstName('First name');
        $this->getFirstName()->shouldReturn('First name');
    }

    public function it_has_last_name()
    {
        $this->setLastName('Last name');
        $this->getLastName()->shouldReturn('Last name');
    }
}

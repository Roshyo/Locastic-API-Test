<?php

namespace spec\App\Entity;

use App\Entity\BlogPost;
use PhpSpec\ObjectBehavior;

class BlogPostSpec extends ObjectBehavior
{
    public function it_is_initialisable()
    {
        $this->shouldBeAnInstanceOf(BlogPost::class);
    }

    public function it_has_title()
    {
        $this->setTitle('SuperTitle');
        $this->getTitle()->shouldReturn('SuperTitle');
    }

    public function it_has_content()
    {
        $this->setContent('My awesome content');
        $this->getContent()->shouldReturn('My awesome content');
    }
}

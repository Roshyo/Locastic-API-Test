<?php

namespace App\Event\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\UserVerificationRequest;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class UserVerificationRequestSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],];
    }

    public function sendMail(ViewEvent $event): void
    {
        $userVerificationRequest = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$userVerificationRequest instanceof UserVerificationRequest || Request::METHOD_PUT !== $method) {
            return;
        }

        if ($userVerificationRequest->getStatus() !== UserVerificationRequest::STATUS_ACCEPTED) {
            return;
        }

        $message = (new \Swift_Message('Your request has been aproved'))->setFrom('system@example.com')->setTo($userVerificationRequest->getUser()->getEmail())->setBody(sprintf('Your verification request #%d has been approved. You can now post blog posts', $userVerificationRequest->getId()));

        $this->mailer->send($message);
    }
}

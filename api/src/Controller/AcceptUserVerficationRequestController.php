<?php

namespace App\Controller;

use App\Entity\UserVerificationRequest;

final class AcceptUserVerficationRequestController
{
    public function __invoke(UserVerificationRequest $data): UserVerificationRequest
    {
        $data->setStatus(UserVerificationRequest::STATUS_ACCEPTED);
        $user = $data->getUser();
        $user->addRole('ROLE_BLOGGER');

        return $data;
    }
}

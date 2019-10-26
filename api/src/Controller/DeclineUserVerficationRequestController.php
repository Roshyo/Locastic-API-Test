<?php

namespace App\Controller;

use App\Entity\UserVerificationRequest;

final class DeclineUserVerficationRequestController
{
    public function __invoke(UserVerificationRequest $data): UserVerificationRequest
    {
        $data->setStatus(UserVerificationRequest::STATUS_DECLINED);

        return $data;
    }
}

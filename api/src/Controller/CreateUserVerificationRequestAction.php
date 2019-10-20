<?php

namespace App\Controller;

use App\Entity\UserVerificationRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CreateUserVerificationRequestAction
{
    public function __invoke(Request $request): UserVerificationRequest
    {
        $uploadedFile = $request->files->get('IDImage');
        if (!$uploadedFile) {
            throw new BadRequestHttpException(\sprintf('"%s" is required', "IDImage"));
        }

        $userVerificationRequest = new UserVerificationRequest();
        $userVerificationRequest->setIDImage($uploadedFile);

        return $userVerificationRequest;
    }
}

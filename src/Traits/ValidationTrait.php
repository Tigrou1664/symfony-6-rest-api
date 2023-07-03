<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;

trait ValidationTrait
{
    public function handleValidationErrors($violations): JsonResponse
    {
        $errors = [];
        foreach ($violations as $violation) {
            $this->addViolation($errors, $violation);
        }

        return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }

    public function addViolation(&$errors, ConstraintViolationInterface $violation)
    {
        $field = $violation->getPropertyPath();
        $message = $violation->getMessage();
        foreach ($errors as $error) {
            if ($error['field'] == $field) {
                $error['messages'][] = $message;

                return;
            }
        }
        $errors[] = [
            'field' => $field,
            'messages' => [$message],
        ];
    }
}

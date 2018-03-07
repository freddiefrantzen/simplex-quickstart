<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Shared\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

final class BadRequestException extends \RuntimeException
{
    /** @var ConstraintViolationListInterface */
    private $violations;

    public function setViolations(ConstraintViolationListInterface $violations): void
    {
        $this->violations = $violations;
    }

    public function getViolations(): ?ConstraintViolationListInterface
    {
        return $this->violations;
    }
}

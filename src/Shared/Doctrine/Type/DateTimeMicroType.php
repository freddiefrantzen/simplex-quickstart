<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Shared\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

/**
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
final class DateTimeMicroType extends Type
{
    public const NAME = 'datetime_micro';

    private const FORMAT = 'Y-m-d H:i:s.u';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return "DATETIME(6) COMMENT '(DC2Type:" . self::NAME  . ")'";
    }

    /** @param \DateTime|null $value */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return ($value !== null) ? $value->format(self::FORMAT) : null;
    }

    /** @param \DateTime|null|string $value */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?\DateTime
    {
        if ($value === null || $value instanceof \DateTime) {
            return $value;
        }

        $value = \DateTime::createFromFormat(self::FORMAT, $value);

        if (!$value) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), self::FORMAT);
        }

        return $value;
    }
}

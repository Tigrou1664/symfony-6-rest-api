<?php

namespace App\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * @codeCoverageIgnore
 */
abstract class BaseEnumType extends Type
{
    protected string $name;
    protected array $values = [];

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $valuesStrings = array_map(function ($val) { return "'".$val."'"; }, $this->values);

        return 'ENUM('.implode(', ', $valuesStrings).')';
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, $this->values)) {
            throw new \InvalidArgumentException("Invalid '".$this->name."' value.");
        }

        return $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
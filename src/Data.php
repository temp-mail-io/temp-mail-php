<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp;

use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;

abstract class Data
{
    private const SCALAR_TYPES = [
        'string',
        'int',
        'float',
        'bool',
    ];

    private array $schema = [];

    /**
     * @throws \ReflectionException
     */
    public static function create(): static
    {
        return new static();
    }

    /**
     * @throws \ReflectionException
     */
    final public function __construct()
    {
        $this->schema = $this->getSchema();
    }

    public function toArray(): array
    {
        $data = [];

        foreach ($this->schema as $key => $type) {
            $snakeCaseKey = $this->toSnakeCase($key);

            if (class_exists($type)) {
                /** @phpstan-ignore property.dynamicName */
                $data[$snakeCaseKey] = $this->$key->toArray();

                continue;
            }

            if (str_contains($type, '[]')) {
                /** @phpstan-ignore property.dynamicName */
                $data[$snakeCaseKey] = array_map(fn ($item) => $item->toArray(), $this->$key);

                continue;
            }

            /** @phpstan-ignore property.dynamicName */
            $data[$snakeCaseKey] = $this->$key;
        }

        return $data;
    }

    public function fromArray(array $incomingData): static
    {
        foreach ($this->schema as $key => $type) {
            $snakeCaseKey = $this->toSnakeCase($key);

            if (class_exists($type) && isset($incomingData[$snakeCaseKey])) {
                /** @phpstan-ignore property.dynamicName */
                $this->$key = (new $type())->fromArray($incomingData[$snakeCaseKey]);

                continue;
            }

            if (str_contains($type, '[]') && isset($incomingData[$snakeCaseKey])) {
                $className = str_replace('[]', '', $type);
                $className = ltrim($className, '\\');
                /** @phpstan-ignore property.dynamicName */
                $this->$key = array_map(fn ($item) => (new $className())->fromArray($item), $incomingData[$snakeCaseKey]);

                continue;
            }

            if (isset($incomingData[$snakeCaseKey])) {
                $value = $incomingData[$snakeCaseKey];

                if (in_array($type, self::SCALAR_TYPES, true)) {
                    settype($value, $type);
                }

                /** @phpstan-ignore property.dynamicName */
                $this->$key = $value;
            }
        }

        return $this;
    }

    /**
     * @throws \ReflectionException
     */
    private function getSchema(): array
    {
        $reflectionClass = new ReflectionClass(static::class);
        $schema = [];

        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->getType()->getName() === 'array') {
                $arrayType = $this->getPropertyType($reflectionClass, $property->getName());

                if ($arrayType === null) {
                    $schema[$property->getName()] = 'array';

                    continue;
                }

                $schema[$property->getName()] = $arrayType;

                continue;
            }

            $schema[$property->getName()] = $property->getType()->getName();
        }

        return $schema;
    }

    private function toSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/[A-Z]/', '_$0', lcfirst($input)));
    }

    private function getPropertyType(ReflectionClass $reflectionClass, string $propertyName): ?string
    {
        $docComment = $reflectionClass->getProperty($propertyName)->getDocComment();

        if (!$docComment) {
            return null;
        }

        $docBlockFactory = DocBlockFactory::createInstance();
        $docBlock = $docBlockFactory->create($docComment);
        $varTags = $docBlock->getTagsByName('var');

        if (isset($varTags[0])) {
            return (string)$varTags[0]->getType();
        }

        return null;
    }
}

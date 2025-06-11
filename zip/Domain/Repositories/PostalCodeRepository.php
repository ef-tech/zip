<?php

namespace Zip\Domain\Repositories;

class PostalCodeRepository
{
    public static function create(string $type, array $args = [])
    {
        $class = 'Zip\\Infrastructure\\Repositories\\'.ucfirst($type).'PostalCodeRepository';
        if (! class_exists($class)) {
            throw new \InvalidArgumentException("Repository class {$class} does not exist.");
        }

        return new $class(...$args);
    }
}

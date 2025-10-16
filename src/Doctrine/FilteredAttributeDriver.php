<?php
// src/Doctrine/FilteredAttributeDriver.php

namespace App\Doctrine;

use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use App\Module\Core\Entity\EntityInterface;

class FilteredAttributeDriver extends AttributeDriver
{
    public function getAllClassNames(): array
    {
        $classes = parent::getAllClassNames();

        return array_filter($classes, function ($class) {
            $implements = class_implements($class);
            return $implements && in_array(EntityInterface::class, $implements);
        });
    }
}

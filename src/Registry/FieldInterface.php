<?php

namespace Tug\SeoBundle\Registry;

use Tug\SeoBundle\Field\FieldInterface as Parameter;

interface FieldInterface
{
    public function set(Parameter $parameter);

    public function get(array $namespace): Parameter;

    /**
     * @return Parameter[]
     */
    public function getAll(): array;
}

<?php

namespace Tug\SeoBundle\Registry;

use Tug\SeoBundle\Field\FieldData;

interface ContextInterface
{
    public function setFields(array $fields, bool $merge = true);

    public function setDefaultFields(array $fields, bool $merge = true);

    public function setRouteFields(array $fields, bool $merge = true);

    public function setParameters(array $parameters, bool $merge = true);

    public function setGlobalParameters(array $parameters, bool $merge = true);

    public function setDefaultParameters(array $parameters, bool $merge = true);

    public function setRouteParameters(array $parameters, bool $merge = true);

    public function setOptions(array $options, bool $merge = true);

    public function setDefaultOptions(array $options, bool $merge = true);

    public function setRouteOptions(array $options, bool $merge = true);

    public function setHierarchy(array $hierarchy);

    public function getFieldData(string $routeName, array $namespace): ?FieldData;
}

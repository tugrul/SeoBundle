<?php

namespace Tug\SeoBundle\Tests\Stub\JsonLd;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

#[JsonLd\Type('Zoka')]
#[JsonLd\Property('mokoko', filters: ['pick_value' => ['*value' => 'field1']])]
#[JsonLd\Property('chain', filters: ['pick_params' => ['*f1' => 'field1', '*f2' => 'field3'], 'array_flip'])]
#[JsonLd\Property('skipNull', filters: ['pick_value' => ['*value' => 'field1'], 'test', 'array_flip'])]
class FilterModel
{
    public string $field1 = 'abc';

    #[JsonLd\Property('myField2', filters: ['pick_params' => ['*n1' => '[nested1]', '*n2' => '[nested2]']])]
    public array $field2 = ['nested1' => 'value1', 'nested2' => 'value2'];

    public int $field3 = 365;
}

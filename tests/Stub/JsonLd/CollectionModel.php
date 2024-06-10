<?php

namespace Tug\SeoBundle\Tests\Stub\JsonLd;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

#[JsonLd\Type('Test')]
class CollectionModel
{
    #[JsonLd\Property('zo', filters: ['append_str' => ['suffix' => 'a']])]
    public \ArrayIterator $field1;

    public function __construct()
    {
        $this->field1 = new \ArrayIterator([1, 2, 3]);
    }
}

<?php

namespace Tug\SeoBundle\Tests\Stub\JsonLd;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

#[JsonLd\Type('LevelModel')]
#[JsonLd\Property('gen1', filters: 'test')]
#[JsonLd\Property('gen2', level: 1, filters: 'test')]
#[JsonLd\Property('gen3', level: 2, filters: 'test')]
#[JsonLd\Property('gen4', level: [2], filters: 'test')]
#[JsonLd\Property('gen5', level: [3, 4], filters: 'test')]
class ModelLevel
{
    #[JsonLd\Property('aaa', level: 1)]
    public string $field1 = 'abc123';

    #[JsonLd\Property('bbb', level: 2)]
    protected string $field2 = 'def456';

    #[JsonLd\Property('ddd', level: [2])]
    public string $field3 = 'mmm';

    #[JsonLd\Property('eee', level: [3, 4])]
    public string $field4 = 'nnn';

    public function getField2(): string
    {
        return $this->field2;
    }

    #[JsonLd\Property('ccc', level: 3)]
    public function bagaBogo(): string
    {
        return 'how is this?';
    }
}
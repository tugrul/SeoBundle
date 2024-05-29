<?php

namespace Tug\SeoBundle\Tests\Stub\JsonLd;

use Tug\SeoBundle\Attribute\JsonLd;

#[JsonLd\Type('LevelModel')]
class DummyModelLevel
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
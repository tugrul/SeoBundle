<?php

namespace Tug\SeoBundle\Tests\Stub;

use Tug\SeoBundle\Model\ModelInterface;

class DummyModel implements ModelInterface
{
    protected string $something;

    public static function getHandleName(): string
    {
        return 'dummy_model';
    }

    /**
     * @return string
     */
    public function getSomething(): string
    {
        return $this->something;
    }

    /**
     * @param string $something
     * @return DummyModel
     */
    public function setSomething(string $something): DummyModel
    {
        $this->something = $something;
        return $this;
    }
}

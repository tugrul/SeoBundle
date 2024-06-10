<?php

namespace Tug\SeoBundle\Tests\Stub;

use Tug\SeoBundle\Model\ModelInterface;

class Model implements ModelInterface
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
     * @return Model
     */
    public function setSomething(string $something): Model
    {
        $this->something = $something;
        return $this;
    }
}

<?php

namespace Tug\SeoBundle\Tests\Stub;

use Tug\SeoBundle\Model\ModelInterface;
use Tug\SeoBundle\Renderer\RendererInterface;

class DummyFaultyRenderer implements RendererInterface
{
    protected string $model;

    public function __construct(string $model = self::class)
    {
        $this->model = $model;
    }

    public function render(ModelInterface $model): string
    {
        return 'something';
    }

    public function getModel(): string
    {
        return $this->model;
    }
}

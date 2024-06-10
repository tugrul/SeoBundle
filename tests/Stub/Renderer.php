<?php

namespace Tug\SeoBundle\Tests\Stub;

use Tug\SeoBundle\Model\{ModelInterface, Title};
use Tug\SeoBundle\Renderer\RendererInterface;

class Renderer implements RendererInterface
{
    public function render(ModelInterface $model): string
    {
        /**
         * @type $model Title
         */
        return '<!-- ' . $model->getValue() . ' -->';
    }

    public function getModel(): string
    {
        return Title::class;
    }
}

<?php

namespace Tug\SeoBundle\Renderer;

use Tug\SeoBundle\Model\{ModelInterface, Link as LinkModel};

class Link implements RendererInterface
{
    /**
     * @param ModelInterface $model
     * @return string
     */
    public function render(ModelInterface $model): string
    {
        /**
         * @type LinkModel $model
         */

        $href = $model->getHref();
        $rel = $model->getRel();
        $type = $model->getType();

        return '<link rel="' . htmlspecialchars($rel) . '" href="' . htmlspecialchars($href) . '"' .
            (!empty($type) ? ' type="' . htmlspecialchars($type) . '"' : '') . ' />';
    }

    public function getModel(): string
    {
        return LinkModel::class;
    }
}

<?php

namespace Tug\SeoBundle\Renderer;

use Tug\SeoBundle\Model\{ModelInterface, Meta as MetaModel};

class Meta implements RendererInterface
{
    /**
     * @param ModelInterface $model
     * @return string
     */
    public function render(ModelInterface $model): string
    {
        /**
         * @type MetaModel $model
         */

        $parts = [];

        $name = $model->getName();
        $property = $model->getProperty();
        $content = $model->getContent();


        if (!empty($name)) {
            $parts[] = 'name="' . htmlspecialchars($name) . '"';
        }

        if (!empty($property)) {
            $parts[] = 'property="' . htmlspecialchars($property) . '"';
        }

        if (!empty($content)) {
            $parts[] = 'content="' . htmlspecialchars($content) . '"';
        }

        if (empty($parts)) {
            return '';
        }

        return '<meta ' . implode(' ', $parts) . ' />';
    }

    public function getModel(): string
    {
        return MetaModel::class;
    }
}

<?php

namespace Tug\SeoBundle\Renderer;

use Tug\SeoBundle\Model\{ModelInterface, Title as TitleModel};

class Title implements RendererInterface
{
    /**
     * @param ModelInterface $model
     * @return string
     */
    public function render(ModelInterface $model): string
    {
        /**
         * @type TitleModel $model
         */

        $value = $model->getValue();

        return empty($value) ? '' : ('<title>' . htmlspecialchars($value) . '</title>');
    }

    public function getModel(): string
    {
        return TitleModel::class;
    }
}

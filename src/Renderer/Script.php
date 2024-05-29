<?php

namespace Tug\SeoBundle\Renderer;

use Tug\SeoBundle\Model\{ModelInterface, Script as ScriptModel};

class Script implements RendererInterface
{
    public function render(ModelInterface $model): string
    {
        /**
         * @type ScriptModel $model
         */

        $type = $model->getType();
        $source = $model->getSource();
        $nonce = $model->getNonce();

        $parts = [];

        if (!empty($type)) {
            $parts[] = ' type="' . htmlspecialchars($type) . '"';
        }

        if (!empty($source)) {
            $parts[] = ' src="' . htmlspecialchars($source) . '"';
        }

        if (!empty($nonce)) {
            $parts[] = ' nonce="' . htmlspecialchars($nonce) . '"';
        }

        return '<script' . implode('', $parts) . '>' . ($model->getBody() ?? '') . '</script>';
    }

    public function getModel(): string
    {
        return ScriptModel::class;
    }
}

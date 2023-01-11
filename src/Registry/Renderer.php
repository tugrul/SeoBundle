<?php

namespace Tug\SeoBundle\Registry;

use Tug\SeoBundle\Model\ModelInterface;
use Tug\SeoBundle\Renderer\RendererInterface;
use Tug\SeoBundle\Registry\RendererInterface as RegistryRendererInterface;

class Renderer implements RegistryRendererInterface
{
    /**
     * @var RendererInterface[] $renderers
     */
    protected array $renderers = [];

    public function set(RendererInterface $renderer): self
    {
        $model = $renderer->getModel();

        if (!is_subclass_of($model, ModelInterface::class)) {
            throw new \RuntimeException('Model should implement the ModelInterface');
        }

        $handleName = $model::getHandleName();

        if (empty($handleName)) {
            throw new \RuntimeException('Invalid handleName');
        }

        $this->renderers[$handleName] = $renderer;

        return $this;
    }


    public function render(ModelInterface $model): string
    {
        $handleName = $model::getHandleName();

        if (!isset($this->renderers[$handleName])) {
            throw new \RuntimeException('There is no renderer to render this model');
        }

        return $this->renderers[$handleName]->render($model);
    }
}

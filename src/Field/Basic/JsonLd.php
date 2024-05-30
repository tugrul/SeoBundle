<?php

namespace Tug\SeoBundle\Field\Basic;

use Tug\SeoBundle\Field\FieldData;
use Tug\SeoBundle\Field\FieldInterface;
use Tug\SeoBundle\Field\SerializableFieldInterface;
use Tug\SeoBundle\Field\SerializableFieldTrait;
use Tug\SeoBundle\Model\Script as ScriptModel;

class JsonLd implements FieldInterface, SerializableFieldInterface
{
    use SerializableFieldTrait;

    /**
     * @inheritDoc
     */
    public function getNamespace(): array
    {
        return ['jsonLd'];
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $script = new ScriptModel();

        $options = $fieldData->getOptions();

        if (!empty($options['nonce'])) {
            $script->setNonce($options['nonce']);
        }

        $defaultContext = $options['defaultContext'] ?? null;

        $script->setType($options['mimeType'] ?? 'application/ld+json');

        $content = $fieldData->getContent();

        if (isset($options['graph']) && is_string($options['graph'])) {
            $content = [
                '@context' => $options['graph'],
                '@graph' => $content
            ];
        }

        $script->setBody($this->serializer->serialize($content, 'ld+json',
            ['jsonLd' => ['defaultContext' => $defaultContext, 'parameters' => $fieldData->getParameters()]]));

        yield $script;
    }


}
<?php

namespace Tug\SeoBundle\Tests\Stub;

use Tug\SeoBundle\Field\{FieldData, FieldInterface};
use Tug\SeoBundle\Model\Meta;

class DummyField implements FieldInterface
{
    public function getNamespace(): array
    {
        return ['dummy', 'field'];
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $params = $fieldData->getParameters();
        $params = array_map(fn(string $key, string $value) => $key . '=' .$value,
            array_keys($params), $params);

        $meta = new Meta();
        $meta->setName('test1');
        $meta->setContent($fieldData->getContent() . ' # ' . implode(' | ', $params));
        yield $meta;

        $fieldData = $fieldData->getParent();

        $options = $fieldData->getOptions();
        $options = array_map(fn(string $key, string $value) => $key . '=' .$value,
            array_keys($options), $options);

        $content = $fieldData->getContent();

        $meta = new Meta();
        $meta->setProperty('test2');
        $meta->setContent($content[1] . ' # ' . implode(' | ', $options));
        yield $meta;
    }
}

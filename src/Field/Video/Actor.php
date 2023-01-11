<?php

namespace Tug\SeoBundle\Field\Video;

use Tug\SeoBundle\Field\FieldData;

class Actor extends Base
{
    function getName(): string
    {
        return 'actor';
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $content = $fieldData->getContent();

        if (is_string($content)) {
            yield $this->getTag()->setContent($content);
            return;
        }

        foreach ($content as $item) {
            if (is_string($item)) {
                yield $this->getTag()->setContent($item);
                continue;
            }

            if (!isset($item['url'])) {
                continue;
            }

            yield $this->getTag()->setContent($item['url']);

            if (!isset($item['role'])) {
                continue;
            }

            if (is_string($item['role'])) {
                yield $this->getTag('role')->setContent($item['role']);
                continue;
            }

            foreach ($item['role'] as $role) {
                yield $this->getTag('role')->setContent($role);
            }
        }
    }
}

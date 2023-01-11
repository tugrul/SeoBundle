<?php

namespace Tug\SeoBundle\Tests\Stub;

use Symfony\Contracts\Translation\TranslatorInterface;

class DummyTranslatorService implements TranslatorInterface
{
    /**
     * @inheritDoc
     */
    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        $parts = [];

        foreach ($parameters as $key => $value) {
            $parts[] = $key . '=' . $value;
        }

        return strrev($id) . ' | ' . implode('&', $parts) . ($domain ? ' | ' . $domain : '');
    }

    /**
     * @inheritDoc
     */
    public function getLocale(): string
    {
        return 'tr';
    }
}

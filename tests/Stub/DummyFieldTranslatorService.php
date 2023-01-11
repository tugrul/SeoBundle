<?php

namespace Tug\SeoBundle\Tests\Stub;

use Symfony\Contracts\Translation\TranslatorInterface;

class DummyFieldTranslatorService implements TranslatorInterface
{
    /**
     * @inheritDoc
     */
    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        $keys = array_keys($parameters);
        $parameters = array_map(fn(string $key, string $value) => $key . '=' . $value, $keys, $parameters);

        return $id . ' # ' . implode(' | ', $parameters);
    }

    /**
     * @inheritDoc
     */
    public function getLocale(): string
    {
        return 'en';
    }
}

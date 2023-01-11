<?php

namespace Tug\SeoBundle\Translate;

interface TranslatorInterface
{
    public function setType(TranslationType $type);

    public function setDomain(?string $domain = null);

    public function setFormatTemplate(array $formatTemplate);

    public function translate(string $content, array $parameters = []): string;
}

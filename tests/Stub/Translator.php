<?php

namespace Tug\SeoBundle\Tests\Stub;

use Tug\SeoBundle\Translate\{TranslationType, TranslatorInterface};

class Translator implements TranslatorInterface
{
    protected TranslationType $type;

    protected ?string $domain = null;

    protected array $formatTemplate = [];

    public function setType(TranslationType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setDomain(?string $domain = null): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function setFormatTemplate(array $formatTemplate): self
    {
        $this->formatTemplate = $formatTemplate;

        return $this;
    }

    public function translate(string $content, array $parameters = []): string
    {
        $template = array_values($this->formatTemplate);

        $formatOpen  = current($template);
        $formatClose = end($template);

        $params = [];

        foreach ($parameters as $key => $value) {
            $params[] = $formatOpen . $key . $formatClose . '=' .$value;
        }

        return $content . ', ' . ($this->domain ?? '(empty)') . ', ' .
            $this->type->value . ', ' . implode(' | ', $params);
    }
}

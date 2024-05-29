<?php

namespace Tug\SeoBundle\Translate;

use Symfony\Contracts\Translation\TranslatorInterface as TranslatorService;

class Translator implements TranslatorInterface
{
    protected TranslatorService $translator;

    protected TranslationType $type;

    protected ?string $domain = null;

    protected array $formatTemplate = ['{', '}'];

    public function __construct(TranslatorService $translator)
    {
        $this->translator = $translator;
    }

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
        $parameters = array_filter($parameters, fn($value) => is_scalar($value));

        if ($this->type === TranslationType::Icu) {
            return $this->translator->trans($content, $parameters, $this->domain);
        }

        $template = array_values($this->formatTemplate);

        $formatOpen  = current($template);
        $formatClose = end($template);

        $formatParameters = [];

        foreach ($parameters as $key => $value) {
            $formatParameters[$formatOpen . $key . $formatClose] = $value;
        }

        if ($this->type === TranslationType::None) {
            return strtr($content, $formatParameters);
        }

        return $this->translator->trans($content, $formatParameters, $this->domain);
    }
}

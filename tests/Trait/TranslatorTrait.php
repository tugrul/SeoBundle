<?php

namespace Tug\SeoBundle\Tests\Trait;

use Tug\SeoBundle\Translate\{TranslationType, Translator, TranslatorInterface};

use Tug\SeoBundle\Tests\Stub\FieldTranslatorService;
trait TranslatorTrait
{
    protected ?TranslatorInterface $translator = null;

    protected function getTranslator(): TranslatorInterface
    {
        if (!is_null($this->translator)) {
            return $this->translator;
        }

        $this->translator = $translator = new Translator(new FieldTranslatorService());

        $translator->setType(TranslationType::Icu);
        $translator->setDomain('tug_seo');

        return $translator;
    }
}
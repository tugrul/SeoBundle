<?php

namespace Tug\SeoBundle\Tests\Field;

use PHPUnit\Framework\TestCase;
use Tug\SeoBundle\Translate\{TranslationType, Translator, TranslatorInterface};

use Tug\SeoBundle\Tests\Stub\DummyFieldTranslatorService;

abstract class AbstractFieldTest extends TestCase
{
    protected ?TranslatorInterface $translator = null;

    protected function getTranslator(): TranslatorInterface
    {
        if (!is_null($this->translator)) {
            return $this->translator;
        }

        $this->translator = $translator = new Translator(new DummyFieldTranslatorService());

        $translator->setType(TranslationType::Icu);
        $translator->setDomain('tug_seo');

        return $translator;
    }
}

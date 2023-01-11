<?php

namespace Tug\SeoBundle\Field;

use Tug\SeoBundle\Translate\TranslatorInterface;

trait TranslatableFieldTrait
{
    protected TranslatorInterface $translator;

    public function setTranslator(TranslatorInterface $translator): self
    {
        $this->translator = $translator;

        return $this;
    }
}

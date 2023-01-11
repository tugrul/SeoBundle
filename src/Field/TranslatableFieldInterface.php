<?php

namespace Tug\SeoBundle\Field;

use Tug\SeoBundle\Translate\TranslatorInterface;

interface TranslatableFieldInterface
{
    public function setTranslator(TranslatorInterface $translator);
}

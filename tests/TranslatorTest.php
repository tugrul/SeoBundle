<?php

namespace Tug\SeoBundle\Tests;

use PHPUnit\Framework\TestCase;
use Tug\SeoBundle\Translate\{TranslationType, Translator};
use Tug\SeoBundle\Tests\Stub\{DummyTranslator, DummyTranslatorService};

class TranslatorTest extends TestCase
{
    public function testTranslatorInterface(): void
    {
        $translator = new DummyTranslator();

        $translator->setType(TranslationType::Icu);
        $translator->setFormatTemplate(['[', ']']);

        $this->assertEquals('some info, (empty), icu, [param1]=value1 | [param2]=value2',
            $translator->translate('some info', ['param1' => 'value1', 'param2' => 'value2']));

        $translator->setDomain('brag');

        $this->assertEquals('bimbimbo, brag, icu, [zimba]=val1 | [zomba]=val2',
            $translator->translate('bimbimbo', ['zimba' => 'val1', 'zomba' => 'val2']));
    }

    public function testTranslator(): void
    {
        $translator = new Translator(new DummyTranslatorService());

        $translator->setType(TranslationType::Icu);
        $translator->setFormatTemplate(['{', '}']);


        $this->assertEquals('abmoz | param1=value1&param2=value2',
            $translator->translate('zomba', [
            'param1' => 'value1',
            'param2' => 'value2'
        ]));

        $translator->setDomain('my_seo');

        $this->assertEquals('abmoz | param1=value1&param2=value2 | my_seo',
            $translator->translate('zomba', [
            'param1' => 'value1',
            'param2' => 'value2'
        ]));

        $translator->setType(TranslationType::Default);

        $this->assertEquals('abmoz | {param1}=value1&{param2}=value2 | my_seo',
            $translator->translate('zomba', [
                'param1' => 'value1',
                'param2' => 'value2'
            ]));

        $translator->setType(TranslationType::None);

        $this->assertEquals('zomba -value1- bambam [value2] 333',
            $translator->translate('zomba -{param1}- bambam [{param2}] 333', [
                'param1' => 'value1',
                'param2' => 'value2'
            ]));
    }
}

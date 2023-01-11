<?php

namespace Tug\SeoBundle\Tests\Registry;

use PHPUnit\Framework\TestCase;
use Tug\SeoBundle\Registry\Context;

class ContextTest extends TestCase
{
    protected Context $context;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->context = new Context();
    }

    public function testFields(): void
    {
        $target = $this->context;

        $this->assertCount(0, $target->getFields());

        $target->setFields(['fo' => ['fa' => 'be']]);

        $this->assertEquals(['fo' => ['fa' => 'be']], $target->getFields());

        $target->setFields(['fo' => ['fi' => 'ka'], 're' => 'ma']);

        $this->assertEquals(['fo' => ['fi' => 'ka', 'fa' => 'be'], 're' => 'ma'], $target->getFields());

        $target->setFields(['mi' => 'ka'], false);

        $this->assertEquals(['mi' => 'ka'], $target->getFields());
    }

    public function testDefaultFields(): void
    {
        $target = $this->context;

        $this->assertCount(0, $target->getDefaultFields());

        $target->setDefaultFields(['fod' => ['fad' => 'be']]);

        $this->assertEquals(['fod' => ['fad' => 'be']], $target->getDefaultFields());

        $target->setDefaultFields(['fod' => ['fid' => 'ka'], 'red' => 'ma']);

        $this->assertEquals(['fod' => ['fid' => 'ka', 'fad' => 'be'], 'red' => 'ma'], $target->getDefaultFields());

        $target->setDefaultFields(['mid' => 'ka'], false);

        $this->assertEquals(['mid' => 'ka'], $target->getDefaultFields());
    }

    public function testRouteFields(): void
    {
        $target = $this->context;

        $this->assertCount(0, $target->getRouteFields());

        $target->setRouteFields([
            'index' => ['fo' => ['fa' => 're']],
            'user' => ['fo' => ['fa' => 'mi']]
        ]);

        $this->assertEquals([
            'index' => ['fo' => ['fa' => 're']],
            'user' => ['fo' => ['fa' => 'mi']]
        ], $target->getRouteFields());

        $target->setRouteFields([
            'index' => ['fo' => ['fi' => 'ma']],
            'info' => ['mi' => ['fo' => 're']]
        ]);

        $this->assertEquals([
            'index' => ['fo' => ['fa' => 're', 'fi' => 'ma']],
            'user' => ['fo' => ['fa' => 'mi']],
            'info' => ['mi' => ['fo' => 're']]
        ], $target->getRouteFields());

        $target->setRouteFields([
            'zubizamba' => ['re' => ['za' => 'ma']]
        ], false);

        $this->assertEquals([
            'zubizamba' => ['re' => ['za' => 'ma']]
        ], $target->getRouteFields());
    }

    public function testParameters(): void
    {
        $target = $this->context;

        $this->assertCount(0, $target->getParameters());

        $target->setParameters([
            'fo' => 'fi',
            'fa' => ['re' => 'la', 'no' => 'ka']
        ]);

        $this->assertEquals([
            'fo' => 'fi',
            'fa' => ['re' => 'la', 'no' => 'ka']
        ], $target->getParameters());

        $target->setParameters([
            'fo' => 'me',
            'pa' => ['re' => 'ka']
        ]);

        $this->assertEquals([
            'fo' => 'me',
            'fa' => ['re' => 'la', 'no' => 'ka'],
            'pa' => ['re' => 'ka']
        ], $target->getParameters());

        $target->setParameters(['me' => 'la'], false);

        $this->assertEquals(['me' => 'la'], $target->getParameters());
    }

    public function testGlobalParameters(): void
    {
        $target = $this->context;

        $this->assertCount(0, $target->getGlobalParameters());

        $target->setGlobalParameters([
            'fog' => 'fi',
            'fag' => ['re' => 'la', 'no' => 'ka']
        ]);

        $this->assertEquals([
            'fog' => 'fi',
            'fag' => ['re' => 'la', 'no' => 'ka']
        ], $target->getGlobalParameters());

        $target->setGlobalParameters([
            'fog' => 'me',
            'pag' => ['re' => 'ka']
        ]);

        $this->assertEquals([
            'fog' => 'me',
            'fag' => ['re' => 'la', 'no' => 'ka'],
            'pag' => ['re' => 'ka']
        ], $target->getGlobalParameters());

        $target->setGlobalParameters(['meg' => 'la'], false);

        $this->assertEquals(['meg' => 'la'], $target->getGlobalParameters());
    }

    public function testRouteParameters(): void
    {
        $target = $this->context;

        $this->assertCount(0, $target->getRouteParameters());

        $target->setRouteParameters([
            'index' => ['for' => ['me' =>'fi']],
            'user' => ['fig' => ['fao' => 'mio']]
        ]);

        $this->assertEquals([
            'index' => ['for' => ['me' =>'fi']],
            'user' => ['fig' => ['fao' => 'mio']]
        ], $target->getRouteParameters());

        $target->setRouteParameters([
            'index' => ['for' => ['ma' => 'go']],
            'profile' => ['zag' => 'mid']
        ]);

        $this->assertEquals([
            'index' => ['for' => ['me' =>'fi', 'ma' => 'go']],
            'user' => ['fig' => ['fao' => 'mio']],
            'profile' => ['zag' => 'mid']
        ], $target->getRouteParameters());

        $target->setRouteParameters([
            'index' => ['bam' => 'bum']
        ], false);

        $this->assertEquals(['index' => ['bam' => 'bum']], $target->getRouteParameters());
    }

    public function testOptions(): void
    {
        $target = $this->context;

        $this->assertCount(0, $target->getOptions());

        $target->setOptions([
            'tg1' => [
                'opt1' => 'val1',
                'opt2' => 'val2'
            ]
        ]);

        $this->assertEquals(['tg1' => ['opt1' => 'val1', 'opt2' => 'val2']], $target->getOptions());


        $target->setOptions([
            'tg1' => ['opt1' => 'val3'],
            'tg2' => ['opt21' => 'val21']
        ]);

        $this->assertEquals([
            'tg1' => ['opt1' => 'val3', 'opt2' => 'val2'],
            'tg2' => ['opt21' => 'val21']
        ], $target->getOptions());

        $target->setOptions(['tg1' => ['miki' => 'moko']], false);

        $this->assertEquals(['tg1' => ['miki' => 'moko']], $target->getOptions());
    }

    public function testDefaultOptions(): void
    {
        $target = $this->context;

        $this->assertCount(0, $target->getDefaultOptions());

        $target->setDefaultOptions([
            'tg1' => [
                'opt1' => 'val1',
                'opt2' => 'val2'
            ]
        ]);

        $this->assertEquals(['tg1' => ['opt1' => 'val1', 'opt2' => 'val2']], $target->getDefaultOptions());


        $target->setDefaultOptions([
            'tg1' => ['opt1' => 'val3'],
            'tg2' => ['opt21' => 'val21']
        ]);

        $this->assertEquals([
            'tg1' => ['opt1' => 'val3', 'opt2' => 'val2'],
            'tg2' => ['opt21' => 'val21']
        ], $target->getDefaultOptions());

        $target->setDefaultOptions(['tg1' => ['miki' => 'moko']], false);

        $this->assertEquals(['tg1' => ['miki' => 'moko']], $target->getDefaultOptions());
    }

    public function testRouteOptions(): void
    {
        $target = $this->context;

        $this->assertCount(0, $target->getRouteOptions());

        $target->setRouteOptions([
            'tg1' => [
                'opt1' => 'val1',
                'opt2' => 'val2'
            ]
        ]);

        $this->assertEquals(['tg1' => ['opt1' => 'val1', 'opt2' => 'val2']], $target->getRouteOptions());


        $target->setRouteOptions([
            'tg1' => ['opt1' => 'val3'],
            'tg2' => ['opt21' => 'val21']
        ]);

        $this->assertEquals([
            'tg1' => ['opt1' => 'val3', 'opt2' => 'val2'],
            'tg2' => ['opt21' => 'val21']
        ], $target->getRouteOptions());

        $target->setRouteOptions(['tg1' => ['miki' => 'moko']], false);

        $this->assertEquals(['tg1' => ['miki' => 'moko']], $target->getRouteOptions());
    }

    public function testHierarchy(): void
    {
        $target = $this->context;

        $hierarchy = [
            'login' => 'index',
            'register' => 'index',
            'contact' => 'index',
            'blog-post' => 'index',
            'blog-post-detail' => 'blog-post'
        ];

        $target->setHierarchy($hierarchy);

        $this->assertEquals($hierarchy, $target->getHierarchy());

        $this->assertNull($target->getParentRouteName('index'));

        $this->assertEquals('index',
            $target->getParentRouteName('login'));

        $this->assertEquals('blog-post',
            $target->getParentRouteName('blog-post-detail'));
    }

    public function testFinalField(): void
    {
        $target = $this->context;

        $this->assertNull($target->getFinalField('index', []));
        $this->assertNull($target->getFinalField('index', ['title']));

        $target->setDefaultFields([
            'title' => 'default title',
            'og' => [
                'title' => 'default og title'
            ]
        ]);

        $this->assertEquals('default title',
            $target->getFinalField('index', ['title']));

        $this->assertEquals('default og title',
            $target->getFinalField('index', ['og', 'title']));

        $target->setRouteFields([
            'login' => [
                'title' => 'login title'
            ],
            'register' => [
                'og' => ['title' => 'register og title']
            ]
        ]);

        $this->assertEquals('login title',
            $target->getFinalField('login', ['title']));

        $this->assertEquals('default og title',
            $target->getFinalField('login', ['og', 'title']));


        $this->assertEquals('default title',
            $target->getFinalField('register', ['title']));

        $this->assertEquals('register og title',
            $target->getFinalField('register', ['og', 'title']));

        $this->assertNull($target->getFinalField('index', ['title'], true));

        $this->assertEquals('default title',
            $target->getFinalField('register', ['title'], true));

        $target->setFields([
            'title' => 'last title'
        ]);

        $this->assertNull($target->getFinalField('index', ['title'], true));

        $this->assertEquals('last title',
            $target->getFinalField('index', ['title']));

        $this->assertEquals('default title',
            $target->getFinalField('register', ['title'], true));

        $this->assertEquals('last title',
            $target->getFinalField('zubizaretta', ['title']));

        $this->assertNull($target->getFinalField('zubizaretta', ['no', 'exists']));
    }

    public function testFinalParameters(): void
    {
        $target = $this->context;

        $this->assertCount(0,
            $target->getFinalParameters('index', []));

        $this->assertCount(0,
            $target->getFinalParameters('index', ['title']));


        $target->setDefaultParameters([
            'title' => ['fo' => 'fa'],
            'description' => ['fi' => 'ma']
        ]);

        $this->assertEquals(['fo' => 'fa'],
            $target->getFinalParameters('index', ['title']));

        $this->assertEquals(['fi' => 'ma'],
            $target->getFinalParameters('index', ['description']));

        $target->setRouteParameters([
            'index' => [
                'title' => ['mi' => 'ko']
            ],
            'profile' => [
                'description' => ['zo' => 'ka']
            ],
            'info' => [
                'og' => ['title' => ['le' => 'pa']]
            ]
        ]);

        $this->assertEquals(['fo' => 'fa', 'mi' => 'ko'],
            $target->getFinalParameters('index', ['title']));

        $this->assertEquals(['le' => 'pa'],
            $target->getFinalParameters('info', ['og', 'title']));

        $this->assertEquals(['fi' => 'ma', 'zo' => 'ka'],
            $target->getFinalParameters('profile', ['description']));

        $target->setParameters(['title' => ['banga' => 'bonga']]);

        $this->assertEquals(['fo' => 'fa', 'mi' => 'ko', 'banga' => 'bonga'],
            $target->getFinalParameters('index', ['title']));

        $this->assertEquals(['fo' => 'fa', 'banga' => 'bonga'],
            $target->getFinalParameters('noexist', ['title']));

        $target->setGlobalParameters(['tiri' => 'toro']);

        $this->assertEquals(['fo' => 'fa', 'mi' => 'ko', 'banga' => 'bonga', 'tiri' => 'toro'],
            $target->getFinalParameters('index', ['title']));

        $this->assertEquals(['fo' => 'fa', 'banga' => 'bonga', 'tiri' => 'toro'],
            $target->getFinalParameters('noexist', ['title']));

        $this->assertEquals(['tiri' => 'toro'],
            $target->getFinalParameters('noexist', ['unknown']));
    }

    public function testFinalOptions(): void
    {
        $target = $this->context;

        $this->assertCount(0,
            $target->getFinalOptions('index', []));

        $this->assertCount(0,
            $target->getFinalOptions('index', ['title']));

        $target->setDefaultOptions([
            'title' => ['foo' => 'fee']
        ]);

        $this->assertEquals(['foo' => 'fee'],
            $target->getFinalOptions('index', ['title']));

        $target->setRouteOptions([
            'index' => ['title' => ['fii' => 'faa']],
            'profile' => ['og' => ['title' => ['ree' => 'roo']]]
        ]);

        $this->assertEquals(['foo' => 'fee', 'fii' => 'faa'],
            $target->getFinalOptions('index', ['title']));

        $this->assertEquals(['foo' => 'fee'],
            $target->getFinalOptions('nonexists', ['title']));

        $this->assertEquals(['ree' => 'roo'],
            $target->getFinalOptions('profile', ['og', 'title']));

        $target->setOptions([
            'title' => ['loo' => 'lee'],
            'og' => ['title' => ['mee' => 'maa']]
        ]);

        $this->assertEquals(['foo' => 'fee', 'fii' => 'faa', 'loo' => 'lee'],
            $target->getFinalOptions('index', ['title']));

        $this->assertEquals(['foo' => 'fee', 'loo' => 'lee'],
            $target->getFinalOptions('nonexists', ['title']));

        $this->assertEquals(['ree' => 'roo', 'mee' => 'maa'],
            $target->getFinalOptions('profile', ['og', 'title']));
    }

    public function testFieldData(): void
    {
        $target = $this->context;

        $target->setDefaultFields([
            'title' => 'Some information about {target} subject on {siteName}'
        ]);

        $target->setRouteFields([
            'index' => [ 'title' => 'Homepage' ],
            'login' => [ 'title' => 'Login screen' ],
            'register' => [ 'title' => 'Register screen' ]
        ]);

        $target->setFields([
            'description' => 'Description of the content'
        ]);

        $target->setDefaultParameters([
            'title' => ['target' => 'Monga monga']
        ]);

        $target->setGlobalParameters([
            'siteName' => 'Acme Co.'
        ]);

        $target->setRouteParameters([
            'index' => ['title' => ['lalalo' => 'lolofa']],
            'info-something' => [ 'title' => [ 'target' => 'Something' ] ],
            'info-other' => [ 'title' => ['target' => 'Other'] ]
        ]);

        $target->setParameters([
            'zinga' => 'Zonga'
        ]);


        $target->setDefaultOptions([
            'title' => ['opt1' => 'val1']
        ]);

        $target->setRouteOptions([
            'info-something' => ['title' => ['opt2' => 'vala']],
            'info-other' => ['title' => ['opt2' => 'valb']]
        ]);

        $target->setOptions(['title' => ['opt3' => 'val3']]);

        $target->setHierarchy([
            'login' => 'index', 'register' => 'index',
            'info-other' => 'index', 'info-something' => 'index'
        ]);

        $this->assertNull($target->getFieldData('index', ['nonexists']));


        $this->assertNull($target->getFieldData('nonexists', ['title'], true));

        $fieldData = $target->getFieldData('nonexists', ['title']);

        $this->assertNotNull($fieldData);

        $this->assertEquals('Some information about {target} subject on {siteName}',
            $fieldData->getContent());

        $this->assertEquals([
            'siteName' => 'Acme Co.',
            'target' => 'Monga monga'
        ], $fieldData->getParameters());

        $this->assertEquals(['opt1' => 'val1', 'opt3' => 'val3'], $fieldData->getOptions());

        $this->assertNull($fieldData->getParent());

        $fieldData = $target->getFieldData('index', ['title']);

        $this->assertNotNull($fieldData);

        $this->assertNull($fieldData->getParent());

        $this->assertEquals('Homepage',
            $fieldData->getContent());

        $this->assertEquals([
            'siteName' => 'Acme Co.',
            'lalalo' => 'lolofa',
            'target' => 'Monga monga'
        ], $fieldData->getParameters());

        $this->assertEquals([
            'opt1' => 'val1',
            'opt3' => 'val3'
        ], $fieldData->getOptions());

        $fieldData = $target->getFieldData('login', ['title']);

        $this->assertNotNull($fieldData);

        $this->assertEquals('Login screen',
            $fieldData->getContent());

        $this->assertEquals([
            'siteName' => 'Acme Co.',
            'target' => 'Monga monga'
        ], $fieldData->getParameters());

        $this->assertEquals([
            'opt1' => 'val1',
            'opt3' => 'val3'
        ], $fieldData->getOptions());

        $fieldData = $target->getFieldData('register', ['title']);

        $this->assertNotNull($fieldData);

        $parentData = $fieldData->getParent();

        $this->assertNotNull($parentData);

        $this->assertEquals('Homepage',
            $parentData->getContent());

        $this->assertEquals([
            'siteName' => 'Acme Co.',
            'lalalo' => 'lolofa',
            'target' => 'Monga monga'
        ], $parentData->getParameters());

        $this->assertEquals([
            'opt1' => 'val1',
            'opt3' => 'val3'
        ], $parentData->getOptions());

        $this->assertEquals('Register screen',
            $fieldData->getContent());

        $this->assertEquals([
            'siteName' => 'Acme Co.',
            'target' => 'Monga monga'
        ], $fieldData->getParameters());

        $this->assertEquals([
            'opt1' => 'val1',
            'opt3' => 'val3'
        ], $fieldData->getOptions());


        $fieldData = $target->getFieldData('info-something', ['title']);

        $this->assertNotNull($fieldData);

        $this->assertEquals('Some information about {target} subject on {siteName}',
            $fieldData->getContent());

        $this->assertEquals([
            'siteName' => 'Acme Co.',
            'target' => 'Something'
        ], $fieldData->getParameters());

        $this->assertEquals([
            'opt1' => 'val1',
            'opt2' => 'vala',
            'opt3' => 'val3'
        ], $fieldData->getOptions());

        $fieldData = $target->getFieldData('info-other', ['title']);

        $this->assertNotNull($fieldData);

        $this->assertEquals('Some information about {target} subject on {siteName}',
            $fieldData->getContent());

        $this->assertEquals([
            'siteName' => 'Acme Co.',
            'target' => 'Other'
        ], $fieldData->getParameters());

        $this->assertEquals([
            'opt1' => 'val1',
            'opt2' => 'valb',
            'opt3' => 'val3'
        ], $fieldData->getOptions());
    }
}

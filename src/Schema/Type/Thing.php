<?php

namespace Tug\SeoBundle\Schema\Type;

use Tug\SeoBundle\JsonLd\Attribute as JsonLd;

#[JsonLd\Type('Thing', 'https://schema.org')]
class Thing
{
    #[JsonLd\Property('additionalType', ['URL', 'Text'])]
    public ?string $additionalType = null;

    #[JsonLd\Property('alternateName', ['Text'])]
    public ?string $alternateName = null;

    #[JsonLd\Property('description', ['Text', 'TextObject'])]
    public ?string $description = null;

    #[JsonLd\Property('disambiguatingDescription', ['Text'])]
    public ?string $disambiguatingDescription = null;

    #[JsonLd\Property('identifier', ['PropertyValue', 'URL', 'Text'])]
    public ?string $identifier = null;

    #[JsonLd\Property('image', ['ImageObject', 'URL'])]
    public ?string $image = null;

    #[JsonLd\Property('mainEntityOfPage', ['CreativeWork', 'URL'])]
    public ?string $mainEntityOfPage = null;

    #[JsonLd\Property('text', ['Text'])]
    public ?string $name = null;

    #[JsonLd\Property('potentialAction', ['Action'])]
    public ?string $potentialAction = null;

    #[JsonLd\Property('sameAs', ['URL'])]
    public ?string $sameAs = null;

    #[JsonLd\Property('subjectOf', ['CreativeWork', 'Event'])]
    public ?string $subjectOf = null;

    #[JsonLd\Property('url', ['URL'])]
    public ?string $url = null;
}

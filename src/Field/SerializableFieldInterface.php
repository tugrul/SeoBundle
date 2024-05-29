<?php

namespace Tug\SeoBundle\Field;

use Symfony\Component\Serializer\SerializerInterface;

interface SerializableFieldInterface
{
    public function setSerializer(SerializerInterface $serializer);
}
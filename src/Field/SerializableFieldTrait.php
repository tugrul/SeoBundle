<?php

namespace Tug\SeoBundle\Field;

use Symfony\Component\Serializer\SerializerInterface;

trait SerializableFieldTrait
{
    protected SerializerInterface $serializer;

    public function setSerializer(SerializerInterface $serializer): self
    {
        $this->serializer = $serializer;

        return $this;
    }
}
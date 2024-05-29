<?php

namespace Tug\SeoBundle\Model;

class Script implements ModelInterface
{
    protected ?string $type = null;

    protected ?string $source = null;

    protected ?string $nonce = null;

    protected ?string $body = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function setSource(?string $source): static
    {
        $this->source = $source;
        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function getNonce(): ?string
    {
        return $this->nonce;
    }

    public function setNonce(?string $nonce): static
    {
        $this->nonce = $nonce;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(mixed $body): static
    {
        $this->body = $body;

        return $this;
    }

    public static function getHandleName(): string
    {
        return 'script';
    }
}

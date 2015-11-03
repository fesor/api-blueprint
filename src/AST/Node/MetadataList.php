<?php

namespace Fesor\ApiBlueprint\AST\Node;

class MetadataList implements \JsonSerializable
{
    /**
     * @var KeyValue[]
     */
    private $metadata;

    /**
     * MetadataList constructor.
     */
    public function __construct()
    {
        $this->metadata = [];
    }

    /**
     * @param KeyValue $metadata
     */
    public function addMetadata(KeyValue $metadata)
    {
        $this->metadata[] = $metadata;
    }

    /**
     * @return array|KeyValue[]
     */
    public function all()
    {
        return $this->metadata;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getValue($name)
    {
        foreach ($this->metadata as $metadata) {
            if ($metadata->getName() === $name) {
                return $metadata->getValue();
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->metadata;
    }
}
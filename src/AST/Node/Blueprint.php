<?php

namespace Fesor\ApiBlueprint\AST\Node;

class Blueprint implements \JsonSerializable
{
    const AST_VERSION = '3.0';

    /**
     * Version of the AST Serialization
     *
     * @var string
     */
    private $version = '';

    /**
     * Ordered array of API Blueprint metadata
     *
     * @var MetadataList
     */
    private $metadata = [];

    /**
     * Name of the API
     *
     * @var string
     */
    private $name;

    /**
     * Top-level description of the API in Markdown (.raw) or HTML (.html)
     *
     * @var string
     */
    private $description;

    /**
     * Element name
     *
     * @var string
     */
    private $element = 'category';

    /**
     * @var Element[]
     */
    private $content;

    /**
     * Blueprint constructor.
     * @param MetadataList $metadata
     * @param string $name
     * @param string $description
     */
    public function __construct(MetadataList $metadata, $name, $description)
    {
        $this->version = self::AST_VERSION;
        $this->metadata = $metadata;
        $this->name = (string) $name;
        $this->description = (string) $description;
        $this->element = Element::CATEGORY;
        $this->content = [];
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return MetadataList
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @return Element[]
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            '_version' => $this->version,
            'metadata' => $this->metadata,
            'name' => $this->name,
            'description' => $this->description,
            'element' => $this->element,
            'content' => $this->content
        ];
    }


}
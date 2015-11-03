<?php

namespace Fesor\ApiBlueprint\Parser;

use Fesor\ApiBlueprint\AST\Node\Blueprint;
use Fesor\ApiBlueprint\AST\Node\KeyValue;
use Fesor\ApiBlueprint\AST\Node\MetadataList;

class MetadataParser implements Parser
{
    /**
     * @var Parser
     */
    private $next;

    /**
     * MetadataParser constructor.
     * @param Parser $next
     */
    public function __construct(Parser $next)
    {
        $this->next = $next;
    }

    /**
     * @inheritdoc
     */
    public function parse($source, Blueprint $blueprint = null)
    {
        if (null === $blueprint) {
            $blueprint = new Blueprint(new MetadataList(), '', '');
        }

        $metadata = $blueprint->getMetadata();
        $trimmedSource = $source;

        foreach ($this->getLines($trimmedSource) as $line) {
            if ($this->isEmptyLine($line)) {
                continue;
            }

            if (($pair = $this->tryGetMetadataPair($line))) {
                $metadata->addMetadata($pair);
            } else {
                $trimmedSource = $line . $trimmedSource;
                break;
            }
        }

        return $this->next->parse($trimmedSource, $blueprint);
    }

    /**
     * @param string $line
     * @return bool
     */
    private function isEmptyLine($line)
    {
        return '' === trim($line);
    }

    /**
     * @param string $line
     * @return KeyValue|null
     */
    private function tryGetMetadataPair($line)
    {
        if (preg_match('/^\s*([^:]+)\s*:\s*([^\s].+)$/', $line, $matches)) {

            return new KeyValue(trim($matches[1]), trim($matches[2]));
        }

        return null;
    }

    /**
     * @param $blueprint
     * @return \Generator
     */
    private function getLines(&$blueprint)
    {
        while ('' !== $blueprint) {
            $lines = preg_split('/\r\n|\n|\r/', $blueprint, 2, PREG_SPLIT_DELIM_CAPTURE);
            $line = $lines[0];
            if (isset($lines[1])) {
                $blueprint = $lines[1];
            } else {
                $blueprint = '';
            }

            yield $line;
        }
    }
}

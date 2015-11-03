<?php

namespace Fesor\ApiBlueprint\Parser;

use Fesor\ApiBlueprint\AST\Node\Blueprint;

interface Parser
{
    /**
     * @param string $source of blueprint
     * @param Blueprint|null $blueprint to be filled
     * @return Blueprint
     */
    public function parse($source, Blueprint $blueprint = null);
}
<?php

namespace spec\Fesor\ApiBlueprint\Parser;

use Fesor\ApiBlueprint\AST\Node\Blueprint;
use Fesor\ApiBlueprint\AST\Node\KeyValue;
use Fesor\ApiBlueprint\AST\Node\MetadataList;
use Fesor\ApiBlueprint\Parser\Parser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MetadataParserSpec extends ObjectBehavior
{
    /**
     * @var Parser
     */
    private $next;

    function let(Parser $next)
    {
        $this->next = $next;
        $this->beConstructedWith($next);
    }

    function it_parses_metadata()
    {
        $this->nextParserShouldBeCalled('');
        $this->parse('KEY: VALUE')->shouldHasMetadata('KEY', 'VALUE');
    }

    function it_ignores_empty_strings()
    {
        $this->nextParserShouldBeCalled('');
        $this->parse("FOO: BAR\n\nBAR: BUZ")->shouldHasMetadata('BAR', 'BUZ');
    }

    function it_trims_name_and_value()
    {
        $this->nextParserShouldBeCalled('');
        $this->parse(" FOO : BAR   ")->shouldHasMetadata('FOO', 'BAR');
    }

    function it_allows_colon_to_be_used_in_value()
    {
        $this->nextParserShouldBeCalled('');
        $this->parse("FOO: BA:R   ")->shouldHasMetadata('FOO', 'BA:R');
    }

    function it_requires_a_value()
    {
        $this->nextParserShouldBeCalled('FOO:     ');
        $this->parse('FOO:     ');
    }

    function it_passes_rest_of_source_to_next_parser()
    {
        $this->nextParserShouldBeCalled('blueprint');
        $this->parse("FOO: BAR\nblueprint")->shouldHasMetadata('FOO', 'BAR');
    }

    function it_allows_no_metadata()
    {
        $this->nextParserShouldBeCalled('blueprint');
        $this->parse("blueprint");
    }

    function it_uses_existing_blueprint_node_if_passed(Blueprint $blueprint, MetadataList $metadataList)
    {
        $this->nextParserShouldBeCalled('');
        $blueprint->getMetadata()->willReturn($metadataList);
        $metadataList->addMetadata(Argument::that(function (KeyValue $argument) {
            return $argument->getName() === 'FOO' && $argument->getValue() === 'BAR';
        }));
        $this->parse('FOO: BAR', $blueprint);
    }

    public function getMatchers()
    {
        return [
            'hasMetadata' => function ($subject, $name, $value) {
                return $subject instanceof Blueprint && $value === $subject->getMetadata()->getValue($name);
            }
        ];
    }

    private function nextParserShouldBeCalled($expectedSource)
    {
        $this->next->parse($expectedSource, Argument::type(Blueprint::class))->willReturnArgument(1);
    }
}

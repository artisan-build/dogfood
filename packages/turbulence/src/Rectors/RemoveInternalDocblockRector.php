<?php

namespace ArtisanBuild\Turbulence\Rectors;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveInternalDocblockRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove @internal docblock from a specific class', [
            new CodeSample(
                <<<'CODE'
/**
 * @internal
 */
class MyTargetClass {}
CODE
                ,
                <<<'CODE'
class MyTargetClass {}
CODE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (! $node instanceof Class_) {
            return null;
        }

        $docComment = $node->getDocComment();

        if ($docComment === null) {
            return null;
        }

        $docText = $docComment->getText();

        if (str_contains($docText, '@internal')) {
            $node->setDocComment(new Doc(str_replace('@internal', '', $docText)));
        }

        return $node;
    }
}

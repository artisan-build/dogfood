<?php

namespace ArtisanBuild\Turbulence\Rectors;

use Illuminate\Support\Str;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUse;
use Rector\Rector\AbstractRector;

final class AddHasAccountsTraitRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (! $node instanceof Class_) {
            return null;
        }

        // Check if the trait is already used
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof TraitUse) {
                foreach ($stmt->traits as $trait) {
                    if (Str::endsWith($trait->name, 'HasAccounts')) {
                        return null;
                    }
                }
            }
        }

        // Add the trait
        $node->stmts = array_merge(
            [new TraitUse([new Name('App\Traits\HasAccounts')])],
            $node->stmts
        );

        return $node;
    }
}

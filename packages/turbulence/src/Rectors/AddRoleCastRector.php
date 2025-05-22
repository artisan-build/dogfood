<?php

namespace ArtisanBuild\Turbulence\Rectors;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Return_;
use Rector\Rector\AbstractRector;

final class AddRoleCastRector extends AbstractRector
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

        $roleCast = new ArrayItem(
            new ClassConstFetch(new Name(\ArtisanBuild\Turbulence\Enums\UserRoles::class), new Identifier('class')),
            new String_('role')
        );

        // Handle $casts property
        foreach ($node->getProperties() as $property) {
            if ($this->nodeNameResolver->isName($property, 'casts')) {
                $prop = $property->props[0];
                if ($prop->default instanceof Array_) {
                    foreach ($prop->default->items as $item) {
                        if ($item->key instanceof String_ && $item->key->value === 'role') {
                            return null;
                        }
                    }

                    $prop->default->items[] = $roleCast;

                    return $node;
                }
            }
        }

        // Handle casts() method
        foreach ($node->getMethods() as $method) {
            if ($this->nodeNameResolver->isName($method, 'casts')) {
                foreach ((array) $method->stmts as $stmt) {
                    if ($stmt instanceof Return_ && $stmt->expr instanceof Array_) {
                        foreach ($stmt->expr->items as $item) {
                            if ($item->key instanceof String_ && $item->key->value === 'role') {
                                return null;
                            }
                        }

                        $stmt->expr->items[] = $roleCast;

                        return $node;
                    }
                }
            }
        }

        return null;
    }
}

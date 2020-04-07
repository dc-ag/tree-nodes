<?php

declare(strict_types=1);

namespace TreeNodes;


interface TreeNodeVisitor
{
    /**
     *
     * @param SortableTreeNode|null $node
     * @return void
     */
    public function visitPreOrder(?SortableTreeNode $node): void;

    /**
     *
     * @param SortableTreeNode|null $node
     * @return void
     */
    public function visitPostOrder(?SortableTreeNode $node): void;
}

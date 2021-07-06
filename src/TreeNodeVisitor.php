<?php

declare(strict_types=1);

namespace TreeNodes;

interface TreeNodeVisitor
{
    /**
     * 
     * @param null|SortableTreeNode $node 
     * @param bool $atRoot 
     * @return void 
     */
    public function visitPreOrder(?SortableTreeNode $node, bool $atRoot = true): void;

    /**
     * 
     * @param null|SortableTreeNode $node 
     * @param bool $atRoot 
     * @return void 
     */
    public function visitPostOrder(?SortableTreeNode $node, bool $atRoot = true): void;
}

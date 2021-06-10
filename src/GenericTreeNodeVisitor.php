<?php

declare(strict_types=1);

namespace TreeNodes;

class GenericTreeNodeVisitor implements TreeNodeVisitor
{
    /** @var callable */
    private $visitorCallable;

    /**
     *
     * @param callable $visitorCallable
     */
    public function __construct(callable $visitorCallable)
    {
        $this->visitorCallable = $visitorCallable;
    }

    /**
     * 
     * @param null|SortableTreeNode $node 
     * @param bool $atRoot 
     * @return void 
     */
    public function visitPreOrder(?SortableTreeNode $node, bool $atRoot = true): void
    {
        if (null === $node) {
            return;
        }
        $leftmostChild = null;
        if ($node->getNoOfChildrenWithSorting() > 0) {
            $leftmostChild = reset($node->getChildrenWithSorting());
            while ($leftmostChild->getLeftSibling() !== null) {
                $leftmostChild = $leftmostChild->getLeftSibling();
            }
        }
        ($this->visitorCallable)($node);
        $this->visitPreOrder($leftmostChild, false);
        if ($atRoot === false) {
            $this->visitPreOrder($node->getRightSibling(), false);
        }
    }

    /**
     * 
     * @param null|SortableTreeNode $node 
     * @param bool $atRoot 
     * @return void 
     */
    public function visitPostOrder(?SortableTreeNode $node, bool $atRoot = true): void
    {
        if (null === $node) {
            return;
        }
        $leftmostChild = null;
        if ($node->getNoOfChildrenWithSorting() > 0) {
            $leftmostChild = reset($node->getChildrenWithSorting());
            while ($leftmostChild->getLeftSibling() !== null) {
                $leftmostChild = $leftmostChild->getLeftSibling();
            }
        }

        $this->visitPostOrder($leftmostChild, false);
        ($this->visitorCallable)($node);
        if ($atRoot === false) {
            $this->visitPostOrder($node->getRightSibling(), false);
        }
    }
}

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
     * @param SortableTreeNode|null $node
     * @return void
     */
    public function visitPreOrder(?SortableTreeNode $node): void
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
        $this->visitPreOrder($leftmostChild);
        $this->visitPreOrder($node->getRightSibling());
    }

    /**
     *
     * @param SortableTreeNode|null $node
     * @return void
     */
    public function visitPostOrder(?SortableTreeNode $node): void
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

        $this->visitPostOrder($leftmostChild);
        ($this->visitorCallable)($node);
        $this->visitPostOrder($node->getRightSibling());
    }
}

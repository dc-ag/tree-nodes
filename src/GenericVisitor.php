<?php
declare(strict_types=1);

namespace TreeNodes;


class GenericVisitor implements Visitor
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
     * @param TreeNode $node 
     * @param bool $atRoot 
     * @return void 
     */
    public function visitPreOrder(TreeNode $node): void
    {
        ($this->visitorCallable)($node);
        $children = $node instanceof SortableTreeNode ? $node->getChildrenWithSorting() : $node->getChildren();
        foreach ($children as $childNode) {
            $this->visitPreOrder($childNode);
        }
    }

    /**
     * 
     * @param TreeNode $node 
     * @param bool $atRoot 
     * @return void 
     */
    public function visitPostOrder(TreeNode $node, $atRoot = true): void
    {
        $children = $node instanceof SortableTreeNode ? $node->getChildrenWithSorting() : $node->getChildren();
        
        foreach ($children as $currChild) {
            $this->visitPostOrder($currChild, false);
            ($this->visitorCallable)($currChild);
        }
        if ($atRoot) {
            ($this->visitorCallable)($node);
        }
    }

    /**
     * 
     * @param TreeNode $node 
     * @param bool $atRoot 
     * @return void 
     */
    public function visitLevelOrder(TreeNode $node): void
    {
        ($this->visitorCallable)($node);

        $getChildren = static fn(TreeNode $n) => $n instanceof SortableTreeNode ? $n->getChildrenWithSorting() : $n->getChildren();

        $nextLevelNodes = [];
        $currlevelNodes = $getChildren($node);

        $collectNextLevelNodes = static function(TreeNode $treeNode) use (&$nextLevelNodes, &$getChildren) { 
             $nextLevelNodes = [...$nextLevelNodes, ...$getChildren($treeNode)];
        };

        while (!empty($currlevelNodes)) {
            while (!empty($currlevelNodes)) {
                $currNode = \array_shift($currlevelNodes);
                $collectNextLevelNodes($currNode);
                ($this->visitorCallable)($currNode);
            }
            $currlevelNodes = $nextLevelNodes;
            $nextLevelNodes = [];
        }
    }
}

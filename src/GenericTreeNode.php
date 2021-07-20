<?php

declare(strict_types=1);

namespace TreeNodes;

use ArrayIterator;
use Closure;
use ErrorException;
use InvalidArgumentException;
use RangeException;

class GenericTreeNode implements TreeNode
{
    private string $id;
    private $payload;
    private ?TreeNode $parent = null;
    private array $children = [];
    protected ?string $rootId = null;


    /**
     * GenericTreeNode constructor.
     * @param $payload
     * @param IdGenerator|null $idGenerator
     */
    public function __construct(?string $id, $payload, ?IdGenerator $idGenerator = null)
    {
        if ($idGenerator === null) {
            $idGenerator = new GenericIdGenerator();
        }

        $this->id = $id ?? $idGenerator->getId();
        $this->payload = $payload;
        $this->rootId = $this->id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        $level = 0;
        if ($this->isRoot()) {
            return $level;
        }

        $parent = $this->getParent();
        while (null !== $parent) {
            $level++;
            $parent = $parent->getParent();
        }

        return $level;
    }

    /**
     * @return TreeNode|null
     */
    public function getParent(): ?TreeNode
    {
        return $this->parent;
    }

    /**
     * @param TreeNode|null $parent
     */
    public function setParent(?TreeNode $parent): void
    {
        $this->parent = $parent;
        $this->rootId = $parent !== null ? $parent->getRootId() : null;

        if ($this->getNoOfChildren() > 0) {
            /** @var TreeNode $child */
            foreach ($this->getChildren() as $child) {
                $child->setParent($this);
            }
        }
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param TreeNode $child
     */
    public function addChild(TreeNode $child): void
    {
        $this->children[] = $child;

        if ($child->getParent() !== $this) {
            $child->setParent($this);
        }
    }

    /**
     * @param TreeNode $childToRemove
     */
    public function removeChild(TreeNode $childToRemove): void
    {
        foreach ($this->children as $key => $child) {
            if ($child === $childToRemove) {
                unset($this->children[$key]);
            }
        }

        $childToRemove->setParent(null);
    }

    /**
     * @return int
     */
    public function getNoOfChildren(): int
    {
        return count($this->children);
    }

    public function getNoOfDescendants(): int
    {
        $noOfDescendants = 0;
        $noOfDescendants += count($this->children);

        /**
         * @var $child TreeNode
         */
        foreach ($this->children as $child) {
            $noOfDescendants += $child->getNoOfDescendants();
        }

        return $noOfDescendants;
    }

    public function isLeaf(): bool
    {
        return empty($this->children);
    }

    public function isRoot(): bool
    {
        return $this->parent === null;
    }

    /**
     * @return TreeNode
     */
    public function getRootForTreeNode(): TreeNode
    {
        if ($this->isRoot()) {
            return $this;
        }

        $rootNode = $this->getParent();
        while (!$rootNode->isRoot()) {
            $rootNode = $rootNode->getParent();
        }

        return $rootNode;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        $height = 0;
        if ($this->isLeaf()) {
            return $height;
        }

        $maxChildHeight = 0;
        /**
         * @var $child TreeNode
         */
        foreach ($this->children as $child) {
            $childHeight = $child->getHeight();
            if ($childHeight > $maxChildHeight) {
                $maxChildHeight = $childHeight;
            }
        }

        $height = $maxChildHeight + 1;

        return $height;
    }

    /**
     * 
     * @return null|string 
     */
    public function getRootId(): ?string
    {
        return $this->rootId;
    }

    public function findNode(callable $identifierPredicate, int $searchOrder): ?TreeNode
    {
        $foundNode = null;

        $visitorFn = static function(TreeNode $node) use (&$foundNode, $identifierPredicate) {
            if ($identifierPredicate($node)) {
                $foundNode = $node;
                //Abort early with specific exception once found
                throw new RangeException();
            }
        };
        
        try {
        switch ($searchOrder) {
            case TreeNode::SEARCH_PRE_ORDER:
                $this->nonSortablePreOrderVisitingFunction($this, $visitorFn);
                break;
            case TreeNode::SEARCH_POST_ORDER:
                $this->nonSortablePostOrderVisitingFunction($this, $visitorFn);
                break;
            case TreeNode::SEARCH_LEVEL_ORDER:
                $this->nonSortableLevelOrderVisitingFunction($this, $visitorFn);
                break;
            default:
                throw new InvalidArgumentException('Unknown search-order code [' . $searchOrder . ']. Please referr to constants in TreeNode-class.');

        }
        } catch (RangeException $t) {
            //All good
        }
        return $foundNode;
    }

    protected function nonSortablePreOrderVisitingFunction(?TreeNode $treeNode, callable $visitorFn): void
    {
        if (null === $treeNode) {
            return;
        }

        $visitorFn($treeNode);
        $childIterator = new ArrayIterator($treeNode->getChildren());
        if ($treeNode->getNoOfChildren() > 0) {
            $currChild = $childIterator->current();
            while (null !== $currChild) {
                $this->nonSortablePreOrderVisitingFunction($currChild, $visitorFn);
                $childIterator->next();
            }
        }       

    }

    protected function nonSortablePostOrderVisitingFunction(?TreeNode $treeNode, callable $visitorFn): void
    {
        if (null === $treeNode) {
            return;
        }
        $childIterator = new ArrayIterator($treeNode->getChildren());
        if ($treeNode->getNoOfChildren() > 0) { 
            $currChild = $childIterator->current();
            while (null !== $currChild) {
                $this->nonSortablePreOrderVisitingFunction($currChild, $visitorFn);
                $visitorFn($treeNode);
                $childIterator->next();
            }
        }       

    }

    
    protected function nonSortableLevelOrderVisitingFunction(?TreeNode $treeNode, callable $visitorFn): void
    {
        if (null === $treeNode) {
            return;
        }
        $visitorFn($treeNode);

        $nextLevelNodes = [];
        $currlevelNodes = $treeNode->getChildren();

        $collectNextLevelNodes = static fn(SortableTreeNode $treeNode) => $nextLevelNodes += $treeNode->getChildren();

        do {
            while (!empty($currlevelNodes)) {
                $currNode = \array_shift($levelNodes);
                $collectNextLevelNodes($currNode);
                $visitorFn($currNode);
            }
            $currlevelNodes = $nextLevelNodes;
            $nextLevelNodes = [];
        } while (!empty($currlevelNodes));    

    }

    public function replaceChildNode(TreeNode $childToReplace, TreeNode $replacementChildNode): void
    {
        $childCount = $this->getNoOfChildren();
    
        for ($i = 0; $i < $childCount; $i++) {
            $currChild = $this->children[$i];
            if ($currChild->getId() === $childToReplace->getId()) {
                $currParentOfReplacement = $replacementChildNode->getParent();
                if (null === $currParentOfReplacement || $currParentOfReplacement->getId() !== $this->getId()) {
                    $replacementChildNode->setParent($this);
                }
                $this->children[$i] = $replacementChildNode;
                break;
            }
        }
    }

}

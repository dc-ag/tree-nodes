<?php

declare(strict_types=1);

namespace TreeNodes;

use ArrayIterator;
use Closure;
use ErrorException;
use InvalidArgumentException;
use JsonSerializable;
use RangeException;

class GenericTreeNode implements TreeNode
{
    protected string $id;
    protected $payload;
    protected ?TreeNode $parent = null;
    protected array $children = [];
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
        $rootId = null;

        if (null !== $parent) {
            $rootId = $parent->isRoot() ? $parent->getId() : $parent->getRootId();
        }

        $this->rootId = $rootId;

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
                    throw new InvalidArgumentException('Unknown search-order code [' . $searchOrder . ']. Please referr to constants in TreeNode-interface.');

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
            while ($childIterator->valid()) {
                $currChild = $childIterator->current();
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
            while ($childIterator->valid()) {
                $currChild = $childIterator->current();
                $this->nonSortablePostOrderVisitingFunction($currChild, $visitorFn);
                $visitorFn($currChild);
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

        $collectNextLevelNodes = static function(TreeNode $treeNode) use (&$nextLevelNodes) { 
            $nextLevelNodes = [...$nextLevelNodes, ...$treeNode->getChildren()];
       };

       do {
           while (!empty($currlevelNodes)) {
               $currNode = \array_shift($currlevelNodes);
               $collectNextLevelNodes($currNode);
               $visitorFn($currNode);
           }
           $currlevelNodes = $nextLevelNodes;
           $nextLevelNodes = [];
       } while (!empty($currlevelNodes));

    }

    public function replaceDescendant(TreeNode $descendantToReplace, TreeNode $replacementSubtree): void
    {
        $descendantId = $descendantToReplace->getId();
        $this->replaceDescendantById($descendantId, $replacementSubtree);

    }

    public function setChildren(TreeNode ...$nodes): void
    {
        $thisId = $this->getId();
        foreach ($nodes as $node) {
            $nodeParent = $node->getParent();
            if (null === $nodeParent || $nodeParent->getId() !== $thisId) {
                $node->setParent($this);
            }
        }
        $this->children = $nodes;        
    }

    public function removeChildById(string $id): void
    {
        $count = $this->getNoOfChildren();
        $newChildArr = [];
        for ($i = 0; $i < $count; $i++) {
            $child = $this->children[$i];
            if ($child->getId() !== $id) {
                $newChildArr[] = $child;
            } else {
                $child->setParent(null);
            }
        }
        $this->children = $newChildArr;
        
    }

    public function replaceDescendantById(string $descendantId, TreeNode $replacementSubtree): void
    {
        $findPredicate = static fn(?TreeNode $n) => null !== $n && $n->getId() === $descendantId;
        $foundNodeToReplace = $this->findNode($findPredicate, TreeNode::SEARCH_PRE_ORDER);

        if (null !== $foundNodeToReplace) {
            $foundNodeParent = $foundNodeToReplace->getParent();
            $childCount = $foundNodeParent->getNoOfChildren();
            $siblingsandSelf = $foundNodeParent->getChildren();
            for ($i = 0; $i < $childCount; $i++) {
                $currChild = $siblingsandSelf[$i];
                if ($currChild->getId() === $descendantId) {
                    $currChild->setParent(null);
                    $replacementSubtree->setParent($foundNodeParent);
                    $siblingsandSelf[$i] = $replacementSubtree;
                }
                $foundNodeParent->setChildren(...$siblingsandSelf);
            }
        }
    }

    protected function clonePayload(TreeNode $node)
    {
        $payload = $node->getPayload();
        if (\is_object($payload)) {
            $clonedPayload = clone $payload;
        } else {
            $clonedPayload = $payload;
        }
        return $clonedPayload;
    }

    public function getDeepCopy(): TreeNode
    {
        
        $clonedParent = new static($this->getId(), $this->clonePayload($this));
        $clonedParent->setChildren(...$this->getChildren());

        $nextLevelParents = [];
        $currParents = [$clonedParent];
        $iterator = new ArrayIterator($currParents);
        while ($iterator->valid()) {
            $currParent = $iterator->current();
            $currChildren = $currParent->getChildren();
            $currParent->setChildren();
            foreach ($currChildren as $currChild) {
                $newCurrChild = new static($currChild->getId(), $this->clonePayload($currChild));
                $newCurrChild->setChildren(...$currChild->getChildren());
                $newCurrChild->setParent($currParent);
                $clonedParent->addChild($newCurrChild);
                $nextLevelParents[] = $newCurrChild;
            }
            $iterator->next();
        }
        return $clonedParent;
    }

}

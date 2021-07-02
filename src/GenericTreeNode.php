<?php

declare(strict_types=1);

namespace TreeNodes;

use InvalidArgumentException;

class GenericTreeNode implements TreeNode
{
    private string $id;
    private $payload;
    private ?TreeNode $parent = null;
    private array $children = [];
    private ?string $rootId = null;

    /**
     * GenericTreeNode constructor.
     * @param $payload
     * @param IdGenerator|null $idGenerator
     */
    public function __construct($payload, ?IdGenerator $idGenerator = null)
    {
        if ($idGenerator === null) {
            $idGenerator = new GenericIdGenerator();
        }

        $this->id = $idGenerator->getId();
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
        if ($parent instanceof TreeNode) {
            $this->setRootId($parent->getRootId());
        } else {
            $this->setRootId($this->getId());
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

    /**
     * 
     * @param null|string $rootId 
     * @return void 
     * @throws InvalidArgumentException 
     */
    public function setRootId(?string $rootId): void
    {
        if($this->getRootForTreeNode()->getId() !== $rootId) {
            throw new InvalidArgumentException("Given rootId [$rootId] has to match id of currrent node root node id");
        }

        $this->rootId = $rootId;
        if ($this->getNoOfChildren() > 0) {
            /** @var TreeNode $child */
            foreach ($this->getChildren() as $child) {
                $child->setRootId($rootId);
            }
        }
    }
}

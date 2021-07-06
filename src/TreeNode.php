<?php

declare(strict_types=1);

namespace TreeNodes;

/**
 * Interface TreeNode
 * @package TreeNodes
 */
interface TreeNode
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return mixed
     */
    public function getPayload();

    /**
     * @return int
     */
    public function getLevel(): int;

    /**
     * @return TreeNode|null
     */
    public function getParent(): ?TreeNode;

    /**
     * @param TreeNode|null $parent
     */
    public function setParent(?TreeNode $parent): void;

    /**
     * @return TreeNode[]
     */
    public function getChildren(): array;

    /**
     * @param TreeNode $child
     */
    public function addChild(TreeNode $child): void;

    /**
     * @param TreeNode $childToRemove
     */
    public function removeChild(TreeNode $childToRemove): void;

    /**
     * @return int
     */
    public function getNoOfChildren(): int;

    /**
     * @return int
     */
    public function getNoOfDescendants(): int;

    /**
     * @return bool
     */
    public function isLeaf(): bool;

    /**
     * @return bool
     */
    public function isRoot(): bool;

    /**
     * @return TreeNode
     */
    public function getRootForTreeNode(): TreeNode;

    /**
     * @return int
     */
    public function getHeight(): int;

    /**
     * 
     * @return null|string 
     */
    public function getRootId(): ?string;
}

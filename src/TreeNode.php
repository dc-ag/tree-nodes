<?php

declare(strict_types=1);

namespace TreeNodes;

use Closure;

/**
 * Interface TreeNode
 * @package TreeNodes
 */
interface TreeNode
{

    public const SEARCH_PRE_ORDER = 0;
    public const SEARCH_POST_ORDER = 1;
    public const SEARCH_LEVEL_ORDER = 2;

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
     * @param TreeNode $childToReplace
     * @param TreeNode $replacementChildNode
     */
    public function replaceChildNode(TreeNode $childToReplace, TreeNode $replacementChildNode): void;

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

    /**
     * @param Closure $identifierPredicate - A callable encapsulating a function from TreeNode to bool. 
     *                                       The TreeNode returned by findNode will be first instance 
     *                                       where the closure returns true or null if it does not 
     *                                       return true on any node in the tree.
     * @param integer $searchOrder         - Directive to search pre-order (0), in-order (1), 
     *                                       in-order (2) or level-order (3)
     * @return TreeNode|null
     */
    public function findNode(callable $identifierPredicate, int $searchOrder): ?TreeNode;

}

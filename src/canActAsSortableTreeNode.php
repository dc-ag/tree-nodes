<?php

declare(strict_types=1);

namespace TreeNodes;

trait canActAsSortableTreeNode
{
    protected array $childrenWithSorting = [];

    /**
     * @return int
     */
    public function getPerLevelSorting(): int
    {
        $perLevelSorting = 1;

        $node = $this;
        while ($node->getLeftSibling() !== null) {
            $node = $node->getLeftSibling();
            $perLevelSorting++;
        }

        return $perLevelSorting;
    }

    /**
     * @return SortableTreeNode|null
     */
    public function getLeftSibling(): ?SortableTreeNode
    {
        $leftSibling = null;
        $thisId = $this->getId();
        if ($this->isRoot()) {
            return $leftSibling;
        }
        $initialId = $this->getId();
        $parent = $this->getParent();
        if ($parent !== null && $parent instanceof SortableTreeNode) {
            $parentChildren = $parent->getChildrenWithSorting();
            /** @var TreeNode $child */
            foreach ($parentChildren as $sorting => $child) {
                if ($child->getId() === $this->getId()) {
                    if (1 === $sorting) {
                        return null;
                    }
                    $newSorting = $sorting - 1;
                    if (array_key_exists($newSorting, $parentChildren)) {
                        if (($parentChildren[$newSorting])->getId() !== $initialId) {
                            $leftSibling = $parentChildren[$newSorting];
                            break;
                        }
                    }
                }
            }
        }

        return $leftSibling;
    }


    /**
     * @return SortableTreeNode|null
     */
    public function getRightSibling(): ?SortableTreeNode
    {
        $rightSibling = null;
        $parent = $this->getParent();
        if ($this->isRoot()) {
            return $rightSibling;
        }
        $initialId = $this->getId();
        if ($parent !== null && $parent instanceof SortableTreeNode) {
            $parentChildren = $parent->getChildrenWithSorting();
            $maxSorting = max(0,...\array_keys($parentChildren));
            /** @var TreeNode $child */
            foreach ($parentChildren as $sorting => $child) {
                if ($child->getId() === $this->getId()) {
                    if ($sorting === $maxSorting) {
                        return null;
                    }
                    $newSorting = $sorting + 1;
                    if (array_key_exists($newSorting, $parentChildren)) {
                        $currRightChild = $parentChildren[$newSorting];
                        $currRightChildId = $currRightChild->getId();
                        if ($currRightChildId !== $initialId) {
                            $rightSibling = $currRightChild;
                            break;
                        }
                    }
                }
            }
        }

        return $rightSibling;
    }

    /**
     * @return array
     */
    public function &getChildrenWithSorting(): array
    {
        return $this->childrenWithSorting;
    }

    /**
     * @return int
     */
    public function getNoOfChildrenWithSorting(): int
    {
        return count($this->childrenWithSorting);
    }

    /**
     * @return int
     */
    public function getNoOfDescendantsWithSorting(): int
    {
        $noOfDescendants = 0;
        $noOfDescendants += count($this->childrenWithSorting);

        /**
         * @var $childWithSorting SortableTreeNode
         */
        foreach ($this->childrenWithSorting as $childWithSorting) {
            $noOfDescendants += $childWithSorting->getNoOfDescendantsWithSorting();
        }

        return $noOfDescendants;
    }

    /**
     * @return bool
     */
    public function isLeafWithSorting(): bool
    {
        return empty($this->childrenWithSorting);
    }

    /**
     * @return int
     */
    public function getHeightWithSorting(): int
    {
        $height = 0;
        if ($this->isLeafWithSorting()) {
            return $height;
        }

        $maxChildHeight = 0;
        /**
         * @var $childWithSorting SortableTreeNode
         */
        foreach ($this->childrenWithSorting as $childWithSorting) {
            $childHeight = $childWithSorting->getHeightWithSorting();
            if ($childHeight > $maxChildHeight) {
                $maxChildHeight = $childHeight;
            }
        }

        $height = $maxChildHeight + 1;

        return $height;
    }
}


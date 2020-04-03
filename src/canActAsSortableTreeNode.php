<?php


namespace TreeNodes;


trait canActAsSortableTreeNode
{
    private array $childrenWithSorting = [];

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
        $parent = $this->getParent();
        if ($parent !== null && $parent instanceof SortableTreeNode) {
            $parentChildren = $parent->getChildrenWithSorting();
            /** @var TreeNode $child */
            foreach ($parentChildren as $sorting => $child) {
                if ($child->getId() === $this->getId()) {
                    if (isset($parentChildren[($sorting - 1)])) {
                        $leftSibling = $parentChildren[($sorting - 1)];
                        break;
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
        if ($parent !== null && $parent instanceof SortableTreeNode) {
            $parentChildren = $parent->getChildrenWithSorting();
            /** @var TreeNode $child */
            foreach ($parentChildren as $sorting => $child) {
                if ($child->getId() === $this->getId()) {
                    if (isset($parentChildren[($sorting + 1)])) {
                        $rightSibling = $parentChildren[($sorting + 1)];
                        break;
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

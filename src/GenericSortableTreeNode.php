<?php

declare(strict_types=1);

namespace TreeNodes;

class GenericSortableTreeNode extends GenericTreeNode implements SortableTreeNode, TreeNodeSortRequestProcessor
{
    use canProcessTreeNodeSortRequests,
        canActAsSortableTreeNode;

    /**
     *
     * @param SortableTreeNode $child
     * @param integer|null $sorting
     * @return void
     */
    public function addChildWithSorting(SortableTreeNode $child, ?int $sorting = null): void
    {
        if ($sorting !== null && !array_key_exists($sorting, $this->childrenWithSorting)) {
            $newSorting = $sorting;
            $this->childrenWithSorting[$newSorting] = $child;
        } else {
            $currentHighestSorting = 0;
            if ($this->getNoOfChildrenWithSorting() > 0) {
                $currentHighestSorting = max(array_keys($this->childrenWithSorting));
            }
            $newSorting = $currentHighestSorting + 1;
            $this->childrenWithSorting[$newSorting] = $child;
        }
        parent::addChild($child);
    }

    /**
     * @param SortableTreeNode $childToRemove
     */
    public function removeChildWithSorting(SortableTreeNode $childToRemove, ?array &$changedNodes = null): void
    {
        $changedNodes = null === $changedNodes ? [] : $changedNodes;
        $sortingChildToRemove = $childToRemove->getPerLevelSorting();

        ksort($this->childrenWithSorting);

        /** @var SortableTreeNode $childWithSorting */
        foreach ($this->childrenWithSorting as $sorting => $childWithSorting) {
            $currentSorting = $sorting;
            if ($childWithSorting === $childToRemove) {
                unset($this->childrenWithSorting[$sorting]);
            } elseif ($currentSorting > $sortingChildToRemove) {
                unset($this->childrenWithSorting[$currentSorting]);
                $this->childrenWithSorting[($currentSorting - 1)] = $childWithSorting;
                $changedNodes[] = $childWithSorting;
            }
        }

        parent::removeChild($childToRemove);
    }
}

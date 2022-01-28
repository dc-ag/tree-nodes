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
        $nodeToRemoveId = $childToRemove->getId();

        /** @var SortableTreeNode $childWithSorting */
        $newChildArray = [];

        foreach ($this->childrenWithSorting as $sorting => $childWithSorting) {
            $nodeId = $childWithSorting->getId();
            $currentSorting = $sorting;
            if ($nodeId === $nodeToRemoveId) {
                //Do nothing
            } elseif ($currentSorting < $sortingChildToRemove) {
                $newChildArray[$currentSorting] = $childWithSorting;
            }elseif ($currentSorting > $sortingChildToRemove) {
                $newChildArray[($currentSorting - 1)] = $childWithSorting;
                $changedNodes[$nodeId] = $childWithSorting;
                $changedNodeSorting = $childWithSorting->getPerLevelSorting();
            }
        }
        ksort($newChildArray);
        $this->childrenWithSorting = $newChildArray;

        parent::removeChild($childToRemove);
    }
}

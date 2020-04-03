<?php


namespace TreeNodes;


class GenericSortableTreeNode extends GenericTreeNode implements SortableTreeNode, TreeNodeSortRequestProcessor
{
    use canProcessTreeNodeSortRequests,
        canActAsSortableTreeNode;

    /**
     * @param SortableTreeNode $child
     */
    public function addChildWithSorting(SortableTreeNode $child): void
    {
        $currentHighestSorting = $this->getNoOfChildrenWithSorting();

        $this->childrenWithSorting[($currentHighestSorting + 1)] = $child;
        parent::addChild($child);
    }

    /**
     * @param SortableTreeNode $childToRemove
     */
    public function removeChildWithSorting(SortableTreeNode $childToRemove): void
    {
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
            }
        }

        parent::removeChild($childToRemove);
    }
}

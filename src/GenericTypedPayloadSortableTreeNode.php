<?php


namespace TreeNodes;

use InvalidArgumentException;

class GenericTypedPayloadSortableTreeNode extends GenericTypedPayloadTreeNode implements TypedPayloadSortableTreeNode, TreeNodeSortRequestProcessor
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

    /**
     * @param TypedPayloadSortableTreeNode $child
     */
    public function addChildWithTypedPayloadAndWithSorting(TypedPayloadSortableTreeNode $child): void
    {
        if (parent::isTypedPayloadValid($child->getPayload(), $this->getPayloadType(), $this->getPayloadObjectFQDN())) {
            $this->addChildWithSorting($child);
        } else {
            throw new InvalidArgumentException("Could not add child with typed payload, type has to be [{$this->getPayloadType()}]");
        }
    }
}

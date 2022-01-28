<?php

declare(strict_types=1);

namespace TreeNodes;

use InvalidArgumentException;

class GenericTypedPayloadSortableTreeNode extends GenericTypedPayloadTreeNode implements TypedPayloadSortableTreeNode, TreeNodeSortRequestProcessor
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
        $childId = $child->getId();
        $parentId = $this->getId();
        $children = $this->childrenWithSorting;
        if ($sorting !== null && !array_key_exists($sorting, $children)) {
            $newSorting = $sorting;
            $this->childrenWithSorting[$newSorting] = $child;
        } else {
            $currentHighestSorting = 0;
            if ($this->getNoOfChildrenWithSorting() > 0) {
                $currentHighestSorting = max(array_keys($children));
            }
            $newSorting = $currentHighestSorting + 1;
            $this->childrenWithSorting[$newSorting] = $child;
        }
        parent::addChild($child);
    }

    public function removeAllChildren(): void
    {
        $this->children = [];
        $this->childrenWithSorting = [];
    }

    /**
     * @param SortableTreeNode $childToRemove
     */
    public function removeChildWithSorting(SortableTreeNode $childToRemove, ?array &$changedNodes = null): void
    {
        $changedNodes = null === $changedNodes ? [] : $changedNodes;
        $sortingChildToRemove = $childToRemove->getPerLevelSorting();
        $nodeToRemoveId = $childToRemove->getId();
        $newChildArray = [];
        $locallyChangedNodes = [];
        foreach ($this->childrenWithSorting as $sorting => $childWithSorting) {
            $nodeId = $childWithSorting->getId();
            $currentSorting = $sorting;
            if ($nodeId === $nodeToRemoveId) {
                //Do nothing
            } elseif ($currentSorting < $sortingChildToRemove) {
                $newChildArray[$currentSorting] = $childWithSorting;
            }elseif ($currentSorting > $sortingChildToRemove) {
                $newSorting = $currentSorting - 1;
                $newChildArray[$newSorting] = $childWithSorting;
                $changedNodes[$nodeId] = $childWithSorting;
                $id = $childWithSorting->getId();
                $locallyChangedNodes[$id]['node'] = $childWithSorting;
                $locallyChangedNodes[$id]['targetSorting'] = $newSorting;
                $locallyChangedNodes[$id]['originalSorting'] = $sorting;
            }
        }
        ksort($newChildArray);
        $this->childrenWithSorting = $newChildArray;
        foreach ($locallyChangedNodes as $id => $changedNodeArr) {
            $changedNode = $changedNodeArr['node'];
            $targetSorting = $changedNodeArr['targetSorting'];
            $originalSorting = $changedNodeArr['originalSorting'];
        }

        parent::removeChild($childToRemove);
    }

    /**
     *
     * @param TypedPayloadSortableTreeNode $child
     * @param integer|null $sorting
     * @return void
     */
    public function addChildWithTypedPayloadAndWithSorting(TypedPayloadSortableTreeNode $child, ?int $sorting = null): void
    {
        $childPayload = $child->getPayload();
        $childPayloadPHPType = \gettype($childPayload);
        $childPayloadPHPFQDN = \is_object($childPayload) ? $childPayload::class : null;
        if (parent::isTypedPayloadValid($child->getPayload(), $this->getPayloadType(), $this->getPayloadObjectFQDN())) {
            $this->addChildWithSorting($child, $sorting);
        } else {
            throw new InvalidArgumentException("Could not add child with typed payload, type has to be [{$this->getPayloadType()}] - actual type [$childPayloadPHPType] with fqdn [$childPayloadPHPFQDN].");
        }
    }
}

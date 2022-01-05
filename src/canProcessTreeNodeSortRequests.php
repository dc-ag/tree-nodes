<?php

declare(strict_types=1);

namespace TreeNodes;

trait canProcessTreeNodeSortRequests
{
    /**
     * @param SortableTreeNode $treeNodeToSort
     * @param int $newSorting
     */
    public static function processNewSortingRequest(SortableTreeNode $treeNodeToSort, int $newSorting, ?array &$changedNodes = null): void
    {
        $changedNodes = null === $changedNodes ? [] : $changedNodes;
        $parentTreeNode = $treeNodeToSort->getParent();

        if ($parentTreeNode instanceof SortableTreeNode && $parentTreeNode->getNoOfChildrenWithSorting() > 0) {

            $currSorting = $treeNodeToSort->getPerLevelSorting();

            if ($currSorting === $newSorting) {
                return;
            }
            $changedNodes[] = $treeNodeToSort;
            $minAffectedSorting = min($currSorting, $newSorting);
            $maxAffectedSorting = max($currSorting, $newSorting);
            $targetMovesUp = $newSorting > $currSorting;

            $childrenWithSortingRef = &$parentTreeNode->getChildrenWithSorting();
            ksort($childrenWithSortingRef);
            $newChildArray = [];

            /** @var SortableTreeNode $currNode */
            foreach ($childrenWithSortingRef as $sorting => $currNode) {
                if ($sorting < $minAffectedSorting || $sorting > $maxAffectedSorting) {
                    $newChildArray[$sorting] = $currNode;
                    continue;
                }
                if ($sorting === $currSorting) {
                    continue;
                }
                $changedNodes[] = $currNode;

                if ($targetMovesUp) {
                    $newChildArray[($sorting - 1)] = $currNode;
                } else {
                    $newChildArray[($sorting + 1)] = $currNode;
                }
            }
            $newChildArray[$newSorting] = $treeNodeToSort;
            ksort($newChildArray);
            $childrenWithSortingRef = $newChildArray;
        }
    }

    /**
     *
     * @param SortableTreeNode $nodeToMove
     * @param SortableTreeNode $newParentNode
     * @param integer $newSorting
     * @return void
     */
    public static function processMoveRequest(SortableTreeNode $nodeToMove, SortableTreeNode $newParentNode, int $newSorting, ?array &$changedNodes = null): void
    {
        /** @var SortableTreeNode $nodeToMoveParent */
        $nodeToMoveParent = $nodeToMove->getParent();
        if ($nodeToMoveParent !== $newParentNode) {
          $nodeToMoveParent->removeChildWithSorting($nodeToMove, $changedNodes);
          $newParentNode->addChildWithSorting($nodeToMove);
        }
        GenericSortableTreeNode::processNewSortingRequest($nodeToMove, $newSorting, $changedNodes);
    }
}

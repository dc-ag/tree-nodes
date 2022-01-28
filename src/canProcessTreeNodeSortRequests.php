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
        $currSorting = $treeNodeToSort->getPerLevelSorting();
        $parentTreeNode = $treeNodeToSort->getParent();

        if ($parentTreeNode instanceof SortableTreeNode && $parentTreeNode->getNoOfChildrenWithSorting() > 0) {

            if ($currSorting === $newSorting) {
                return;
            }
            $changedNodes[$treeNodeToSort->getId()] = $treeNodeToSort;
            $minAffectedSorting = min($currSorting, $newSorting);
            $maxAffectedSorting = max($currSorting, $newSorting);
            $targetMovesUp = $newSorting > $currSorting;

            $childrenWithSortingRef = &$parentTreeNode->getChildrenWithSorting();
            ksort($childrenWithSortingRef);
            $newChildArray = [];

            /** @var SortableTreeNode $currNode */
            $locallyChangedNodes = [];
            $allNodes = [];
            foreach ($childrenWithSortingRef as $sorting => $currNode) {
                $sorting = (int)$sorting;
                $currId = $currNode->getId();
                $allNodes[$currId] = $currNode;
                if ($sorting < $minAffectedSorting || $sorting > $maxAffectedSorting) {
                    $newChildArray[$sorting] = $currNode;
                    continue;
                }
                if ($sorting === $currSorting) {
                    continue;
                }

                $changedNodes[$currNode->getId()] = $currNode;

                if ($targetMovesUp) {
                    $newLocalSorting = $sorting- 1;
                    $locallyChangedNodes[$currNode->getId()]['node'] = $currNode;
                    $locallyChangedNodes[$currNode->getId()]['originalSorting'] = $currNode->getPerLevelSorting();
                    $locallyChangedNodes[$currNode->getId()]['targetSorting'] = $newLocalSorting;
                    $newChildArray[$newLocalSorting] = $currNode;
                } else {
                    $newLocalSorting = $sorting + 1;
                    $locallyChangedNodes[$currNode->getId()]['node'] = $currNode;
                    $locallyChangedNodes[$currNode->getId()]['originalSorting'] = $currNode->getPerLevelSorting();
                    $locallyChangedNodes[$currNode->getId()]['targetSorting'] = $newLocalSorting;
                    $newChildArray[$newLocalSorting] = $currNode;
                }
            }
            $newChildArray[$newSorting] = $treeNodeToSort;
            ksort($newChildArray);
            $childrenWithSortingRef = $newChildArray;
            $newChildren = $parentTreeNode->getChildrenWithSorting();
            $newChildrenForPrint = array_map(static fn($n) => $n->getId(), $newChildren);
            foreach ($newChildrenForPrint as $sorting => $newChildId) {
                $perLevelSorting = ($allNodes[$newChildId])->getPerLevelSorting();
            }
            foreach ($locallyChangedNodes as $changedNodeArr) {
                $changedNode = $changedNodeArr['node'];
                $changedNodeId = $changedNode->getId();
                $changedNodeCurrSorting = $changedNode->getPerLevelSorting();
                $changedNodeOrigSorting = $changedNodeArr['originalSorting'];
                $changedNodeTargetSorting = $changedNodeArr['targetSorting'];
            }
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
        $nodeToMoveParentId = $nodeToMoveParent->getId();
        $newParentNodeId = $newParentNode->getId();
        if ($nodeToMoveParentId !== $newParentNodeId) {
            $nodeToMoveParent->removeChildWithSorting($nodeToMove, $changedNodes);
            $newParentNode->addChildWithSorting($nodeToMove);
        }
        GenericSortableTreeNode::processNewSortingRequest($nodeToMove, $newSorting, $changedNodes);
    }
}

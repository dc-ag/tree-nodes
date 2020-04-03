<?php

namespace TreeNodes;


trait canProcessTreeNodeSortRequests
{
    /**
     * @param SortableTreeNode $treeNodeToSort
     * @param int $newSorting
     */
    public static function processNewSortingRequest(SortableTreeNode $treeNodeToSort, int $newSorting): void
    {
        $parentTreeNode = $treeNodeToSort->getParent();

        if ($parentTreeNode instanceof SortableTreeNode && $parentTreeNode->getNoOfChildrenWithSorting() > 0) {
            $childrenWithSorting = &$parentTreeNode->getChildrenWithSorting();
            $treeNodeToSortPerLevelSorting = $treeNodeToSort->getPerLevelSorting();
            $moveUpAction = $treeNodeToSortPerLevelSorting < $newSorting;
            $moveDownAction = $treeNodeToSortPerLevelSorting > $newSorting;

            if ($moveUpAction) {
                ksort($childrenWithSorting);
            } elseif ($moveDownAction) {
                krsort($childrenWithSorting);
            }

            unset($childrenWithSorting[$treeNodeToSortPerLevelSorting]);

            /** @var SortableTreeNode $childWithSorting */
            foreach ($childrenWithSorting as $sorting => $childWithSorting) {
                if (
                    ($moveDownAction &&
                        ($sorting < $newSorting ||
                            $sorting > $treeNodeToSortPerLevelSorting)) ||
                    ($moveUpAction &&
                        ($sorting > $newSorting ||
                            $sorting < $treeNodeToSortPerLevelSorting))
                ) {
                    continue;
                }

                if ($moveDownAction) {
                    unset($childrenWithSorting[$sorting]);
                    $childrenWithSorting[($sorting + 1)] = $childWithSorting;
                } elseif ($moveUpAction) {
                    unset($childrenWithSorting[$sorting]);
                    $childrenWithSorting[($sorting - 1)] = $childWithSorting;
                }
            }

            $childrenWithSorting[$newSorting] = $treeNodeToSort;
            ksort($childrenWithSorting);
        }
    }

    /**
     *
     * @param SortableTreeNode $nodeToMove
     * @param SortableTreeNode $newParentNode
     * @param integer $newSorting
     * @return void
     */
    public static function processMoveRequest(SortableTreeNode $nodeToMove, SortableTreeNode $newParentNode, int $newSorting): void
    {
        /** @var SortableTreeNode $nodeToMoveParent */
        $nodeToMoveParent = $nodeToMove->getParent();
        $nodeToMoveParent->removeChildWithSorting($nodeToMove);
        $newParentNode->addChildWithSorting($nodeToMove);
        GenericSortableTreeNode::processNewSortingRequest($nodeToMove, $newSorting);
    }
}

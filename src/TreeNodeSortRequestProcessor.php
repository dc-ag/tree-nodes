<?php


namespace TreeNodes;


interface TreeNodeSortRequestProcessor extends SortableTreeNode
{
    /**
     * @param SortableTreeNode $treeNodeToSort
     * @param int $newSorting
     */
    public static function processNewSortingRequest(SortableTreeNode $treeNodeToSort, int $newSorting): void;
}
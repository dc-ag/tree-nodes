<?php


namespace TreeNodes;


interface TypedPayloadSortableTreeNode extends TypedPayloadTreeNode, SortableTreeNode
{
    /**
     * @param TypedPayloadSortableTreeNode $child
     */
    public function addChildWithTypedPayloadAndWithSorting(TypedPayloadSortableTreeNode $child): void;
}
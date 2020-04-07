<?php


namespace TreeNodes;


interface TypedPayloadSortableTreeNode extends TypedPayloadTreeNode, SortableTreeNode
{
    /**
     *
     * @param TypedPayloadSortableTreeNode $child
     * @param integer|null $sorting
     * @return void
     */
    public function addChildWithTypedPayloadAndWithSorting(TypedPayloadSortableTreeNode $child, ?int $sorting = null): void;
}

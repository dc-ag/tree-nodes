<?php
declare(strict_types=1);

namespace TreeNodes;

interface Visitor
{
    /**
     * 
     * @param TreeNode $node 
     * @param bool $atRoot 
     * @return void 
     */
    public function visitPreOrder(TreeNode $node): void;

    /**
     * 
     * @param TreeNode $node 
     * @param bool $atRoot 
     * @return void 
     */
    public function visitPostOrder(TreeNode $node): void;

    /**
     * 
     * @param TreeNode $node 
     * @param bool $atRoot 
     * @return void 
     */
    public function visitLevelOrder(TreeNode $node): void;

    
}

<?php

declare(strict_types=1);

namespace TreeNodes\Tests\Unit;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use TreeNodes\GenericSortableTreeNode;
use TreeNodes\TreeNodeVisitor;
use TreeNodes\GenericTreeNodeVisitor;
use TreeNodes\SortableTreeNode;

final class GenericTreeNodeVisitorTest extends TestCase
{
    private Generator $faker;
    private SortableTreeNode $demoTree;
    private TreeNodeVisitor $orderWritingVisitor;
    private string $orderString = "";

    protected function setUp(): void
    {
        $this->faker = Factory::create();

        $sortableTreeNode = new GenericSortableTreeNode("root", "root");

        $sortableTreeNodeFirstChild = new GenericSortableTreeNode("1", "1");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $sortableTreeNodeSecondChild = new GenericSortableTreeNode("2", "2");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $sortableTreeNodeSecondChildFirstChild = new GenericSortableTreeNode("2.1", "2.1");
        $sortableTreeNodeSecondChild->addChildWithSorting($sortableTreeNodeSecondChildFirstChild);

        $sortableTreeNodeSecondChildFirstChildFirstChild = new GenericSortableTreeNode("2.1.1", "2.1.1");
        $sortableTreeNodeSecondChildFirstChild->addChildWithSorting($sortableTreeNodeSecondChildFirstChildFirstChild);

        $sortableTreeNodeSecondChildFirstChildSecondChild = new GenericSortableTreeNode("2.1.2", "2.1.2");
        $sortableTreeNodeSecondChildFirstChild->addChildWithSorting($sortableTreeNodeSecondChildFirstChildSecondChild);

        $sortableTreeNodeSecondChildSecondChild = new GenericSortableTreeNode("2.2", "2.2");
        $sortableTreeNodeSecondChild->addChildWithSorting($sortableTreeNodeSecondChildSecondChild);

        $sortableTreeNodeThirdChild = new GenericSortableTreeNode("3", "3");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeThirdChild);

        $sortableTreeNodeThirdChildFirstChild = new GenericSortableTreeNode("3.1", "3.1");
        $sortableTreeNodeThirdChild->addChildWithSorting($sortableTreeNodeThirdChildFirstChild);

        $sortableTreeNodeFourthChild = new GenericSortableTreeNode("4", "4");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFourthChild);

        $sortableTreeNodeFifthChild = new GenericSortableTreeNode("5", "5");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFifthChild);

        $this->demoTree = $sortableTreeNode;

        $visitFn = function(SortableTreeNode $treeNode) {
            $separator = '' === $this->orderString ? '' : ', ';
            $this->orderString .= $separator . $treeNode->getId();
        };
        $this->orderWritingVisitor = new GenericTreeNodeVisitor($visitFn);
    
        
    }

    protected function tearDown(): void
    {
        unset($this->faker);
        unset($this->demoTree);
        unset($this->orderWritingVisitor);
        unset($this->orderString);
    }



    public function testVisitPreOrder(): void
    {
        $sortableTreeNode = $this->demoTree;
        $this->orderString = "";
        $expectedOrder = "root, 1, 2, 2.1, 2.1.1, 2.1.2, 2.2, 3, 3.1, 4, 5";
        $this->orderWritingVisitor->visitPreOrder($sortableTreeNode);
        $errMsg = 'Expected order: [' . $expectedOrder . '] - actual order [' . $this->orderString . '].';
        $this->assertEquals($expectedOrder, $this->orderString, $errMsg);
    }

    public function testVisitPostOrder(): void
    {
        $sortableTreeNode = $this->demoTree;
        $this->orderString = "";
        $expectedOrder = "1, 2.1.1, 2.1.2, 2.1, 2.2, 2, 3.1, 3, 4, 5, root";
        $this->orderWritingVisitor->visitPostOrder($sortableTreeNode);
        $errMsg = 'Expected order: [' . $expectedOrder . '] - actual order [' . $this->orderString . '].';
        $this->assertEquals($expectedOrder, $this->orderString, $errMsg);
    }
    
    public function testVisitLevelOrder(): void
    {
        $sortableTreeNode = $this->demoTree;
        $this->orderString = "";
        $expectedOrder = "root, 1, 2, 3, 4, 5, 2.1, 2.2, 3.1, 2.1.1, 2.1.2";
        $this->orderWritingVisitor->visitLevelOrder($sortableTreeNode);
        $errMsg = 'Expected order: [' . $expectedOrder . '] - actual order [' . $this->orderString . '].';
        $this->assertEquals($expectedOrder, $this->orderString, $errMsg);
    }
}

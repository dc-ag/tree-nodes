<?php

declare(strict_types=1);

namespace TreeNodes\Tests\Unit;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use TreeNodes\GenericTreeNode;
use TreeNodes\Visitor;
use TreeNodes\GenericVisitor;
use TreeNodes\TreeNode;


/**
 * @covers TreeNodes\GenericVisitor
 * @uses TreeNodes\GenericTreeNode
 * @uses TreeNodes\GenericTreeNode
 * @uses TreeNodes\canActAsSortableTreeNode
 */
final class GenericVisitorTest extends TestCase
{
    private Generator $faker;
    private TreeNode $demoTree;
    private Visitor $orderWritingVisitor;
    private string $orderString = "";

    protected function setUp(): void
    {
        $this->faker = Factory::create();

        $sortableTreeNode = new GenericTreeNode("root", "root");

        $sortableTreeNodeFirstChild = new GenericTreeNode("1", "1");
        $sortableTreeNode->addChild($sortableTreeNodeFirstChild);

        $sortableTreeNodeSecondChild = new GenericTreeNode("2", "2");
        $sortableTreeNode->addChild($sortableTreeNodeSecondChild);

        $sortableTreeNodeSecondChildFirstChild = new GenericTreeNode("2.1", "2.1");
        $sortableTreeNodeSecondChild->addChild($sortableTreeNodeSecondChildFirstChild);

        $sortableTreeNodeSecondChildFirstChildFirstChild = new GenericTreeNode("2.1.1", "2.1.1");
        $sortableTreeNodeSecondChildFirstChild->addChild($sortableTreeNodeSecondChildFirstChildFirstChild);

        $sortableTreeNodeSecondChildFirstChildSecondChild = new GenericTreeNode("2.1.2", "2.1.2");
        $sortableTreeNodeSecondChildFirstChild->addChild($sortableTreeNodeSecondChildFirstChildSecondChild);

        $sortableTreeNodeSecondChildSecondChild = new GenericTreeNode("2.2", "2.2");
        $sortableTreeNodeSecondChild->addChild($sortableTreeNodeSecondChildSecondChild);

        $sortableTreeNodeThirdChild = new GenericTreeNode("3", "3");
        $sortableTreeNode->addChild($sortableTreeNodeThirdChild);

        $sortableTreeNodeThirdChildFirstChild = new GenericTreeNode("3.1", "3.1");
        $sortableTreeNodeThirdChild->addChild($sortableTreeNodeThirdChildFirstChild);

        $sortableTreeNodeFourthChild = new GenericTreeNode("4", "4");
        $sortableTreeNode->addChild($sortableTreeNodeFourthChild);

        $sortableTreeNodeFifthChild = new GenericTreeNode("5", "5");
        $sortableTreeNode->addChild($sortableTreeNodeFifthChild);

        $this->demoTree = $sortableTreeNode;

        $visitFn = function(TreeNode $treeNode) {
            $separator = '' === $this->orderString ? '' : ', ';
            $this->orderString .= $separator . $treeNode->getId();
        };
        $this->orderWritingVisitor = new GenericVisitor($visitFn);
    
        
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

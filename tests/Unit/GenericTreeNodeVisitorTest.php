<?php

declare(strict_types=1);

namespace TreeNodes\Tests\Unit;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use TreeNodes\GenericSortableTreeNode;
use TreeNodes\GenericTreeNodeVisitor;
use TreeNodes\SortableTreeNode;

final class GenericTreeNodeVisitorTest extends TestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }

    protected function tearDown(): void
    {
        unset($this->faker);
    }

    public function testVisitPreOrder(): void
    {
        $sortableTreeNode = new GenericSortableTreeNode("root");

        $sortableTreeNodeFirstChild = new GenericSortableTreeNode("1");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $sortableTreeNodeSecondChild = new GenericSortableTreeNode("2");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $sortableTreeNodeSecondChildFirstChild = new GenericSortableTreeNode("2.1");
        $sortableTreeNodeSecondChild->addChildWithSorting($sortableTreeNodeSecondChildFirstChild);

        $sortableTreeNodeSecondChildFirstChildFirstChild = new GenericSortableTreeNode("2.1.1");
        $sortableTreeNodeSecondChildFirstChild->addChildWithSorting($sortableTreeNodeSecondChildFirstChildFirstChild);

        $sortableTreeNodeSecondChildFirstChildSecondChild = new GenericSortableTreeNode("2.1.2");
        $sortableTreeNodeSecondChildFirstChild->addChildWithSorting($sortableTreeNodeSecondChildFirstChildSecondChild);

        $sortableTreeNodeSecondChildSecondChild = new GenericSortableTreeNode("2.2");
        $sortableTreeNodeSecondChild->addChildWithSorting($sortableTreeNodeSecondChildSecondChild);

        $sortableTreeNodeThirdChild = new GenericSortableTreeNode("3");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeThirdChild);

        $sortableTreeNodeThirdChildFirstChild = new GenericSortableTreeNode("3.1");
        $sortableTreeNodeThirdChild->addChildWithSorting($sortableTreeNodeThirdChildFirstChild);

        $sortableTreeNodeFourthChild = new GenericSortableTreeNode("4");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFourthChild);

        $sortableTreeNodeFifthChild = new GenericSortableTreeNode("5");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFifthChild);

        $expectedNode = $sortableTreeNode;
        $visitorFunction = function (SortableTreeNode $node) use (&$expectedNode) {
            $this->assertTrue($expectedNode === $node);

            if ($node->getNoOfChildrenWithSorting() > 0) {
                $expectedNode = reset($node->getChildrenWithSorting());
            } elseif ($node->getRightSibling() !== null) {
                $expectedNode = $node->getRightSibling();
            } else {
                $parentNode = $node->getParent();
                if ($parentNode instanceof SortableTreeNode) {
                    $expectedNode = $parentNode->getRightSibling();
                }
            }
        };
        $genericTreeNoeVisitor = new GenericTreeNodeVisitor($visitorFunction);
        $genericTreeNoeVisitor->visitPreOrder($sortableTreeNode);
    }

    public function testVisitPostOrder(): void
    {
        $sortableTreeNode = new GenericSortableTreeNode("root");

        $sortableTreeNodeFirstChild = new GenericSortableTreeNode("1");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $sortableTreeNodeSecondChild = new GenericSortableTreeNode("2");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $sortableTreeNodeSecondChildFirstChild = new GenericSortableTreeNode("2.1");
        $sortableTreeNodeSecondChild->addChildWithSorting($sortableTreeNodeSecondChildFirstChild);

        $sortableTreeNodeSecondChildFirstChildFirstChild = new GenericSortableTreeNode("2.1.1");
        $sortableTreeNodeSecondChildFirstChild->addChildWithSorting($sortableTreeNodeSecondChildFirstChildFirstChild);

        $sortableTreeNodeSecondChildFirstChildSecondChild = new GenericSortableTreeNode("2.1.2");
        $sortableTreeNodeSecondChildFirstChild->addChildWithSorting($sortableTreeNodeSecondChildFirstChildSecondChild);

        $sortableTreeNodeSecondChildSecondChild = new GenericSortableTreeNode("2.2");
        $sortableTreeNodeSecondChild->addChildWithSorting($sortableTreeNodeSecondChildSecondChild);

        $sortableTreeNodeThirdChild = new GenericSortableTreeNode("3");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeThirdChild);

        $sortableTreeNodeThirdChildFirstChild = new GenericSortableTreeNode("3.1");
        $sortableTreeNodeThirdChild->addChildWithSorting($sortableTreeNodeThirdChildFirstChild);

        $sortableTreeNodeFourthChild = new GenericSortableTreeNode("4");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFourthChild);

        $sortableTreeNodeFifthChild = new GenericSortableTreeNode("5");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFifthChild);

        $expectedNode = $sortableTreeNodeFirstChild;
        $visitorFunction = function (SortableTreeNode $node) use (&$expectedNode) {
            $this->assertTrue($expectedNode === $node);
            $rightSibling = $node->getRightSibling();
            if ($rightSibling !== null) {
                if ($rightSibling->getNoOfChildrenWithSorting() > 0) {
                    $nodeToFind = reset($rightSibling->getChildrenWithSorting());
                    if ($nodeToFind instanceof SortableTreeNode) {
                        while ($nodeToFind->getNoOfChildrenWithSorting() > 0) {
                            $nodeToFind = reset($nodeToFind->getChildrenWithSorting());
                        }
                        $expectedNode = $nodeToFind;
                    }
                } else {
                    $expectedNode = $rightSibling;
                }
            } else {
                $parentNode = $node->getParent();
                if ($parentNode instanceof SortableTreeNode) {
                    $expectedNode = $parentNode;
                }
            }
        };
        $genericTreeNoeVisitor = new GenericTreeNodeVisitor($visitorFunction);
        $genericTreeNoeVisitor->visitPostOrder($sortableTreeNode);
    }
}

<?php

declare(strict_types=1);

namespace TreeNodes\Tests\Unit;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use TreeNodes\GenericSortableTreeNode;

final class GenericSortableTreeNodeTest extends TestCase
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

    public function testAddChildWithSorting(): void
    {
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericSortableTreeNode(null, $payload);

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericSortableTreeNode(null, $payloadFirstChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $payloadSecondChild = $this->faker->domainName;
        $sortableTreeNodeSecondChild = new GenericSortableTreeNode(null, $payloadSecondChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $payloadThirdChild = $this->faker->domainName;
        $sortableTreeNodeThirdChild = new GenericSortableTreeNode(null, $payloadThirdChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeThirdChild);

        $this->assertTrue($sortableTreeNodeFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChild->getPerLevelSorting() === 2);
        $this->assertTrue($sortableTreeNodeThirdChild->getPerLevelSorting() === 3);
    }

    public function testRemoveChildWithSorting(): void
    {
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericSortableTreeNode(null, $payload);

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericSortableTreeNode(null, $payloadFirstChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $payloadSecondChild = $this->faker->domainName;
        $sortableTreeNodeSecondChild = new GenericSortableTreeNode(null, $payloadSecondChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $payloadThirdChild = $this->faker->domainName;
        $sortableTreeNodeThirdChild = new GenericSortableTreeNode(null, $payloadThirdChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeThirdChild);

        $this->assertTrue($sortableTreeNode->getNoOfChildrenWithSorting() === 3);
        $this->assertTrue($sortableTreeNodeFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChild->getPerLevelSorting() === 2);
        $this->assertTrue($sortableTreeNodeThirdChild->getPerLevelSorting() === 3);

        $sortableTreeNode->removeChildWithSorting($sortableTreeNodeSecondChild);

        $this->assertTrue($sortableTreeNode->getNoOfChildrenWithSorting() === 2);
        $this->assertTrue($sortableTreeNodeFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeThirdChild->getPerLevelSorting() === 2);
    }

    public function testGetterPerLevelSorting(): void
    {
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericSortableTreeNode(null, $payload);

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericSortableTreeNode(null, $payloadFirstChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $payloadSecondChild = $this->faker->domainName;
        $sortableTreeNodeSecondChild = new GenericSortableTreeNode(null, $payloadSecondChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $this->assertTrue($sortableTreeNodeFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChild->getPerLevelSorting() === 2);
    }

    public function testGetterLeftSibling(): void
    {
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericSortableTreeNode(null, $payload);

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericSortableTreeNode(null, $payloadFirstChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $payloadSecondChild = $this->faker->domainName;
        $sortableTreeNodeSecondChild = new GenericSortableTreeNode(null, $payloadSecondChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $payloadThirdChild = $this->faker->domainName;
        $sortableTreeNodeThirdChild = new GenericSortableTreeNode(null, $payloadThirdChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeThirdChild);

        $this->assertTrue($sortableTreeNodeThirdChild->getLeftSibling() === $sortableTreeNodeSecondChild);
        $this->assertTrue($sortableTreeNodeSecondChild->getLeftSibling() === $sortableTreeNodeFirstChild);
        $this->assertTrue($sortableTreeNodeFirstChild->getLeftSibling() === null);
    }

    public function testGetterRightSibling(): void
    {
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericSortableTreeNode(null, $payload);

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericSortableTreeNode(null, $payloadFirstChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $payloadSecondChild = $this->faker->domainName;
        $sortableTreeNodeSecondChild = new GenericSortableTreeNode(null, $payloadSecondChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $payloadThirdChild = $this->faker->domainName;
        $sortableTreeNodeThirdChild = new GenericSortableTreeNode(null, $payloadThirdChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeThirdChild);


        $this->assertTrue($sortableTreeNodeFirstChild->getRightSibling() === $sortableTreeNodeSecondChild);
        $this->assertTrue($sortableTreeNodeSecondChild->getRightSibling() === $sortableTreeNodeThirdChild);
        $this->assertTrue($sortableTreeNodeThirdChild->getRightSibling() === null);
    }

    public function testGetterChildrenWithSorting(): void
    {
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericSortableTreeNode(null, $payload);

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericSortableTreeNode(null, $payloadFirstChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $payloadSecondChild = $this->faker->domainName;
        $sortableTreeNodeSecondChild = new GenericSortableTreeNode(null, $payloadSecondChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $payloadThirdChild = $this->faker->domainName;
        $sortableTreeNodeThirdChild = new GenericSortableTreeNode(null, $payloadThirdChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeThirdChild);

        $this->assertTrue(is_array($sortableTreeNode->getChildrenWithSorting()) && count($sortableTreeNode->getChildrenWithSorting()) === 3);
    }

    public function testGetterNoChildrenWithSorting(): void
    {
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericSortableTreeNode(null, $payload);

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericSortableTreeNode(null, $payloadFirstChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $payloadSecondChild = $this->faker->domainName;
        $sortableTreeNodeSecondChild = new GenericSortableTreeNode(null, $payloadSecondChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $payloadThirdChild = $this->faker->domainName;
        $sortableTreeNodeThirdChild = new GenericSortableTreeNode(null, $payloadThirdChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeThirdChild);

        $this->assertTrue($sortableTreeNode->getNoOfChildrenWithSorting() === 3);
    }

    public function testGetterNoOfDescendantsWithSorting(): void
    {
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericSortableTreeNode(null, $payload);

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericSortableTreeNode(null, $payloadFirstChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $payloadSecondChild = $this->faker->domainName;
        $sortableTreeNodeSecondChild = new GenericSortableTreeNode(null, $payloadSecondChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $payloadSecondChildFirstChild = $this->faker->domainName;
        $sortableTreeNodeSecondChildFirstChild = new GenericSortableTreeNode(null, $payloadSecondChildFirstChild);
        $sortableTreeNodeSecondChild->addChildWithSorting($sortableTreeNodeSecondChildFirstChild);

        $payloadThirdChild = $this->faker->domainName;
        $sortableTreeNodeThirdChild = new GenericSortableTreeNode(null, $payloadThirdChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeThirdChild);

        $this->assertTrue($sortableTreeNode->getNoOfDescendantsWithSorting() === 4);
    }

    public function testIsLeafWithSorting(): void
    {
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericSortableTreeNode(null, $payload);

        $this->assertTrue($sortableTreeNode->isLeafWithSorting());

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericSortableTreeNode(null, $payloadFirstChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $this->assertTrue(!$sortableTreeNode->isLeafWithSorting());
    }

    public function testGetterHeightWithSorting(): void
    {
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericSortableTreeNode(null, $payload);

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericSortableTreeNode(null, $payloadFirstChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $payloadFirstChildFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChildFirstChild = new GenericSortableTreeNode(null, $payloadFirstChild);
        $sortableTreeNodeFirstChild->addChildWithSorting($sortableTreeNodeFirstChildFirstChild);

        $this->assertTrue($sortableTreeNode->getHeightWithSorting() === 2);
    }

    public function testProcessSortRequestMoveUp(): void
    {
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericSortableTreeNode(null, $payload);

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericSortableTreeNode(null, $payloadFirstChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $payloadSecondChild = $this->faker->domainName;
        $sortableTreeNodeSecondChild = new GenericSortableTreeNode(null, $payloadSecondChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $payloadThirdChild = $this->faker->domainName;
        $sortableTreeNodeThirdChild = new GenericSortableTreeNode(null, $payloadThirdChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeThirdChild);

        $payloadFourthChild = $this->faker->domainName;
        $sortableTreeNodeFourthChild = new GenericSortableTreeNode(null, $payloadFourthChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFourthChild);

        $payloadFifthChild = $this->faker->domainName;
        $sortableTreeNodeFifthChild = new GenericSortableTreeNode(null, $payloadFifthChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFifthChild);

        $this->assertTrue($sortableTreeNodeFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChild->getPerLevelSorting() === 2);
        $this->assertTrue($sortableTreeNodeThirdChild->getPerLevelSorting() === 3);
        $this->assertTrue($sortableTreeNodeFourthChild->getPerLevelSorting() === 4);
        $this->assertTrue($sortableTreeNodeFifthChild->getPerLevelSorting() === 5);

        GenericSortableTreeNode::processNewSortingRequest($sortableTreeNodeSecondChild, 4);

        $this->assertTrue($sortableTreeNodeFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChild->getPerLevelSorting() === 4);
        $this->assertTrue($sortableTreeNodeThirdChild->getPerLevelSorting() === 2);
        $this->assertTrue($sortableTreeNodeFourthChild->getPerLevelSorting() === 3);
        $this->assertTrue($sortableTreeNodeFifthChild->getPerLevelSorting() === 5);
    }

    public function testProcessSortRequestMoveDown(): void
    {
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericSortableTreeNode(null, $payload);

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericSortableTreeNode(null, $payloadFirstChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $payloadSecondChild = $this->faker->domainName;
        $sortableTreeNodeSecondChild = new GenericSortableTreeNode(null, $payloadSecondChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $payloadThirdChild = $this->faker->domainName;
        $sortableTreeNodeThirdChild = new GenericSortableTreeNode(null, $payloadThirdChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeThirdChild);

        $payloadFourthChild = $this->faker->domainName;
        $sortableTreeNodeFourthChild = new GenericSortableTreeNode(null, $payloadFourthChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFourthChild);

        $payloadFifthChild = $this->faker->domainName;
        $sortableTreeNodeFifthChild = new GenericSortableTreeNode(null, $payloadFifthChild);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFifthChild);

        $this->assertTrue($sortableTreeNodeFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChild->getPerLevelSorting() === 2);
        $this->assertTrue($sortableTreeNodeThirdChild->getPerLevelSorting() === 3);
        $this->assertTrue($sortableTreeNodeFourthChild->getPerLevelSorting() === 4);
        $this->assertTrue($sortableTreeNodeFifthChild->getPerLevelSorting() === 5);

        GenericSortableTreeNode::processNewSortingRequest($sortableTreeNodeFourthChild, 2);

        $this->assertTrue($sortableTreeNodeFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChild->getPerLevelSorting() === 3);
        $this->assertTrue($sortableTreeNodeThirdChild->getPerLevelSorting() === 4);
        $this->assertTrue($sortableTreeNodeFourthChild->getPerLevelSorting() === 2);
        $this->assertTrue($sortableTreeNodeFifthChild->getPerLevelSorting() === 5);
    }

    public function testProcessMoveRequest(): void
    {
        $sortableTreeNode = new GenericSortableTreeNode(null, "root");

        $sortableTreeNodeFirstChild = new GenericSortableTreeNode(null, "1");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $sortableTreeNodeSecondChild = new GenericSortableTreeNode(null, "2");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $sortableTreeNodeSecondChildFirstChild = new GenericSortableTreeNode(null, "2.1");
        $sortableTreeNodeSecondChild->addChildWithSorting($sortableTreeNodeSecondChildFirstChild);

        $sortableTreeNodeSecondChildFirstChildFirstChild = new GenericSortableTreeNode(null, "2.1.1");
        $sortableTreeNodeSecondChildFirstChild->addChildWithSorting($sortableTreeNodeSecondChildFirstChildFirstChild);

        $sortableTreeNodeSecondChildFirstChildSecondChild = new GenericSortableTreeNode(null, "2.1.2");
        $sortableTreeNodeSecondChildFirstChild->addChildWithSorting($sortableTreeNodeSecondChildFirstChildSecondChild);

        $sortableTreeNodeSecondChildSecondChild = new GenericSortableTreeNode(null, "2.2");
        $sortableTreeNodeSecondChild->addChildWithSorting($sortableTreeNodeSecondChildSecondChild);

        $sortableTreeNodeThirdChild = new GenericSortableTreeNode(null, "3");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeThirdChild);

        $sortableTreeNodeThirdChildFirstChild = new GenericSortableTreeNode(null, "3.1");
        $sortableTreeNodeThirdChild->addChildWithSorting($sortableTreeNodeThirdChildFirstChild);

        $sortableTreeNodeFourthChild = new GenericSortableTreeNode(null, "4");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFourthChild);

        $sortableTreeNodeFifthChild = new GenericSortableTreeNode(null, "5");
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFifthChild);

        $this->assertTrue($sortableTreeNodeFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChild->getPerLevelSorting() === 2);
        $this->assertTrue($sortableTreeNodeSecondChildFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChildFirstChild->getLevel() === 2);
        $this->assertTrue($sortableTreeNodeSecondChildFirstChildFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChildFirstChildFirstChild->getLevel() === 3);
        $this->assertTrue($sortableTreeNodeSecondChildFirstChildSecondChild->getPerLevelSorting() === 2);
        $this->assertTrue($sortableTreeNodeSecondChildFirstChildSecondChild->getLevel() === 3);
        $this->assertTrue($sortableTreeNodeSecondChildSecondChild->getPerLevelSorting() === 2);
        $this->assertTrue($sortableTreeNodeSecondChildSecondChild->getLevel() === 2);
        $this->assertTrue($sortableTreeNodeThirdChild->getPerLevelSorting() === 3);
        $this->assertTrue($sortableTreeNodeThirdChildFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeThirdChildFirstChild->getLevel() === 2);
        $this->assertTrue($sortableTreeNodeFourthChild->getPerLevelSorting() === 4);
        $this->assertTrue($sortableTreeNodeFifthChild->getPerLevelSorting() === 5);

        $sortableTreeNode->processMoveRequest($sortableTreeNodeSecondChildFirstChild, $sortableTreeNode, 4);

        $this->assertTrue($sortableTreeNodeFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChild->getPerLevelSorting() === 2);
        $this->assertTrue($sortableTreeNodeSecondChildFirstChild->getPerLevelSorting() === 4);
        $this->assertTrue($sortableTreeNodeSecondChildFirstChild->getLevel() === 1);
        $this->assertTrue($sortableTreeNodeSecondChildFirstChildFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChildFirstChildFirstChild->getLevel() === 2);
        $this->assertTrue($sortableTreeNodeSecondChildFirstChildSecondChild->getPerLevelSorting() === 2);
        $this->assertTrue($sortableTreeNodeSecondChildFirstChildSecondChild->getLevel() === 2);
        $this->assertTrue($sortableTreeNodeSecondChildSecondChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChildSecondChild->getLevel() === 2);
        $this->assertTrue($sortableTreeNodeThirdChild->getPerLevelSorting() === 3);
        $this->assertTrue($sortableTreeNodeThirdChildFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeThirdChildFirstChild->getLevel() === 2);
        $this->assertTrue($sortableTreeNodeFourthChild->getPerLevelSorting() === 5);
        $this->assertTrue($sortableTreeNodeFifthChild->getPerLevelSorting() === 6);
    }
}

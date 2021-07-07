<?php

declare(strict_types=1);

namespace TreeNodes\Tests\Unit;

use Faker\Factory;
use Faker\Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TreeNodes\GenericSortableTreeNode;
use TreeNodes\GenericTypedPayloadSortableTreeNode;

final class GenericTypedPayloadSortableTreeNodeTest extends TestCase
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
        $sortableTreeNode = new GenericTypedPayloadSortableTreeNode(null, $payload, GenericTypedPayloadSortableTreeNode::PAYLOAD_TYPE_STRING, null);

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericTypedPayloadSortableTreeNode(null, $payloadFirstChild, GenericTypedPayloadSortableTreeNode::PAYLOAD_TYPE_STRING, null);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $payloadSecondChild = $this->faker->domainName;
        $sortableTreeNodeSecondChild = new GenericTypedPayloadSortableTreeNode(null, $payloadSecondChild, GenericTypedPayloadSortableTreeNode::PAYLOAD_TYPE_STRING, null);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $payloadThirdChild = $this->faker->domainName;
        $sortableTreeNodeThirdChild = new GenericTypedPayloadSortableTreeNode(null, $payloadThirdChild, GenericTypedPayloadSortableTreeNode::PAYLOAD_TYPE_STRING, null);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeThirdChild);

        $this->assertTrue($sortableTreeNodeFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChild->getPerLevelSorting() === 2);
        $this->assertTrue($sortableTreeNodeThirdChild->getPerLevelSorting() === 3);
    }

    public function testRemoveChildWithSorting(): void
    {
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericTypedPayloadSortableTreeNode(null, $payload, GenericTypedPayloadSortableTreeNode::PAYLOAD_TYPE_STRING, null);

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericTypedPayloadSortableTreeNode(null, $payloadFirstChild, GenericTypedPayloadSortableTreeNode::PAYLOAD_TYPE_STRING, null);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeFirstChild);

        $payloadSecondChild = $this->faker->domainName;
        $sortableTreeNodeSecondChild = new GenericTypedPayloadSortableTreeNode(null, $payloadSecondChild, GenericTypedPayloadSortableTreeNode::PAYLOAD_TYPE_STRING, null);
        $sortableTreeNode->addChildWithSorting($sortableTreeNodeSecondChild);

        $payloadThirdChild = $this->faker->domainName;
        $sortableTreeNodeThirdChild = new GenericTypedPayloadSortableTreeNode(null, $payloadThirdChild, GenericTypedPayloadSortableTreeNode::PAYLOAD_TYPE_STRING, null);
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

    public function testAddChildWithTypedPayloadAndWithSorting(): void
    {
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericTypedPayloadSortableTreeNode(null, $payload, GenericTypedPayloadSortableTreeNode::PAYLOAD_TYPE_STRING, null);

        $payloadFirstChild = $this->faker->domainName;
        $sortableTreeNodeFirstChild = new GenericTypedPayloadSortableTreeNode(null, $payloadFirstChild, GenericTypedPayloadSortableTreeNode::PAYLOAD_TYPE_STRING, null);
        $sortableTreeNode->addChildWithTypedPayloadAndWithSorting($sortableTreeNodeFirstChild);

        $payloadSecondChild = $this->faker->domainName;
        $sortableTreeNodeSecondChild = new GenericTypedPayloadSortableTreeNode(null, $payloadSecondChild, GenericTypedPayloadSortableTreeNode::PAYLOAD_TYPE_STRING, null);
        $sortableTreeNode->addChildWithTypedPayloadAndWithSorting($sortableTreeNodeSecondChild);

        $payloadThirdChild = $this->faker->domainName;
        $sortableTreeNodeThirdChild = new GenericTypedPayloadSortableTreeNode(null, $payloadThirdChild, GenericTypedPayloadSortableTreeNode::PAYLOAD_TYPE_STRING, null);
        $sortableTreeNode->addChildWithTypedPayloadAndWithSorting($sortableTreeNodeThirdChild);

        $this->assertTrue($sortableTreeNodeFirstChild->getPerLevelSorting() === 1);
        $this->assertTrue($sortableTreeNodeSecondChild->getPerLevelSorting() === 2);
        $this->assertTrue($sortableTreeNodeThirdChild->getPerLevelSorting() === 3);
    }

    public function testExceptionWrongTypeWhenAddChildWithTypedPayloadAndWithSortingWrong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $payload = $this->faker->domainName;
        $sortableTreeNode = new GenericTypedPayloadSortableTreeNode(null, $payload, GenericTypedPayloadSortableTreeNode::PAYLOAD_TYPE_STRING, null);

        $payloadFirstChild = 2456;
        $sortableTreeNodeFirstChild = new GenericTypedPayloadSortableTreeNode(null, $payloadFirstChild, GenericTypedPayloadSortableTreeNode::PAYLOAD_TYPE_INT, null);
        $sortableTreeNode->addChildWithTypedPayloadAndWithSorting($sortableTreeNodeFirstChild);
    }
}

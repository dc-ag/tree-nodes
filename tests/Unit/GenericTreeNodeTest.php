<?php

declare(strict_types=1);

namespace TreeNodes\Tests\Unit;

use Faker\Factory;
use Faker\Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TreeNodes\GenericTreeNode;

final class GenericTreeNodeTest extends TestCase
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

    public function testGetterId(): void
    {
        $payload = $this->faker->domainName;
        $id = $this->faker->uuid;
        $treeNode = new GenericTreeNode($id, $payload);

        $this->assertEquals($id, $treeNode->getId());
    }

    public function testGetterPayload(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);

        $this->assertTrue($payload === $treeNode->getPayload());
    }

    public function testGetterLevel(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);

        $this->assertTrue($treeNode->getLevel() === 0);
    }

    public function testGetterSetterParent(): void
    {
        $payloadParent = $this->faker->domainName;
        $treeNodeParent = new GenericTreeNode(null, $payloadParent);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);
        $treeNode->setParent($treeNodeParent);

        $this->assertTrue($treeNode->getParent() === $treeNodeParent);
    }

    public function testGetterChildren(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);

        $this->assertIsArray($treeNode->getChildren());
    }

    public function testGetterNoOfChildren(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode(null, $payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode(null, $payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode(null, $payloadChildThird);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);
        $treeNode->addChild($treeNodeChildFirst);
        $treeNode->addChild($treeNodeChildSecond);
        $treeNode->addChild($treeNodeChildThird);

        $this->assertTrue($treeNode->getNoOfChildren() === 3);
    }

    public function testAddChild(): void
    {
        $payloadChild = $this->faker->domainName;
        $treeNodeChild = new GenericTreeNode(null, $payloadChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);

        $this->assertTrue($treeNode->getNoOfChildren() === 0);

        $treeNode->addChild($treeNodeChild);

        $this->assertTrue($treeNode->getNoOfChildren() === 1);
    }

    public function testRemoveChild(): void
    {
        $payloadChild = $this->faker->domainName;
        $treeNodeChild = new GenericTreeNode(null, $payloadChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);

        $this->assertTrue($treeNode->getNoOfChildren() === 0);

        $treeNode->addChild($treeNodeChild);

        $this->assertTrue($treeNode->getNoOfChildren() === 1);

        $treeNode->removeChild($treeNodeChild);

        $this->assertTrue($treeNode->getNoOfChildren() === 0);
    }

    public function testGetterNoOfDescendants(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode(null, $payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode(null, $payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode(null, $payloadChildThird);

        $payloadChildThirdChild = $this->faker->domainName;
        $treeNodeChildThirdChild = new GenericTreeNode(null, $payloadChildThirdChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);
        $treeNode->addChild($treeNodeChildFirst);
        $treeNode->addChild($treeNodeChildSecond);
        $treeNode->addChild($treeNodeChildThird);

        $this->assertTrue($treeNode->getNoOfDescendants() === 4);
    }

    public function testGetterIsLeaf(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);

        $this->assertTrue($treeNode->isLeaf());
    }

    public function testGetterIsRoot(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);

        $this->assertTrue($treeNode->isRoot());
    }

    public function testGetterRootForTreeNode(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode(null, $payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode(null, $payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode(null, $payloadChildThird);

        $payloadChildThirdChild = $this->faker->domainName;
        $treeNodeChildThirdChild = new GenericTreeNode(null, $payloadChildThirdChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);
        $treeNode->addChild($treeNodeChildFirst);
        $treeNode->addChild($treeNodeChildSecond);
        $treeNode->addChild($treeNodeChildThird);

        $this->assertTrue($treeNodeChildThirdChild->getRootForTreeNode() === $treeNode);
    }

    public function testGetterHeight(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode(null, $payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode(null, $payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode(null, $payloadChildThird);

        $payloadChildThirdChild = $this->faker->domainName;
        $treeNodeChildThirdChild = new GenericTreeNode(null, $payloadChildThirdChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode(null, $payload);
        $treeNode->addChild($treeNodeChildFirst);
        $treeNode->addChild($treeNodeChildSecond);
        $treeNode->addChild($treeNodeChildThird);

        $this->assertTrue($treeNode->getHeight() === 2);
    }

    public function testGetterRootIdAddingSubTreeToRoot(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode(null, $payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode(null, $payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode(null, $payloadChildThird);

        $payloadChildThirdFirstChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChild = new GenericTreeNode(null, $payloadChildThirdFirstChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdFirstChild);

        $payloadChildThirdFirstChildFirstChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChildFirstChild = new GenericTreeNode(null, $payloadChildThirdFirstChildFirstChild);
        $treeNodeChildThirdFirstChild->addChild($treeNodeChildThirdFirstChildFirstChild);

        $payloadChildThirdFirstChildSecondChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChildSecondChild = new GenericTreeNode(null, $payloadChildThirdFirstChildSecondChild);
        $treeNodeChildThirdFirstChild->addChild($treeNodeChildThirdFirstChildSecondChild);

        $payloadChildThirdSecondChild = $this->faker->domainName;
        $treeNodeChildThirdSecondChild = new GenericTreeNode(null, $payloadChildThirdSecondChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdSecondChild);

        $payload = $this->faker->domainName;
        $rootTreeNode = new GenericTreeNode(null, $payload);
        $rootTreeNode->addChild($treeNodeChildFirst);
        $rootTreeNode->addChild($treeNodeChildSecond);
        $rootTreeNode->addChild($treeNodeChildThird);

        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildFirst->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildSecond->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThird->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdFirstChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdFirstChildFirstChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdFirstChildSecondChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdSecondChild->getRootId());
    }

    public function testGetterRootIdAddingChildsFromTopToBottom(): void
    {
        $payload = $this->faker->domainName;
        $rootTreeNode = new GenericTreeNode(null, $payload);

        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode(null, $payloadChildFirst);
        $rootTreeNode->addChild($treeNodeChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode(null, $payloadChildSecond);
        $rootTreeNode->addChild($treeNodeChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode(null, $payloadChildThird);
        $rootTreeNode->addChild($treeNodeChildThird);

        $payloadChildThirdFirstChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChild = new GenericTreeNode(null, $payloadChildThirdFirstChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdFirstChild);

        $payloadChildThirdFirstChildFirstChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChildFirstChild = new GenericTreeNode(null, $payloadChildThirdFirstChildFirstChild);
        $treeNodeChildThirdFirstChild->addChild($treeNodeChildThirdFirstChildFirstChild);

        $payloadChildThirdFirstChildSecondChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChildSecondChild = new GenericTreeNode(null, $payloadChildThirdFirstChildSecondChild);
        $treeNodeChildThirdFirstChild->addChild($treeNodeChildThirdFirstChildSecondChild);

        $payloadChildThirdSecondChild = $this->faker->domainName;
        $treeNodeChildThirdSecondChild = new GenericTreeNode(null, $payloadChildThirdSecondChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdSecondChild);

        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildFirst->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildSecond->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThird->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdFirstChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdFirstChildFirstChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdFirstChildSecondChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(), $treeNodeChildThirdSecondChild->getRootId());
    }
}

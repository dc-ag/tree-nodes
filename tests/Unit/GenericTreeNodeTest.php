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
        $treeNode = new GenericTreeNode($payload);

        $this->assertTrue(is_string($treeNode->getId()));
    }

    public function testGetterPayload(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode($payload);

        $this->assertTrue($payload === $treeNode->getPayload());
    }

    public function testGetterLevel(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode($payload);

        $this->assertTrue($treeNode->getLevel() === 0);
    }

    public function testGetterSetterParent(): void
    {
        $payloadParent = $this->faker->domainName;
        $treeNodeParent = new GenericTreeNode($payloadParent);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode($payload);
        $treeNode->setParent($treeNodeParent);

        $this->assertTrue($treeNode->getParent() === $treeNodeParent);
    }

    public function testGetterChildren(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode($payload);

        $this->assertIsArray($treeNode->getChildren());
    }

    public function testGetterNoOfChildren(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode($payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode($payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode($payloadChildThird);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode($payload);
        $treeNode->addChild($treeNodeChildFirst);
        $treeNode->addChild($treeNodeChildSecond);
        $treeNode->addChild($treeNodeChildThird);

        $this->assertTrue($treeNode->getNoOfChildren() === 3);
    }

    public function testAddChild(): void
    {
        $payloadChild = $this->faker->domainName;
        $treeNodeChild = new GenericTreeNode($payloadChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode($payload);

        $this->assertTrue($treeNode->getNoOfChildren() === 0);

        $treeNode->addChild($treeNodeChild);

        $this->assertTrue($treeNode->getNoOfChildren() === 1);
    }

    public function testRemoveChild(): void
    {
        $payloadChild = $this->faker->domainName;
        $treeNodeChild = new GenericTreeNode($payloadChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode($payload);

        $this->assertTrue($treeNode->getNoOfChildren() === 0);

        $treeNode->addChild($treeNodeChild);

        $this->assertTrue($treeNode->getNoOfChildren() === 1);

        $treeNode->removeChild($treeNodeChild);

        $this->assertTrue($treeNode->getNoOfChildren() === 0);
    }

    public function testGetterNoOfDescendants(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode($payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode($payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode($payloadChildThird);

        $payloadChildThirdChild = $this->faker->domainName;
        $treeNodeChildThirdChild = new GenericTreeNode($payloadChildThirdChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode($payload);
        $treeNode->addChild($treeNodeChildFirst);
        $treeNode->addChild($treeNodeChildSecond);
        $treeNode->addChild($treeNodeChildThird);

        $this->assertTrue($treeNode->getNoOfDescendants() === 4);
    }

    public function testGetterIsLeaf(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode($payload);

        $this->assertTrue($treeNode->isLeaf());
    }

    public function testGetterIsRoot(): void
    {
        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode($payload);

        $this->assertTrue($treeNode->isRoot());
    }

    public function testGetterRootForTreeNode(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode($payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode($payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode($payloadChildThird);

        $payloadChildThirdChild = $this->faker->domainName;
        $treeNodeChildThirdChild = new GenericTreeNode($payloadChildThirdChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode($payload);
        $treeNode->addChild($treeNodeChildFirst);
        $treeNode->addChild($treeNodeChildSecond);
        $treeNode->addChild($treeNodeChildThird);

        $this->assertTrue($treeNodeChildThirdChild->getRootForTreeNode() === $treeNode);
    }

    public function testGetterHeight(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode($payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode($payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode($payloadChildThird);

        $payloadChildThirdChild = $this->faker->domainName;
        $treeNodeChildThirdChild = new GenericTreeNode($payloadChildThirdChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdChild);

        $payload = $this->faker->domainName;
        $treeNode = new GenericTreeNode($payload);
        $treeNode->addChild($treeNodeChildFirst);
        $treeNode->addChild($treeNodeChildSecond);
        $treeNode->addChild($treeNodeChildThird);

        $this->assertTrue($treeNode->getHeight() === 2);
    }

    public function testGetterRootId(): void
    {
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode($payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode($payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode($payloadChildThird);

        $payloadChildThirdFirstChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChild = new GenericTreeNode($payloadChildThirdFirstChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdFirstChild);

        $payloadChildThirdFirstChildFirstChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChildFirstChild = new GenericTreeNode($payloadChildThirdFirstChildFirstChild);
        $treeNodeChildThirdFirstChild->addChild($treeNodeChildThirdFirstChildFirstChild);

        $payloadChildThirdFirstChildSecondChild = $this->faker->domainName;
        $treeNodeChildThirdFirstChildSecondChild = new GenericTreeNode($payloadChildThirdFirstChildSecondChild);
        $treeNodeChildThirdFirstChild->addChild($treeNodeChildThirdFirstChildSecondChild);

        $payloadChildThirdSecondChild = $this->faker->domainName;
        $treeNodeChildThirdSecondChild = new GenericTreeNode($payloadChildThirdSecondChild);
        $treeNodeChildThird->addChild($treeNodeChildThirdSecondChild);

        $payload = $this->faker->domainName;
        $rootTreeNode = new GenericTreeNode($payload);
        $rootTreeNode->addChild($treeNodeChildFirst);
        $rootTreeNode->addChild($treeNodeChildSecond);
        $rootTreeNode->addChild($treeNodeChildThird);

        $this->assertEquals($rootTreeNode->getRootId(),$treeNodeChildFirst->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(),$treeNodeChildSecond->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(),$treeNodeChildThird->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(),$treeNodeChildThirdFirstChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(),$treeNodeChildThirdFirstChildFirstChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(),$treeNodeChildThirdFirstChildSecondChild->getRootId());
        $this->assertEquals($rootTreeNode->getRootId(),$treeNodeChildThirdSecondChild->getRootId());
    }

    public function testExceptionWrongRootIdForNode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        
        $payloadChildFirst = $this->faker->domainName;
        $treeNodeChildFirst = new GenericTreeNode($payloadChildFirst);

        $payloadChildSecond = $this->faker->domainName;
        $treeNodeChildSecond = new GenericTreeNode($payloadChildSecond);

        $payloadChildThird = $this->faker->domainName;
        $treeNodeChildThird = new GenericTreeNode($payloadChildThird);

        $payload = $this->faker->domainName;
        $rootTreeNode = new GenericTreeNode($payload);
        $rootTreeNode->addChild($treeNodeChildFirst);
        $rootTreeNode->addChild($treeNodeChildSecond);
        $rootTreeNode->addChild($treeNodeChildThird);

        $treeNodeChildThird->setRootId($this->faker->uuid);
    }
}

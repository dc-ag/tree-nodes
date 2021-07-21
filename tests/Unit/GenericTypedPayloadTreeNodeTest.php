<?php

declare(strict_types=1);

namespace TreeNodes\Tests\Unit;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use TreeNodes\GenericTypedPayloadTreeNode;
use TreeNodes\TypedPayloadTreeNode;
use InvalidArgumentException;
use stdClass;

/**
 * @covers TreeNodes\GenericTypedPayloadTreeNode
 * @uses TreeNodes\GenericIdGenerator
 * @uses TreeNodes\GenericTreeNode
 */
final class GenericTypedPayloadTreeNodeTest extends TestCase
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

    public function testIsPayloadTypeValid(): void
    {
        $this->assertFalse(GenericTypedPayloadTreeNode::isPayloadTypeValid($this->faker->firstName));
        $this->assertTrue(GenericTypedPayloadTreeNode::isPayloadTypeValid(TypedPayloadTreeNode::PAYLOAD_TYPE_STRING));
    }

    public function testIsPayloadObjectFQDNValid(): void
    {
        $this->assertFalse(GenericTypedPayloadTreeNode::isPayloadObjectFQDNValid($this->faker->domainName));
        $this->assertTrue(GenericTypedPayloadTreeNode::isPayloadObjectFQDNValid(self::class));
    }

    public function testExceptionInvalidPayloadTypeConstructor(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $payload = $this->faker->domainName;
        new GenericTypedPayloadTreeNode(null, $payload, $this->faker->name, null);
    }

    public function testExceptionPayloadObjectFQDNCannotBeNullConstructor(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $payload = $this->faker->domainName;
        new GenericTypedPayloadTreeNode(null, $payload, TypedPayloadTreeNode::PAYLOAD_TYPE_OBJECT_WITH_FQDN, null);
    }

    public function testExceptionPayloadObjectFQDNNotFoundConstructor(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $payload = $this->faker->domainName;
        new GenericTypedPayloadTreeNode(null, $payload, TypedPayloadTreeNode::PAYLOAD_TYPE_OBJECT_WITH_FQDN, $this->faker->domainName);
    }

    public function testExceptionPayloadInvalidConstructor(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $payload = $this->faker->domainName;
        new GenericTypedPayloadTreeNode(null, $payload, TypedPayloadTreeNode::PAYLOAD_TYPE_BOOL, null);
    }

    public function testGetterPayloadType(): void
    {
        $payload = $this->faker->domainName;
        $typedPayloadTreeNode = new GenericTypedPayloadTreeNode(null, $payload, TypedPayloadTreeNode::PAYLOAD_TYPE_STRING, null);

        $this->assertTrue($typedPayloadTreeNode->getPayloadType() === TypedPayloadTreeNode::PAYLOAD_TYPE_STRING);
    }

    public function testGetterPayloadObjectFQDN(): void
    {
        $payload = $this->faker->domainName;
        $typedPayloadTreeNode = new GenericTypedPayloadTreeNode(null, $payload, TypedPayloadTreeNode::PAYLOAD_TYPE_STRING, null);

        $this->assertTrue($typedPayloadTreeNode->getPayloadObjectFQDN() === null);

        $payload = new stdClass();
        $typedPayloadTreeNode = new GenericTypedPayloadTreeNode(null, $payload, TypedPayloadTreeNode::PAYLOAD_TYPE_OBJECT_WITH_FQDN, stdClass::class);


        $this->assertTrue($typedPayloadTreeNode->getPayloadObjectFQDN() === stdClass::class);
    }

    public function testAddChildWithTypedPayload(): void
    {
        $payloadChild = $this->faker->domainName;
        $typedPayloadTreeNodeChild = new GenericTypedPayloadTreeNode(null, $payloadChild, TypedPayloadTreeNode::PAYLOAD_TYPE_STRING, null);

        $payload = $this->faker->domainName;
        $typedPayloadTreeNode = new GenericTypedPayloadTreeNode(null, $payload, TypedPayloadTreeNode::PAYLOAD_TYPE_STRING, null);

        $this->assertTrue(count($typedPayloadTreeNode->getChildren()) === 0);
        $typedPayloadTreeNode->addChildWithTypedPayload($typedPayloadTreeNodeChild);
        $this->assertTrue(count($typedPayloadTreeNode->getChildren()) === 1);

        $payloadChildSecond = $this->faker->boolean;
        $typedPayloadTreeNodeChildSecond = new GenericTypedPayloadTreeNode(null, $payloadChildSecond, TypedPayloadTreeNode::PAYLOAD_TYPE_BOOL, null);

        $this->expectException(InvalidArgumentException::class);
        $typedPayloadTreeNode->addChildWithTypedPayload($typedPayloadTreeNodeChildSecond);
    }
}

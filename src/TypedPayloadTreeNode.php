<?php

declare(strict_types=1);

namespace TreeNodes;

interface TypedPayloadTreeNode extends TreeNode
{
    public const PAYLOAD_TYPE_INT = 'payloadTypeInt';
    public const PAYLOAD_TYPE_FLOAT = 'payloadTypeFloat';
    public const PAYLOAD_TYPE_STRING = 'payloadTypeString';
    public const PAYLOAD_TYPE_BOOL = 'payloadTypeBool';
    public const PAYLOAD_TYPE_ARRAY = 'payloadTypeArray';
    public const PAYLOAD_TYPE_OBJECT = 'payloadTypeObject';
    public const PAYLOAD_TYPE_OBJECT_WITH_FQDN = 'payloadTypeObjectWithFQDN';

    public const VALID_PAYLOAD_TYPES = [
        self::PAYLOAD_TYPE_INT => self::PAYLOAD_TYPE_INT,
        self::PAYLOAD_TYPE_FLOAT => self::PAYLOAD_TYPE_FLOAT,
        self::PAYLOAD_TYPE_STRING => self::PAYLOAD_TYPE_STRING,
        self::PAYLOAD_TYPE_BOOL => self::PAYLOAD_TYPE_BOOL,
        self::PAYLOAD_TYPE_ARRAY => self::PAYLOAD_TYPE_ARRAY,
        self::PAYLOAD_TYPE_OBJECT => self::PAYLOAD_TYPE_OBJECT,
        self::PAYLOAD_TYPE_OBJECT_WITH_FQDN => self::PAYLOAD_TYPE_OBJECT_WITH_FQDN,
    ];

    /**
     * @return string
     */
    public function getPayloadType(): string;

    /**
     * @return string|null
     */
    public function getPayloadObjectFQDN(): ?string;

    /**
     * @param TypedPayloadTreeNode $childWithTypedPayload
     */
    public function addChildWithTypedPayload(TypedPayloadTreeNode $childWithTypedPayload): void;
}

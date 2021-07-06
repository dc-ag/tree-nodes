<?php

declare(strict_types=1);

namespace TreeNodes;

use InvalidArgumentException;

class GenericTypedPayloadTreeNode extends GenericTreeNode implements TypedPayloadTreeNode
{
    private string $payloadType;
    private ?string $payloadObjectFQDN = null;

    /**
     *
     * @param [type] $payload
     * @param string $payloadType
     * @param string|null $payloadObjectFQDN
     * @param IdGenerator|null $idGenerator
     */
    public function __construct($payload, string $payloadType, ?string $payloadObjectFQDN, ?IdGenerator $idGenerator = null)
    {
        if (!self::isPayloadTypeValid($payloadType)) {
            throw new InvalidArgumentException("Payload type [$payloadType] is not valid");
        }

        if ($payloadType === self::PAYLOAD_TYPE_OBJECT_WITH_FQDN && $payloadObjectFQDN === null) {
            throw new InvalidArgumentException('Payload object FQDN cannot be null');
        }

        if ($payloadType === self::PAYLOAD_TYPE_OBJECT_WITH_FQDN && !self::isPayloadObjectFQDNValid(
            $payloadObjectFQDN
        )) {
            throw new InvalidArgumentException("Could not find class for payload object FQDN [$payloadObjectFQDN]");
        }

        if (!self::isTypedPayloadValid($payload, $payloadType, $payloadObjectFQDN)) {
            throw new InvalidArgumentException(
                "Payload is invalid, payload does not match given payload type [$payloadType]"
            );
        }

        parent::__construct($payload, $idGenerator);
        $this->payloadType = $payloadType;
        $this->payloadObjectFQDN = $payloadObjectFQDN;
    }

    /**
     * @param string $payloadType
     * @return bool
     */
    public static function isPayloadTypeValid(string $payloadType): bool
    {
        return array_key_exists($payloadType, self::VALID_PAYLOAD_TYPES);
    }

    /**
     * @param string $payloadObjectFQDN
     * @return bool
     */
    public static function isPayloadObjectFQDNValid(string $payloadObjectFQDN): bool
    {
        return class_exists($payloadObjectFQDN);
    }

    /**
     * @return string
     */
    public function getPayloadType(): string
    {
        return $this->payloadType;
    }

    /**
     * @return string|null
     */
    public function getPayloadObjectFQDN(): ?string
    {
        return $this->payloadObjectFQDN;
    }

    /**
     * @param $childWithTypedPayload
     */
    public function addChildWithTypedPayload(TypedPayloadTreeNode $childWithTypedPayload): void
    {
        if (self::isTypedPayloadValid(
            $childWithTypedPayload->getPayload(),
            $this->getPayloadType(),
            $this->getPayloadObjectFQDN()
        )) {
            parent::addChild($childWithTypedPayload);
        } else {
            throw new InvalidArgumentException(
                "Could not add child with typed payload, type has to be [{$this->getPayloadType()}]"
            );
        }
    }

    /**
     * @param $payload
     * @param string $payloadType
     * @param string|null $payloadObjectFQDN
     * @return bool
     */
    public static function isTypedPayloadValid($payload, string $payloadType, ?string $payloadObjectFQDN): bool
    {
        switch ($payloadType) {
            case self::PAYLOAD_TYPE_INT:
                return is_int($payload);
                break;
            case self::PAYLOAD_TYPE_FLOAT:
                return is_float($payload);
                break;
            case self::PAYLOAD_TYPE_STRING:
                return is_string($payload);
                break;
            case self::PAYLOAD_TYPE_BOOL:
                return is_bool($payload);
                break;
            case self::PAYLOAD_TYPE_ARRAY:
                return is_array($payload);
                break;
            case self::PAYLOAD_TYPE_OBJECT:
                return is_object($payload);
                break;
            case self::PAYLOAD_TYPE_OBJECT_WITH_FQDN:
                if ($payloadObjectFQDN !== null) {
                    return $payload instanceof $payloadObjectFQDN;
                }
                break;
            default:
                return false;
        }

        return false;
    }
}

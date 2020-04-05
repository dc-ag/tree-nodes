<?php

namespace TreeNodes\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TreeNodes\GenericIdGenerator;

final class GenericIdGeneratorTest extends TestCase
{
    public function testGetterId(): void
    {
        $idGenerator = new GenericIdGenerator();
        $id = $idGenerator->getId();

        $this->assertTrue(is_string($id));
        $this->assertTrue(GenericIdGenerator::isUUIDv4Valid($id));
    }

    public function testIsValidFunction(): void
    {
        $idGenerator = new GenericIdGenerator();
        $id = $idGenerator->getId();

        $this->assertTrue(GenericIdGenerator::isUUIDv4Valid($id));
        $this->assertTrue(!GenericIdGenerator::isUUIDv4Valid('Bla'));
    }
}

<?php

declare(strict_types=1);

namespace Tests\Data;

use PHPUnit\Framework\TestCase;
use TempMailIo\TempMailPhp\Email\Data\Request\DomainType;

class DataTest extends TestCase
{
    public function testToArray()
    {
        $data = new ExtendedData();
        $data->fromArray(['key1' => 'value1', 'key2' => 'value2']);

        $expected = ['key1' => 'value1', 'key2' => 'value2'];
        $this->assertEquals($expected, $data->toArray());
    }

    public function testFromArray()
    {
        $data = new ExtendedData();
        $incomingData = ['key1' => 'value1', 'key2' => 'value2'];
        $data->fromArray($incomingData);

        $this->assertEquals('value1', $data->key1);
        $this->assertEquals('value2', $data->key2);
    }

    public function testFromArrayEmpty()
    {
        $data = new ExtendedData();
        $data->fromArray([]);

        $this->assertNull($data->key1);
        $this->assertNull($data->key2);
    }

    public function testCreate()
    {
        $data = ExtendedData::create();

        $incomingData = ['key1' => 'value1', 'key2' => 'value2'];
        $data->fromArray($incomingData);

        $this->assertEquals('value1', $data->key1);
        $this->assertEquals('value2', $data->key2);
    }

    public function testCreateFromArrayNestedObjects()
    {
        $data = ExtendedDataWithNestedObject::create();

        $incomingData = [
            'key3' => 'value3',
            'key4' => 'value4',
            'extended_data' => [
                'key1' => 'value1',
                'key2' => 'value2'
            ],
        ];
        $data->fromArray($incomingData);

        $this->assertEquals('value1', $data->extendedData->key1);
        $this->assertEquals('value2', $data->extendedData->key2);
        $this->assertEquals('value3', $data->key3);
        $this->assertEquals('value4', $data->key4);
    }

    public function testToArrayNestedObjects()
    {
        $data = ExtendedDataWithNestedObject::create();

        $incomingData = [
            'key3' => 'value3',
            'key4' => 'value4',
            'extended_data' => [
                'key1' => 'value1',
                'key2' => 'value2'
            ],
        ];
        $data->fromArray($incomingData);

        $this->assertEquals($incomingData, $data->toArray());
    }

    public function testFromArrayWithNestedListOfObjects()
    {
        $data = ExtendedDataWithNestedListOfObjects::create();

        $incomingData = [
            'key1' => 'value1',
            'key2' => 'value2',
            'data' => [
                ['key1' => 'value1', 'key2' => 'value2'],
                ['key1' => 'value3', 'key2' => 'value4'],
            ],
        ];
        $data->fromArray($incomingData);

        $this->assertEquals($incomingData, $data->toArray());
        $this->assertEquals(['key1' => 'value1', 'key2' => 'value2'], $data->data[0]->toArray());
    }

    public function testFromArrayWithEnum()
    {
        $data = new ExtendedDataWithEnum();
        $data->fromArray(['key1' => 'value1', 'key2' => 'value2', 'domain_type' => DomainType::PUBLIC]);

        $this->assertEquals('value1', $data->key1);
        $this->assertEquals('value2', $data->key2);
        $this->assertEquals(DomainType::PUBLIC, $data->domainType);
    }

    public function testToArrayWithEnum()
    {
        $data = new ExtendedDataWithEnum();
        $incomingData = ['key1' => 'value1', 'key2' => 'value2', 'domain_type' => DomainType::PUBLIC];
        $data->fromArray($incomingData);

        $this->assertEquals($incomingData, $data->toArray());
    }
}

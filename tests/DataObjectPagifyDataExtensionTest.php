<?php

/**
 * Class DataObjectPagifyDataExtensionTest
 */
class DataObjectPagifyDataExtensionTest extends SapphireTest
{

    /**
     * @var array
     */
    protected $extraDataObjects = [
        'URLSegmentDataObject',
    ];

    /**
     *
     */
    public function testPagifyImplemented()
    {
        $newObject = URLSegmentDataObject::create();
        $newObject->write();

        $dbFields = DataObject::database_fields('URLSegmentDataObject');

        $this->assertTrue($newObject->hasExtension('DataObjectPagifyDataExtension'));
        $this->assertTrue(array_key_exists('URLSegment', $dbFields));
        $this->assertTrue(array_key_exists('Title', $dbFields));
        $this->assertTrue(array_key_exists('MetaTitle', $dbFields));
        $this->assertTrue(array_key_exists('MetaDescription', $dbFields));
        $this->assertTrue(array_key_exists('Content', $dbFields));

    }

}

/**
 * Class URLSegmentDataObject
 */
class URLSegmentDataObject extends DataObject implements TestOnly
{

    /**
     * @var array
     */
    private static $extensions = [
        'DataObjectPagifyDataExtension',
    ];

}
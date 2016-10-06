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

    /**
     * todo: expand on testing that the expected fields are present
     */
    public function testCMSFields()
    {
        $fields = [
            'URLSegment',
            'Status',
            'Version',
            'MetaTitle',
            'MetaDescription',
        ];

        $extension = new DataObjectPagifyDataExtension();

        $object = URLSegmentDataObject::create();
        $object->write();

        $objectFields = $object->getCMSFields();
        $extension->updateCMSFields($objectFields);

        $this->assertInstanceOf('FieldList', $objectFields);
    }

    /**
     *
     */
    public function testAutoGenerateURL()
    {

        $newObject = URLSegmentDataObject::create();
        $newObject->Title = 'My Unique Title';
        $newObject->write();
        $this->assertEquals('my-unique-title', URLSegmentDataObject::get()->byID($newObject->ID)->URLSegment);

    }

    /**
     *
     */
    public function testIterateDuplicateURLSegment()
    {

        $newObject = URLSegmentDataObject::create();
        $newObject->Title = 'New Object';
        $newObject->write();

        $this->assertEquals('new-object', URLSegmentDataObject::get()->byID($newObject->ID)->URLSegment);

        $nextObject = URLSegmentDataObject::create();
        $nextObject->Title = 'New Object';
        $nextObject->write();

        $this->assertEquals('new-object-2', URLSegmentDataObject::get()->byID($nextObject->ID)->URLSegment);

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
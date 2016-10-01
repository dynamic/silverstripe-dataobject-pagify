<?php

/**
 * Class DataObjectPagifyDataExtension
 */
class DataObjectPagifyDataExtension extends DataExtension
{

    /**
     * @var array
     */
    private static $db = [
        'URLSegment' => 'Varchar(255)',
        'Title' => 'Varchar(255)',
        'MetaTitle' => 'Varchar(255)',
        'MetaDescription' => 'Varchar(255)',
        'Content' => 'HTMLText',
    ];

    /**
     * @var array
     */
    private static $summary_fields = [
        'Title' => 'Title',
        'URLSegment' => 'URLSegment',
    ];

    /**
     * @var array
     */
    private static $indexes = [
        "URLSegment" => [
            'type' => 'unique',
            'value' => 'URLSegment',
        ],
    ];

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->removeByName([
            'URLSegment',
            'Status',
            'Version',
            'MetaTitle',
            'MetaDescription',
        ]);

        $fields->addFieldToTab('Root.Main', new TextField('Title'));
        if ($this->owner->ID) {
            $urlsegment = SiteTreeURLSegmentField::create("URLSegment", $this->owner->fieldLabel('URLSegment'));

            $prefix = Director::absoluteBaseURL() . 'listing-page/show/';
            $urlsegment->setURLPrefix($prefix);

            $helpText = _t('SiteTreeURLSegmentField.HelpChars', ' Special characters are automatically converted or removed.');
            $urlsegment->setHelpText($helpText);
            $fields->addFieldToTab('Root.Main', $urlsegment);
        }
        $fields->addFieldToTab('Root.Main', HtmlEditorField::create('Content'));

        $fields->addFieldToTab('Root.Main', ToggleCompositeField::create('Metadata', 'Metadata',
            array(
                TextField::create("MetaTitle", $this->owner->fieldLabel('MetaTitle')),
                TextareaField::create("MetaDescription", $this->owner->fieldLabel('MetaDescription')),
            )
        ));

    }

    /**
     * Produce the correct breadcrumb trail for use on the DataObject Item Page
     *
     * @param int $maxDepth
     * @param bool $unlinked
     * @param bool $stopAtPageType
     * @param bool $showHidden
     * @return HTMLText
     */
    public function Breadcrumbs($maxDepth = 20, $unlinked = false, $stopAtPageType = false, $showHidden = false)
    {
        $page = Controller::curr();
        $pages = array();

        $pages[] = $this;

        while (
            $page
            && (!$maxDepth || count($pages) < $maxDepth)
            && (!$stopAtPageType || $page->ClassName != $stopAtPageType)
        ) {
            if ($showHidden || $page->ShowInMenus || ($page->ID == $this->owner->ID)) {
                $pages[] = $page;
            }

            $page = $page->Parent;
        }

        $template = new SSViewer('BreadcrumbsTemplate');

        return $template->process(
            $this->customise(
                new ArrayData([
                    'Pages' => new ArrayList(array_reverse($pages))
                ])
            )
        );
    }

}
<?php

/**
 * Main Linkage object setup.
 *
 * Sets up the main Linkage object and create editable fields in the CMS.
 *
 * @author S.Dwayne Pivac, OMI Ltd.
 *
 */
class Linkage extends DataObject
{
    private static $db = array(
        'Title' => 'Text',
        'URL' => 'Text',
        'External' => 'Boolean',
        'Sorder' => 'Varchar',
    );
    private static $summary_fields = array('HTMLDescription');
    static $singular_name = 'Link';
    static $plural_name = 'Links';
    public static $default_sort = 'Sorder ASC';
    function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['HTMLDescription'] = 'Links';
        return $labels;
    }
    private static $casting = array('HTMLDescription' => 'HTMLText');
    function HTMLDescription()
    {
        return $this->getHTMLDescription();
    }
    public function getHTMLDescription()
    {
        $ShowExt = 'self';
        if ($this->External) {
            $ShowExt = 'blank';
        }
        $data = new ArrayData(array(
            'ID' => $this->ID,
            'Title' => $this->Title,
            'URL' => $this->URL,
            'ShowExt' => $ShowExt,
        ));
        $output = HTMLText::create();
        $output->setValue($data->renderWith('Links'));
        return $output;
    }
    public function getCMSfields(){
        $f=FieldList::create(TabSet::create('Root'));
        $f->addFieldToTab('Root.Main', TextField::create('Title', 'Link/button name'));
        $f->addFieldToTab('Root.Main', TextField::create('URL', 'URL/Link'));
        $f->addFieldToTab('Root.Main', CheckboxField::create('External', 'External linkage?')->setDescription('Open in a new window?'));
        $f->addFieldToTab('Root.Main', TextField::create('Sorder', 'Sort Order')->setDescription('Links are sorted by this field using alphanumeric sorting. You can use numbers or letters.'));
        return $f;
    }
}


/**
 * Create Linkage menu.
 *
 * Create the Linkage link on the 'Left' hand side of the ModelAdmin.
 *
 * @author S.Dwayne Pivac, OMI Ltd.
 *
 */
class LinkageAdmin extends ModelAdmin
{
    private static $menu_title = 'Linkage';
    private static $url_segment = 'linkage';
    private static $managed_models = array('Linkage');
    private static $menu_icon = 'linkage/img/linkage.gif';
    private static $page_length = 50;
    private static $menu_priority = 100;
    private static $url_priority = 30;
    public function init()
    {
        parent::init();
        Requirements::css('linkage/css/linkage.css');
    }
    public function getEditForm($id=null, $fields=null)
    {
        $gridFieldTitle = 'Your Linkage';
        $listField=GridField::create(
            $this->sanitiseClassName($this->modelClass), $gridFieldTitle, $this->getList(),
            $fieldConfig=GridFieldConfig_RecordViewer::create($this->stat('page_length'))
                ->removeComponentsByType('GridFieldPageCount')
                ->removeComponentsByType('GridFieldPaginator')
                ->removeComponentsByType('GridFieldSortableHeader')
                ->removeComponentsByType('GridFieldViewButton')
                ->removeComponentsByType('GridFieldToolbarHeader')
                ->addComponent(new GridFieldEditLinkage())
                ->addComponent(new GridFieldAddNewButton())
            );
        return CMSForm::create(
            $this, 
            'EditForm', 
            new FieldList($listField), 
            new FieldList() 
        )->setHTMLID('Form_EditForm')->addExtraClass('cms-edit-form cms-panel-padded center');
    }
}


/**
 * Linkage Edit Buttons.
 *
 * Custom Edit Buttons to avoid the default behaviour of linking via the GridField.
 *
 * @author S.Dwayne Pivac, OMI Ltd.
 *
 */
class GridFieldEditLinkage implements GridField_ColumnProvider
{

    public function augmentColumns($gridField, &$columns) {
        if(!in_array('Actions', $columns)) {
            $columns[] = 'Actions';
        }
    }

    public function getColumnAttributes($gridField, $record, $columnName) {
        return array('class' => 'col-buttons');
    }

    public function getColumnMetadata($gridField, $columnName) {
        if($columnName == 'Actions') {
            return array('title' => '');
        }
    }

    public function getColumnsHandled($gridField) {
        return array('Actions');
    }

    public function getColumnContent($gridField, $record, $columnName) {
        $data = new ArrayData(array(
            'Link' => Controller::join_links($gridField->Link('item'), $record->ID, 'edit')
        ));
        return $data->renderWith('LinkageEditButton');
    }

}

#~/linkage/code/Linkage.php


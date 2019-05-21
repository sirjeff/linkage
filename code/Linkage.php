<?php
class Linkage extends DataObject{
 private static $db=array(
  "Title"=>"Text",
  "URL"=>"Text",
  "External"=>"Boolean",
  "Sorder"=>"Varchar",
 );
 private static $summary_fields=array("HTMLDescription",);
 static $singular_name="Link";
 static $plural_name="Links";
 public static $default_sort="Sorder ASC";
 function fieldLabels($includerelations=true){
  $labels=parent::fieldLabels($includerelations);
  $labels["HTMLDescription"]="Links";
  return $labels;
 }
 private static $casting=array("HTMLDescription"=>"HTMLText");
 function HTMLDescription(){return $this->getHTMLDescription();}
 public function getHTMLDescription(){#2Do: move all HTML into a template
  $Target="";
  $ShowExt="";
  if($this->External){
   $Target=" target='_blank'";
   $ShowExt="<img src='linkage/img/external.gif'>";
  }
  $data=new ArrayData(array(
   "ID"=>$this->ID,
   "Title"=>$this->Title,
   "URL"=>$this->URL,
   "Target"=>$Target,
   "ShowExt"=>$ShowExt,
  ));
  $output=HTMLText::create();
  $output->setValue($data->renderWith("Links"));
  return $output;
 }
 public function getCMSfields(){
  $f=FieldList::create(TabSet::create("Root"));
  $f->addFieldToTab("Root.Main",TextField::create("Title","Link/button name"));
  $f->addFieldToTab("Root.Main",TextField::create("URL","URL/Link"));
  $f->addFieldToTab("Root.Main",CheckboxField::create("External","External linkage?")->setDescription("Open in a new window?"));
  $f->addFieldToTab("Root.Main",TextField::create("Sorder","Sort Order")->setDescription("Links are sorted by this field using alphanumeric sorting. You can use numbers or letters."));
  return $f;
 }
}
class LinkageAdmin extends ModelAdmin{
 private static $menu_title="Linkage";
 private static $url_segment="linkage";
 private static $managed_models=array("Linkage");
 private static $menu_icon="linkage/img/linkage.gif";
 private static $page_length=50;
 private static $menu_priority=100;
 private static $url_priority=30;
 public function init(){
  parent::init();
  Requirements::css("linkage/css/linkage.css");
 }
 public function getEditForm($id=null,$fields=null){
  $gridFieldTitle="Your Linkage";
  $listField=GridField::create(
   $this->sanitiseClassName($this->modelClass),$gridFieldTitle,$this->getList(),
   $fieldConfig=GridFieldConfig_RecordViewer::create($this->stat("page_length"))
    ->removeComponentsByType("GridFieldPageCount")
    ->removeComponentsByType("GridFieldPaginator")
    ->removeComponentsByType("GridFieldSortableHeader")
    ->removeComponentsByType("GridFieldViewButton")
   )->setDescription("<div class='addlinkage'><a href='admin/linkage/Linkage/EditForm/field/Linkage/item/new' class='action action-detail ss-ui-action-constructive ss-ui-button ui-button ui-widget ui-state-default ui-corner-all new new-link ui-button-text-icon-primary' data-icon='add' role='button' aria-disabled='false'><span class='ui-button-icon-primary ui-icon btn-icon-add'></span><span class='ui-button-text'>Add Link</span></a></div>");
  return CMSForm::create($this,"EditForm",new FieldList($listField),new FieldList())->setHTMLID("Form_EditForm")->addExtraClass("cms-edit-form cms-panel-padded center");
 }
}
#
#Linkage/code/Linkage.php

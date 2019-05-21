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
 public function getHTMLDescription(){
  $Target="";
  $ShowExt="";
  if($this->External){
   $Target=" target='_blank'";
   $ShowExt="<img src='linkage/img/external.gif'>";
  }
  $output=HTMLText::create(); 
  $output->setValue("<h4 class=linkage><a class=editlink href='/admin/linkage/Linkage/EditForm/field/Linkage/item/".$this->ID."/edit'><img src='linkage/img/edit.gif'></a><a class=link href='".$this->URL."'$Target>".$this->Title."$ShowExt</a></h4>"); 
  return $output;
 }


 public function getCMSfields(){
  $f=FieldList::create(TabSet::create("Root"));
  $f->addFieldToTab("Root.Main",TextField::create("Title","Link/button name"));
  $f->addFieldToTab("Root.Main",TextField::create("URL","URL/Link"));
  $f->addFieldToTab("Root.Main",CheckboxField::create("External","External linkage?")->setDescription("Open in a new window?"));
  $f->addFieldToTab("Root.Main",TextField::create("Sorder","Sort Order"));
  return $f;
 }
}



class LinkageAdmin extends ModelAdmin{
 private static $menu_title="Linkage";
 private static $url_segment="linkage";
 private static $managed_models=array("Linkage");
 private static $menu_icon="linkage/img/linkage.gif";
 private static $menu_priority=100;
 private static $url_priority=30;
 private static $page_length=50;
 public function init() {
  parent::init();
  Requirements::css("linkage/css/linkage.css");
 }
 public function getEditForm($id=null,$fields=null){
  $gridFieldTitle="Your Linkage";
  $listField=GridField::create(
   $this->sanitiseClassName($this->modelClass),$gridFieldTitle,$this->getList(),
   $fieldConfig=GridFieldConfig_RecordViewer::create($this->stat("page_length"))
     ->removeComponentsByType("GridFieldDeleteAction")
     ->removeComponentsByType("GridFieldPageCount")
     ->removeComponentsByType("GridFieldPaginator")
     ->removeComponentsByType("GridFieldAddNewButton")
     ->removeComponentsByType("GridFieldFilterHeader")
     ->removeComponentsByType("GridFieldSortableHeader")
     ->removeComponentsByType("GridFieldViewButton")
     ->removeComponentsByType("SaveButton")

   )->setDescription("");
  return CMSForm::create($this,"EditForm",new FieldList($listField),new FieldList())->setHTMLID("Form_EditForm")->addExtraClass("cms-edit-form cms-panel-padded center");
 }
}
#
#Linkage/code/Linkage.php

<?php
class Linkage extends DataObject{
 private static $db=array(
  "Title"=>"Text",
  "URL"=>"Text",
  "External"=>"Boolean",
  "Sorder"=>"Varchar",
 );
 private static $summary_fields=array(
  "HTMLID",
  "HTMLDescription",
 );

 private static $casting=array("HTMLID"=>"HTMLText","HTMLDescription"=>"HTMLText");
 function HTMLDescription(){return $this->getHTMLDescription();}
 function HTMLID(){return $this->getHTMLID();}
 public function getHTMLID(){
  $output=HTMLText::create(); 
  $output->setValue("<h4 class=linkage><a href='/admin/Linkage/Linkage/EditForm/field/Linkage/item/".$this->ID."/edit'>".$this->ID."</a></h4>"); 
  return $output;
 }
 public function getHTMLDescription(){
  $Target="";
  $ShowExt="";
  if($this->External){
   $Target=" target='_blank'";
   $ShowExt="<img src='linkage/img/external.png'>";
  }
  $output=HTMLText::create(); 
  $output->setValue("<h4 class=linkage><a href='".$this->URL."'$Target>".$this->Title."$ShowExt</a></h4>"); 
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
 private static $url_segment="Linkage";
 private static $managed_models=array("Linkage");
 private static $menu_icon="linkage/img/linkage.png";
 private static $menu_priority=100;
 private static $url_priority=30;
 private static $page_length=50;
 public function init() {
  parent::init();
  Requirements::css("linkage/css/linkage.css");
 }
 public function getEditForm($id=null,$fields=null){
  $gridFieldTitle="Links";
  $listField=GridField::create(
   $this->sanitiseClassName($this->modelClass),$gridFieldTitle,$this->getList(),
   $fieldConfig=GridFieldConfig_RecordViewer::create($this->stat("page_length"))
    ->removeComponentsByType("GridFieldDeleteAction")
    ->removeComponentsByType("GridFieldPageCount")
    ->removeComponentsByType("GridFieldPaginator")
   )->setDescription("");
  return CMSForm::create($this,"EditForm",new FieldList($listField),new FieldList())->setHTMLID("Form_EditForm")->addExtraClass("cms-edit-form cms-panel-padded center");
 }
}
#
#Linkage/code/Linkage.php

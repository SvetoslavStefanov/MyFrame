<?
class InfoPage extends ActiveRecord {
     const INFOPAGE_STATUS_HIDDEN = 0;
     const INFOPAGE_STATUS_VISIBLE = 1;
     const INFOPAGE_STATUS_INACTIVE = 2;

     static $table = 'infopage';
      static $columns = array(
         'created',
         'updated',
         'title',
         'content',
         'status',
         'seo_description',
         'seo_keywords'
         );

      protected function validate(){
           $this->content = htmlentities($this->content, null, "UTF-8");
      }
}
<?

class admin_InfoPage extends ActiveRecord
{

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

    protected function validate ()
    {
        if ($this->isNew()) {
            $this->created = time();
        } else {
            $this->updated = time();
        }

        //$this->content = htmlentities($this->content, null, "UTF-8");

        FormValidator::validate("title", "title", array('required' => 1, 'max_length' => 255));
        FormValidator::validate("content", "content", array('required' => 1, 'max_length' => 500));
        FormValidator::validate("status", "status", array('required' => 1));
    }

}
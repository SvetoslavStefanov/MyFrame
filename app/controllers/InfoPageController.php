<?

class InfoPageController extends Controller
{
    protected $before = array(
        'getNewPage' => array('show')
    );

    protected function getNewPage()
    {
        $this->page = InfoPage::get($this->id);
    }

    public function showAction()
    {
        $this->title = $this->page->title;
        $this->page->content = html_entity_decode($this->page->content);

        
        switch($this->page->status)
        {
            case InfoPage::INFOPAGE_STATUS_HIDDEN:
                if(!isset($this->currentUser)){
                    $this->page->content = 'This page is only for logged users';
                }
                break;
            case InfoPage::INFOPAGE_STATUS_INACTIVE:
                $this->page->content = 'This page is inactive';
                break;
        }
    }
}
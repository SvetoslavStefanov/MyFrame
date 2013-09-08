<?
class admin_InfopageController extends admin_BaseController{
    var $before = array(
        'setNewPage' => array('index', 'new', 'create'),
        'getInfopagesInfo' => array('show', 'edit', 'update', 'destroy'),
    );

    protected function setNewPage(){
        $this->page = new admin_InfoPage();
    }

    protected function getInfopagesInfo(){
        $this->page = admin_InfoPage::get($this->id);
    }

    public function indexAction(){
        $this->title = 'Info Pages';
        $this->pages = $this->page->findAll();
    }

    public function newAction(){
        $this->title = 'Добавяне на инфо страница';
    }

    public function createAction(){
        if($this->page->save($this->post('admin_InfoPage'))){
            $this->redirect('InfoPage/index');
        }else{
            $this->action('new');
        }
    }

    public function showAction(){
        $this->title = $this->page->title;

    }

    public function editAction(){
        $this->title = $this->page->title .' '. ' Редактиране';

    }

    public function updateAction(){
        if($this->page->save($this->post('admin_InfoPage'))){
            $this->redirect("InfoPage/show/{$this->id}");
        }else{
            $this->page->content = html_entity_decode($this->page->content);
            $this->action('edit');
        }
    }

    public function destroyAction(){
        $this->page->destroy();
        $this->redirect('InfoPage/index');
    }
}
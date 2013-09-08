<?

class ArticleController extends Controller
{

    var $before = array(
        'setNewArticle' => array('new', 'index', 'update', 'create')
        , 'getArticleObj' => array('update', 'destroy', 'show', 'edit')
        , 'confirmLogged' => array('new', 'edit', 'destroy', 'create')
    );

    protected function setNewArticle ()
    {
        $this->article = new Article();
    }

    public function getArticleObj ()
    {
        $news = new Article();
        $this->article = $news->get($this->id);
    }

    public function newAction ()
    {
        $this->title = 'Create new Article';
    }

    public function createAction ()
    {
        $_POST['Article']['from'] = $this->currentUser->id;
        if ($this->article->save($this->post('Article'))) {
            $this->redirect("Article/show/{$this->article->id}");
        } else {
            $this->action('new');
        }
    }

    public function indexAction ()
    {
        $this->title = 'List all articles';
        $this->articles = $this->article->findAll();
    }

    public function showAction ()
    {
        $this->title = $this->article->title;
    }

    public function editAction ()
    {
        $this->title = $this->article->title . ' Edit';
    }

    public function updateAction ()
    {
        if ($this->article->save($this->post('Article'))) {
            $this->redirect("Article/show/{$this->article->id}");
        } else {
            $this->action('edit');
        }
    }

    public function destroyAction ()
    {
        $this->article->destroy();
        $this->redirect("Article/index");
    }

}

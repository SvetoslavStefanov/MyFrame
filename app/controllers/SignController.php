<?

class SignController extends Controller
{

    protected $before = array(
        'setNewSign' => array('up', 'create', 'login', 'in')
        , 'isLogged' => array('in', 'up', 'create', 'login')
    );

    protected function setNewSign ()
    {
        $this->sign = new Sign();
    }

    public function upAction ()
    {
        $this->title = "Register new user";
    }

    public function createAction ()
    {
        if ($this->sign->save($this->post('Sign'), null, 'register')) {
            $this->redirect("Sign/in");
        } else {
            $this->action('up');
        }
    }

    public function inAction ()
    {
        $this->title = "Login";
    }

    public function loginAction ()
    {
        if ($this->sign = $this->sign->loginValidate($this->post('Sign'))) {
            $this->setUserCookie();
            $this->redirect("Article/index");
        } else {
            $this->action('in');
        }
    }

    protected function setUserCookie ()
    {
        $userData = serialize(array('user_id' => $this->sign->id));

        setcookie('user_cookie', $userData, time() + 86400, '/');
    }

    public function outAction ()
    {
        setcookie('user_cookie', 'dd', time() - 1, '/');
        $this->back();
    }

    protected function isLogged ()
    {
        if (isset($this->currentUser) && ($this->currentUser instanceof ActiveRecord)) {
            $this->back();
        }
    }

}
<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController {

    public function beforeFilter(\Cake\Event\EventInterface $event) {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions([
            'login', 'logout', 'forgetPassword',
            'index', 'view',  'add',
            'edit', 'delete', 'profile',
            'countUsers'
        ]);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index() {
        $users = $this->paginate($this->Users->find('all'))->toList();

        return $this->response
            ->withType('application/json')
            ->withStatus(200)
            ->withStringBody(json_encode($users));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $user = $this->Users->find('all')->where(['id' => $id])->first();

        if(!empty($user)) {
            return $this->response
                ->withType('application/json')
                ->withStatus(200)
                ->withStringBody(json_encode($user));

        } else {
            return $this->response
                ->withType('application/json')
                ->withStatus(404)
                ->withStringBody(json_encode(['message' => 'Ops... osuário não encontrado!']));
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        if ($this->request->is('post')) {
            $user = $this->Users->newEmptyEntity();
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->username = $user->email;

            if ($this->Users->save($user)) {
                return $this->response
                    ->withType('application/json')
                    ->withStatus(200)
                    ->withStringBody(json_encode('Cadastro realizado com sucesso!'));
            }
            return $this->response
                ->withType('application/json')
                ->withStatus(400)
                ->withStringBody(json_encode('Não foi possível cadastrar a conta. Por favor, tente novamente!'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $user = $this->Users->find('all')->where(['id' => $id])->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            if ($this->Users->save($user)) {
                return $this->response
                    ->withType('application/json')
                    ->withStatus(200)
                    ->withStringBody(json_encode('Os dados da conta foi atualizado!'));
            }
            return $this->response
                ->withType('application/json')
                ->withStatus(400)
                ->withStringBody(json_encode('Não foi possível atualizar os dados da conta. Por favor, tente novamente!'));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->find('all')->where(['id' => $id])->first();

        if ($this->Users->delete($user)) {
            return $this->response
                ->withType('application/json')
                ->withStatus(200)
                ->withStringBody(json_encode(['message' => 'Conta excluída com sucesso!']));

        } else {
            return $this->response
                ->withType('application/json')
                ->withStatus(400)
                ->withStringBody(json_encode(['message' => 'Não foi possível excluir a conta. Por favor, tente novamente!']));
        }
    }

    public function login() {
        if ($this->request->is('post')) {
            $result = $this->Authentication->getResult();

            if ($result && $result->isValid()) {
                $credentials = [
                    'name' => $result->getData()->name,
                    'token' => $result->getData()->password,
                    'username' => $this->request->getData('username'),
                    'password' => $this->request->getData('password'),
                ];

                return $this->response
                    ->withType('application/json')
                    ->withStatus(200)
                    ->withStringBody(json_encode($credentials));
            }
            return $this->response
                ->withType('application/json')
                ->withStatus(401)
                ->withStringBody(json_encode(['message' => 'Usuário ou senha inválido!']));
        }
    }

    public function logout() {
        if ($this->request->is('post')) {
            $result = $this->Authentication->getResult();

            if ($result && $result->isValid()) {
                $this->Authentication->logout();

                return $this->response
                    ->withType('application/json')
                    ->withStatus(200)
                    ->withStringBody(json_encode('Deslogado com sucesso!'));
            }
        }
    }

    public function forgetPassword() { }

    public function profile() {
        $token = $this->request->getQuery('id');
        $profile = $this->Users->find('all')->where(['password' => $token])->first();

        return $this->response
            ->withType('application/json')
            ->withStatus(200)
            ->withStringBody(json_encode($profile));
    }

    public function countUsers() {
        $this->debug($this->Users->find('all')->count());
        $count = $this->Users->find('all')->count();

        return $this->response
            ->withType('application/json')
            ->withStatus(200)
            ->withStringBody(json_encode($count));

    }
}

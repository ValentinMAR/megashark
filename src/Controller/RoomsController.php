<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\Collection\Collection;
/**
 * Rooms Controller
 *
 * @property \App\Model\Table\RoomsTable $Rooms
 *
 * @method \App\Model\Entity\Room[] paginate($object = null, array $settings = [])
 */
class RoomsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $rooms = $this->paginate($this->Rooms);
        $this->set(compact('rooms'));
        $this->set('_serialize', ['rooms']);
    }
    /**
     * View method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {

        $room = $this->Rooms->get($id, 
            ['contain' => ['Showtimes']]);
        
        $weekMonday = new \DateTime('monday this week');
        $weekSunday = new \DateTime('sunday this week');
        $moviesSemaine = $this->Rooms->Showtimes
            ->find()
            ->where([
                'room_id' => $room->id, 
                 'start >='=>$weekMonday,
                 'start <='=>$weekSunday
            ]);
            
        
        $items = ['lundi' => 1, 'mardi' => 2, 'mercredi' => 3, 'jeudi' => 4, 'vendredi' => 5, 'samedi' => 6, 'dimanche' => 7];
        $collection = new Collection($items);
        $collectionClassee = $collection->groupBy($showtime->start->format('N'));
        
        $this->set('moviesSemaine', $moviesSemaine);
        $this->set('room', $room);
        $this->set('_serialize', ['room']);
    }
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $room = $this->Rooms->newEntity();
        if ($this->request->is('post')) {
            $room = $this->Rooms->patchEntity($room, $this->request->getData());
            if ($this->Rooms->save($room)) {
                $this->Flash->success(__('The room has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The room could not be saved. Please, try again.'));
        }
        $this->set(compact('room'));
        $this->set('_serialize', ['room']);
    }
    /**
     * Edit method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $room = $this->Rooms->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $room = $this->Rooms->patchEntity($room, $this->request->getData());
            if ($this->Rooms->save($room)) {
                $this->Flash->success(__('The room has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The room could not be saved. Please, try again.'));
        }
        $this->set(compact('room'));
        $this->set('_serialize', ['room']);
    }
    /**
     * Delete method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $room = $this->Rooms->get($id);
        if ($this->Rooms->delete($room)) {
            $this->Flash->success(__('The room has been deleted.'));
        } else {
            $this->Flash->error(__('The room could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
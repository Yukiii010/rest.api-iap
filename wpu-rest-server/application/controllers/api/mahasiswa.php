<?php
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * @property Mahasiswa_model $Mahasiswa_model
 */
class Mahasiswa extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mahasiswa_model');

        $this->methods['index_get']['limit'] = 100;
    }

    public function index_get()
    {
        $id = $this->get('id');
        if($id === null) {
        $data = $this->Mahasiswa_model->getMahasiswa();
        } else {
            $data = $this->Mahasiswa_model->getMahasiswa($id);
        }


        if($data) {
            $this->response([
                'status' => true,
                'message' => $data
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

   public function index_delete() {
    $id = $this->delete('id');

    if ($id === null) {
         $this->response([
            'status' => false,
            'message' => 'provide an id'
        ], REST_Controller::HTTP_BAD_REQUEST);
    } else {
        if ($this->Mahasiswa_model->deletemahasiswa($id) > 0) {
            $this->response([
                'status' => true,
                'message' => 'deleted',
                'id' => $id
            ], REST_Controller::HTTP_ACCEPTED);
        } else {
            $this->response([
                'status' => false,
                'message' => 'id not found'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}


public function index_post() {
    $data = [
        'nim' => $this->post ('nim'),
        'nama' => $this->post ('nama'),
        'email' => $this->post ('email'),
        'jurusan' => $this->post ('jurusan')

    ];

    if( $this->Mahasiswa_model->createmahasiswa ($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'new mahasiswa has been created',
            ], REST_Controller::HTTP_CREATED);
    } else {
          $this->response([
                'status' => false,
                'message' => 'failed to create new data!'
            ], REST_Controller::HTTP_BAD_REQUEST);

    }
}

public function index_put () {
    $id = $this->put('id');
    $data = [
        'nim' => $this->put ('nim'),
        'nama' => $this->put ('nama'),
        'email' => $this->put ('email'),
        'jurusan' => $this->put ('jurusan')
    ];

    if( $this->Mahasiswa_model->updatemahasiswa ($data, $id) > 0) {
            $this->response([
                'status' => true,
                'message' => 'updated',
            ], REST_Controller::HTTP_OK);
    } else {
          $this->response([
                'status' => false,
                'message' => 'failed to update!'
            ], REST_Controller::HTTP_BAD_REQUEST);

    }
}

}


    


<?php 
use GuzzleHttp\Client;

class Mahasiswa_model extends CI_model {

    private $_client;

    public function __construct()
    {
        $this->_client = new Client([
            'base_uri' => 'http://localhost/rset-api/wpu-rest-server/api/',
            'auth' => ['Ilham', 'api123'],
        ]);
    }

    public function getAllMahasiswa()
    {
        //return $this->db->get('mahasiswa')->result_array();
        $response = $this->_client->request('GET', 'mahasiswa', [
            'query' => [
                'wpu-api-key' => 'Ilham123'
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result ['message'];
    }

     public function getMahasiswaById($id)
    {
         $client = new Client();

        $response = $this->_client->request('GET', 'mahasiswa', [
            'query' => [
                'wpu-api-key' => 'Ilham123',
                'id' => $id
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result ['message'][0];
    
    }

    public function tambahDataMahasiswa()
    {
        $data = [
            "nama" => $this->input->post('nama', true),
            "nim" => $this->input->post('nim', true),
            "email" => $this->input->post('email', true),
            "jurusan" => $this->input->post('jurusan', true),
            'wpu-api-key' => 'Ilham123'
        ];

        $response = $this->_client->request('POST', 'mahasiswa', [
            'form_params' => $data
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result;
    }

    public function hapusDataMahasiswa($id)
    {
       $response = $this->_client->request('DELETE', 'mahasiswa', [
        'form_params' => [
            'id' => $id,
            'wpu-api-key' => 'Ilham123'
        ]
       ]);

       $result = json_decode($response->getBody()->getContents(), true);

        return $result;
    }

    public function ubahDataMahasiswa()
    {
        $data = [
            "nama" => $this->input->post('nama', true),
            "nrp" => $this->input->post('nim', true),
            "email" => $this->input->post('email', true),
            "jurusan" => $this->input->post('jurusan', true)
        ];

      $response = $this->_client->request('POST', 'mahasiswa', [
            'form_params' => $data
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result;
    }

    public function cariDataMahasiswa()
    {
        $keyword = $this->input->post('keyword', true);
        $this->db->like('nama', $keyword);
        $this->db->or_like('jurusan', $keyword);
        $this->db->or_like('nim', $keyword);
        $this->db->or_like('email', $keyword);
        return $this->db->get('mahasiswa')->result_array();
    }
}
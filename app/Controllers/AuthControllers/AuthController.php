<?php

namespace App\Controllers\AuthControllers;

use App\Controllers\BaseController;
use App\Models\AuthModels\UserModel;
use App\Models\DataModels\PegawaiModel;

class AuthController extends BaseController
{
    public function index()
    {
        if (session()->get('id_user')) {
            return redirect()->to('/');
        }
        return view('auth/login_view');
    }

    public function attemptLogin()
    {
        if ($this->request->isAJAX()) {
            $validation = \Config\Services::validation();

            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $valid = $this->validate([
                'username' => [
                    'label' => 'Username',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} Harus Diisi'
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} Harus Diisi'
                    ]
                ]
            ]);

            if (!$valid) {
                $sessError = [
                    'username' => $validation->getError('username'),
                    'password' => $validation->getError('password')
                ];

                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => $sessError
                    ]
                );
            }
            $userModel = model(UserModel::class);
            $user = $userModel->cekUser($username);
            if (!$user) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'username' => 'Username tidak ditemukan'
                        ]
                    ]
                );
            }

            $userPassword = $userModel->cekPassword($username, $password);
            if (!$userPassword) {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'password' => 'Password tidak sesuai'
                        ]
                    ]
                );
            }

            $pegawaiModel = model(PegawaiModel::class);
            $detailUser = $pegawaiModel->getDetailPegawai($userPassword['id_user']);
            if ($detailUser['departemen'] != 'CSSD') {
                return $this->response->setJSON(
                    [
                        'sukses' => false,
                        'pesan' => [
                            'username' => 'User bukan petugas CSSD',
                            'password' => 'User bukan petugas CSSD'
                        ]
                    ]
                );
            }

            $data_user = [
                'id_user' => $user['id_user'],
                'nik' => $detailUser['nik'],
                'nama' => $detailUser['nama'],
                'foto' => "http://192.168.30.20/webapps/penggajian/" . $detailUser['photo'],
                'departemen' => $detailUser['departemen']
            ];

            session()->set($data_user);
            $this->logModel->insert([
                "id_user" => $data_user['id_user'],
                "log" => "Login aplikasi"
            ]);
            return $this->response->setJSON(
                [
                    'sukses' => true,
                    'pesan' => [
                        'url' => '/'
                    ]
                ]
            );
        }
    }

    public function logout()
    {
        $id_user = session()->get('id_user');
        session()->destroy();
        $this->logModel->insert([
            "id_user" => $id_user,
            "log" => "Logout aplikasi"
        ]);
        return redirect()->to('/login');
    }
}

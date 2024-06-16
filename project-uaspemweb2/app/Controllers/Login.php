<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoginModel;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    public function index()
    {
        return view('login/index');
    }

    public function cekUser()
    {
        $iduser = $this->request->getPost('iduser');
        $pass = $this->request->getPost('pass');

        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'iduser' => [
                'label' => 'ID User',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'pass' => [
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ]
        ]);

        if (!$valid) {
            $sessError = [
                'errorIdUser' => $validation->getError('iduser'),
                'errorPass' => $validation->getError('pass'),
            ];

            session()->setFlashdata($sessError);
            return redirect()->to(site_url('login/index'));
        } else {
            $modelLogin = new LoginModel();

            $cekUserLog = $modelLogin->find($iduser);
            if ($cekUserLog == NULL) {
                $sessError = [
                    'errorIdUser' => 'Maaf, user yang anda masukkan belum terdaftar'
                ];

                session()->setFlashdata($sessError);
                return redirect()->to(site_url('login/index'));
            } else {
                $passwordUser = $cekUserLog['userpassword'];

                if (password_verify($pass, $passwordUser)) {
                    $idlevel = $cekUserLog['userlvlid'];

                    $simpan_session = [
                        'iduser' => $iduser,
                        'namauser' => $cekUserLog['usernama'],
                        'idlevel' => $idlevel,
                    ];
                    session()->set($simpan_session);

                    return redirect()->to('/main/index');
                } else {
                    $sessError = [
                        'errorPass' => 'Password yang anda masukkan salah'
                    ];

                    session()->setFlashdata($sessError);
                    return redirect()->to(site_url('login/index'));
                }
            }
        }
    }

    public function keluar()
    {
        session()->destroy();
        return redirect()->to('/login/index');
    }
}

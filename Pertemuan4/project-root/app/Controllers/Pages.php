<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Selamat Datang | Unipdu Press',
        ];

        return view('pages/home', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'About Unipdu'
        ];

        return view('pages/about', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact',
            'alamat' => [
                [
                    'tipe' => 'Rumah',
                    'alamat' => 'BandarKedungMulyo',
                    'kota' => 'Jombang'
                ],
                [
                    'tipe' => 'Kantor',
                    'alamat' => 'Perak',
                    'kota' => 'Jombang'
                ]
            ]
        ];

        return view('pages/contact', $data);
    }
}

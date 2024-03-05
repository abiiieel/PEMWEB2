<?php 

namespace App\Controllers;

class Page extends BaseController
{
    public function about()
    {
        echo "about page";
    }

    public function contact()
    {
        echo "contact page";
    }

    public function faqs()
    {
        echo "faqs page";
    }

    public function tos()
    {
        echo "Halaman Term of Service";
    }

    public function biodata()
    {
        echo "Nama: Muhammad Jihad Fisabilillah Enha";
        echo "<br>Tempat Tanggal Lahir: Sidaorjo, 21 November 2003";
        echo "<br>Alamat: RT/RW 06/01 Semelo Kayen BandarKedungMulyo";
    }
}
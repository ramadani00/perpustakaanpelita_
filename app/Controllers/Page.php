<?php
namespace App\Controllers;

use App\Models\KategoriModel;

class Page extends BaseController
{
    private function getKategori()
    {
        $kategoriModel = new KategoriModel();
        return $kategoriModel->findAll();
    }

    public function about()
    {
        return view('about', [
            'title' => 'Tentang Kami',
            'content' => 'InfoTerkini.id adalah media digital independen yang berfokus pada penyajian berita aktual, objektif, dan dapat dipercaya. Didirikan pada tahun 2025 oleh sekelompok jurnalis muda, kami berkomitmen untuk memberikan informasi berkualitas dan berimbang kepada masyarakat Indonesia.
        
        Misi kami: 
            - Menyajikan berita yang akurat dan faktual
            - Menjadi wadah literasi digital dan media
            - Mendorong masyarakat untuk berpikir kritis dan terbuka
            - Mari bersama ciptakan media yang cerdas dan berintegritas!',
            'kategori' => $this->getKategori()
        ]);
    }

    public function contact()
    {
        return view('contact', [
            'title' => 'Halaman Kontak',
            'content' => 'Kami sangat terbuka untuk kritik, saran, maupun kerja sama. Silakan isi formulir di bawah ini atau hubungi kami melalui informasi berikut:

            📧 Email: xxxxx@infoterkini.id
            📱 WhatsApp: +62 812-xxxx-xxxx
            📍 Alamat: Jl. Merdeka No. xxx, Jakarta Pusat
            
            Terima kasih telah mempercayai InfoTerkini.id sebagai sumber informasi Anda!',
            'kategori' => $this->getKategori()
        ]);
    }

    public function artikel()
    {
        return view('artikel', [
            'kategori' => $this->getKategori()
        ]);
    }

    public function faqs()
    {
        return view('faqs', [
            'title' => 'Halaman FAQ',
            'content' => 'Ini adalah halaman FAQ yang menjelaskan tentang isi 
            halaman ini.',
            'kategori' => $this->getKategori()
        ]);
    }

    public function tos()
    {
        return view('tos', [
            'title' => 'Halaman TOS',
            'content' => 'Ini adalah halaman Terms of Service yang menjelaskan tentang isi 
            halaman ini.',
            'kategori' => $this->getKategori()
        ]);
    }
}
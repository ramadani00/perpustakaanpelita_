<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="contact-hero" style="background: linear-gradient(90deg,#10439F 60%,#2563eb 100%); padding: 38px 0 28px 0; border-radius: 16px; text-align: center; color: #fff; margin: 40px auto 0; max-width: 900px;">
    <h1 style="font-size:2.1rem; font-weight:700; margin-bottom:10px;">Hubungi Kami</h1>
    <p style="font-size:1.15rem; max-width:700px; margin:0 auto;">Kami siap membantu Anda dengan segala pertanyaan mengenai layanan perpustakaan.</p>
</div>

<div class="contact-container" style="max-width: 1100px; margin: 48px auto; padding: 0 16px; display: flex; gap: 32px; flex-wrap: wrap;">
    <!-- Contact Form -->
    <div class="contact-form" style="flex: 1 1 340px; min-width: 280px; background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(16,67,159,0.06); padding: 32px;">
        <h2 style="font-size: 1.35rem; color: #10439F; margin-bottom: 18px;">Kirim Pesan</h2>
        <form action="<?= base_url('contact/send') ?>" method="post" style="display: grid; gap: 18px;">
            <div>
                <label for="name" style="display: block; margin-bottom: 7px; font-weight: 600; color: #444;">Nama Lengkap</label>
                <input type="text" id="name" name="name" required style="width: 100%; padding: 10px; border: 1px solid #e3f0ff; border-radius: 8px; font-size: 1rem; background:#f8f9fa;">
            </div>
            <div>
                <label for="email" style="display: block; margin-bottom: 7px; font-weight: 600; color: #444;">Email</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #e3f0ff; border-radius: 8px; font-size: 1rem; background:#f8f9fa;">
            </div>
            <div>
                <label for="subject" style="display: block; margin-bottom: 7px; font-weight: 600; color: #444;">Subjek</label>
                <input type="text" id="subject" name="subject" required style="width: 100%; padding: 10px; border: 1px solid #e3f0ff; border-radius: 8px; font-size: 1rem; background:#f8f9fa;">
            </div>
            <div>
                <label for="message" style="display: block; margin-bottom: 7px; font-weight: 600; color: #444;">Pesan</label>
                <textarea id="message" name="message" rows="5" required style="width: 100%; padding: 10px; border: 1px solid #e3f0ff; border-radius: 8px; font-size: 1rem; background:#f8f9fa;"></textarea>
            </div>
            <button type="submit" style="background: linear-gradient(90deg,#10439F 60%,#2563eb 100%); color: white; padding: 12px 0; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; font-weight:600; transition: background 0.3s; width: 100%; box-shadow: 0 2px 8px rgba(16,67,159,0.08);">
                <i class="fas fa-paper-plane" style="margin-right:8px;"></i> Kirim Pesan
            </button>
        </form>
    </div>

    <!-- Contact Info -->
    <div class="contact-info" style="flex: 1 1 340px; min-width: 280px; background: #f8f9fa; border-radius: 14px; box-shadow: 0 2px 12px rgba(16,67,159,0.04); padding: 32px;">
        <h2 style="font-size: 1.35rem; color: #10439F; margin-bottom: 18px;">Informasi Kontak</h2>
        <p style="font-size: 1.05rem; line-height: 1.7; color: #444; margin-bottom: 24px;">
            Kami sangat terbuka untuk kritik, saran, pertanyaan, maupun kerja sama. Silakan hubungi kami melalui informasi berikut:
        </p>
        <div style="display: flex; align-items: flex-start; gap: 13px; margin-bottom: 18px;">
            <div style="font-size: 1.3rem; color: #2563eb;"><i class="fas fa-envelope"></i></div>
            <div>
                <h3 style="margin: 0 0 4px 0; color: #10439F; font-size: 1rem;">Email</h3>
                <p style="margin: 0; color: #444;">perpuspelita@gmail.com</p>
            </div>
        </div>
        <div style="display: flex; align-items: flex-start; gap: 13px; margin-bottom: 18px;">
            <div style="font-size: 1.3rem; color: #10b981;"><i class="fab fa-whatsapp"></i></div>
            <div>
                <h3 style="margin: 0 0 4px 0; color: #10439F; font-size: 1rem;">WhatsApp</h3>
                <p style="margin: 0; color: #444;">+62 812-xxxx-xxxx</p>
            </div>
        </div>
        <div style="display: flex; align-items: flex-start; gap: 13px; margin-bottom: 18px;">
            <div style="font-size: 1.3rem; color: #f59e42;"><i class="fas fa-map-marker-alt"></i></div>
            <div>
                <h3 style="margin: 0 0 4px 0; color: #10439F; font-size: 1rem;">Alamat</h3>
                <p style="margin: 0; color: #444;">Jababeka<br>Gedung Pelita Lantai 1</p>
            </div>
        </div>
        <div style="display: flex; align-items: flex-start; gap: 13px;">
            <div style="font-size: 1.3rem; color: #2563eb;"><i class="fas fa-clock"></i></div>
            <div>
                <h3 style="margin: 0 0 4px 0; color: #10439F; font-size: 1rem;">Jam Operasional</h3>
                <p style="margin: 0; color: #444;">
                    Senin-Jumat: 08.00 - 17.00 WIB<br>
                    Sabtu: 09.00 - 15.00 WIB<br>
                    Minggu & Hari Libur: Tutup
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
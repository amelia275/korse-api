<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Chapter;
use App\Models\ContentBlock;
use App\Models\Course;
use App\Models\MaterialItem;
use App\Models\Thread;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Database\Seeder;

class KorseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        // ---------- COURSE: Dasar Statistika ----------
        $statistika = Course::create([
            'title' => 'Dasar Statistika',
            'description' => 'Pengantar konsep statistika untuk pemula, dari distribusi data hingga probabilitas dasar.',
            'level_label' => 'Pemula',
            'cover_image_url' => 'assets/images/cover_statistika.png',
        ]);

        // ---------- CHAPTER: Pengenalan Data ----------
        $pengenalanData = Chapter::create([
            'course_id' => $statistika->id,
            'title' => 'Pengenalan Data',
            'headline' => 'Jenis Data dan Skala Pengukuran',
            'module_label' => 'MODUL 1 · DASAR STATISTIKA',
            'estimate_label' => 'Estimasi total 14 menit bacaan interaktif',
            'order' => 1,
        ]);

        $apaItuDistribusi = MaterialItem::create([
            'chapter_id' => $pengenalanData->id,
            'title' => 'Apa itu Distribusi?',
            'reading_time_label' => '3 menit baca',
            'core_concept_note' => 'Lanjut ke bahasan kurva lonceng',
            'order' => 1,
        ]);

        ContentBlock::create([
            'material_id' => $apaItuDistribusi->id,
            'type' => 'illustration',
            'image_url' => 'assets/images/fig_histogram.png',
            'text_content' => 'Gambar 2.0: Histogram sebaran nilai sebagai bentuk awal distribusi',
            'order' => 1,
        ]);
        ContentBlock::create([
            'material_id' => $apaItuDistribusi->id,
            'type' => 'paragraph',
            'text_content' => 'Distribusi data adalah cara nilai-nilai dalam sebuah kumpulan data tersebar: mana yang sering muncul, mana yang jarang, dan seberapa jauh jaraknya dari pusat data.',
            'order' => 2,
        ]);
        ContentBlock::create([
            'material_id' => $apaItuDistribusi->id,
            'type' => 'callout',
            'callout_type' => 'definisi',
            'text_content' => 'Distribusi menggambarkan seberapa sering tiap nilai muncul dalam kumpulan data, biasanya divisualisasikan lewat histogram.',
            'order' => 3,
        ]);

        // ---------- CHAPTER: Distribusi Data ----------
        $distribusiData = Chapter::create([
            'course_id' => $statistika->id,
            'title' => 'Distribusi Data',
            'headline' => 'Kurva, Pola, dan Dispersi',
            'module_label' => 'MODUL 2 · DASAR STATISTIKA',
            'estimate_label' => 'Estimasi total 18 menit bacaan interaktif',
            'order' => 2,
        ]);

        $distribusiNormal = MaterialItem::create([
            'chapter_id' => $distribusiData->id,
            'title' => 'Distribusi Normal',
            'reading_time_label' => '5 menit baca',
            'core_concept_note' => 'Lanjut ke bahasan parameter deviasi',
            'order' => 2,
        ]);

        ContentBlock::create([
            'material_id' => $distribusiNormal->id,
            'type' => 'illustration',
            'image_url' => 'assets/images/fig_kurva_normal.png',
            'text_content' => 'Gambar 2.1: Kurva Lonceng Gauss Simetris (μ = Nilai Tengah)',
            'order' => 1,
        ]);
        ContentBlock::create([
            'material_id' => $distribusiNormal->id,
            'type' => 'paragraph',
            'text_content' => 'Distribusi normal adalah distribusi probabilitas kontinu yang membentuk kurva lonceng simetris sempurna, di mana nilai sebagian besar data berpusat di sekitar nilai rata-rata (mean).',
            'order' => 2,
        ]);
        ContentBlock::create([
            'material_id' => $distribusiNormal->id,
            'type' => 'callout',
            'callout_type' => 'definisi',
            'text_content' => 'Secara matematis, distribusi ini dipengaruhi oleh dua parameter utama: rata-rata (μ) yang menentukan posisi puncak dan deviasi standar (σ) yang menentukan lebar sebaran data.',
            'order' => 3,
        ]);
        ContentBlock::create([
            'material_id' => $distribusiNormal->id,
            'type' => 'callout',
            'callout_type' => 'tips',
            'text_content' => 'Dalam ujian seleksi berskala nasional dengan 10.000 peserta, sekitar 68,2% peserta akan memperoleh nilai berkisar di antara satu deviasi standar dari nilai rata-rata keseluruhan.',
            'order' => 4,
        ]);

        $distribusiBinomial = MaterialItem::create([
    	    'chapter_id' => $distribusiData->id,
            'title' => 'Distribusi Binomial',
            'reading_time_label' => '4 menit baca',
            'order' => 3,
	]);

        $latihanSoal = MaterialItem::create([
            'chapter_id' => $distribusiData->id,
            'title' => 'Latihan Soal',
            'reading_time_label' => '6 menit uji pemahaman',
            'order' => 4,
        ]);

	// ---------- USER PROGRESS ----------
	UserProgress::create(['user_id' => $user->id, 'material_id' => $apaItuDistribusi->id, 'status' => 'completed']);
	UserProgress::create(['user_id' => $user->id, 'material_id' => $distribusiNormal->id, 'status' => 'in_progress']);
	UserProgress::create(['user_id' => $user->id, 'material_id' => $distribusiBinomial->id, 'status' => 'locked']);
	UserProgress::create(['user_id' => $user->id, 'material_id' => $latihanSoal->id, 'status' => 'locked']);

        // ---------- COURSE LAIN (ringkas, tanpa bab/materi) ----------
        Course::create([
            'title' => 'Dasar Pemrograman',
            'description' => 'Fondasi logika pemrograman dan sintaks dasar untuk pemula.',
            'level_label' => 'Pemula',
            'cover_image_url' => 'assets/images/cover_pemrograman.png',
        ]);

        // ---------- THREAD contoh ----------
        Thread::create([
            'course_id' => $statistika->id,
            'user_id' => $user->id,
            'title' => 'Bagaimana cara menghitung deviasi standar dengan cepat?',
            'body' => 'Ada yang punya cara cepat menghitung deviasi standar untuk data besar?',
        ]);

        // ---------- ACHIEVEMENT contoh ----------
        Achievement::create([
            'user_id' => $user->id,
            'course_id' => $statistika->id,
            'title' => 'Sertifikat Dasar Statistika',
            'status_label' => 'Dalam Proses',
        ]);
    }
}
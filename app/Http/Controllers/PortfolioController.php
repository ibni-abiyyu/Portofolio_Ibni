<?php
// app/Http/Controllers/PortfolioController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Portfolio;
use App\Models\Skill;

class PortfolioController extends Controller
{
    // Data default portofolio (untuk fallback jika database kosong)
    private $defaultData = [
        'fotoprofil' => 'https://cdn.discordapp.com/attachments/1256123329505660938/1460886503625592924/Screenshot_2025-06-08_002525.png?ex=699feb14&is=699e9994&hm=d343de924bd89500ab705f42c386dcfd8448ffd0b0ab1453061d43ab71905b51',
        'name' => 'Ibni Abiyyu',
        'description' => 'Seorang pengembang web yang bersemangat menciptakan solusi digital inovatif',
        'github_url' => 'https://github.com/PPLG-SMKTI-27/uuk-ganjil-ibni-abiyyu',
        'tiktok_url' => 'https://www.tiktok.com/@meidoragon_',
        'email' => '24_ibni@student.smkti.net',
        'nomortelp' => '+62 851 5666 4819',
        'lokasi' => 'Samarinda, Indonesia',
        'pendidikan' => 'Telkom University',
        'skills' => [
            [
                'name' => 'Frontend Development',
                'icon' => 'fas fa-code',
                'percentage' => 30,
                'delay' => 0.1
            ],
            [
                'name' => 'Backend Development',
                'icon' => 'fas fa-server',
                'percentage' => 75,
                'delay' => 0.2
            ],
            [
                'name' => 'Mobile Development',
                'icon' => 'fas fa-mobile-alt',
                'percentage' => 40,
                'delay' => 0.3
            ],
            [
                'name' => 'Database Design',
                'icon' => 'fas fa-database',
                'percentage' => 70,
                'delay' => 0.4
            ]
        ]
    ];

    /**
     * Menampilkan halaman utama portofolio
     */
    public function index(Request $request)
    {
        // Gunakan Auth bawaan Laravel
        $isLoggedIn = Auth::check();
        $isAdmin = false;
        $loggedInUser = null;
        
        if ($isLoggedIn) {
            $user = Auth::user();
            $loggedInUser = [
                'username' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'user' // Pastikan ada kolom role di users table
            ];
            // Cek apakah admin (berdasarkan email atau role)
            $isAdmin = ($user->email === 'admin@example.com') || ($user->role === 'admin');
        }
        
        // Load portfolio dengan skills-nya
        $portfolio = Portfolio::with('skills')->first();

        if (!$portfolio) {
            return view('portofolio', [
                'fotoprofil' => $this->defaultData['fotoprofil'],
                'name' => $this->defaultData['name'],
                'description' => $this->defaultData['description'],
                'github_url' => $this->defaultData['github_url'],
                'tiktok_url' => $this->defaultData['tiktok_url'],
                'email' => $this->defaultData['email'],
                'nomortelp' => $this->defaultData['nomortelp'],
                'lokasi' => $this->defaultData['lokasi'],
                'pendidikan' => $this->defaultData['pendidikan'],
                'skills' => $this->defaultData['skills'],
                'isLoggedIn' => $isLoggedIn,
                'isAdmin' => $isAdmin,
                'loggedInUser' => $loggedInUser,
                'editMode' => $request->session()->get('editMode', false),
                'usingDefaultData' => true
            ]);
        }

        // Konversi skills collection ke array
        $skills = $portfolio->skills->map(function($skill) {
            return [
                'name' => $skill->name,
                'icon' => $skill->icon ?? $this->getDefaultIcon($skill->name),
                'percentage' => $skill->percentage,
                'delay' => $skill->delay ?? 0.1
            ];
        })->toArray();

        return view('portofolio', [
            'fotoprofil' => $portfolio->fotoprofil ?? $this->defaultData['fotoprofil'],
            'name' => $portfolio->name ?? $this->defaultData['name'],
            'description' => $portfolio->description ?? $this->defaultData['description'],
            'github_url' => $portfolio->github_url ?? $this->defaultData['github_url'],
            'tiktok_url' => $portfolio->tiktok_url ?? $this->defaultData['tiktok_url'],
            'email' => $portfolio->email ?? $this->defaultData['email'],
            'nomortelp' => $portfolio->nomortelp ?? $this->defaultData['nomortelp'],
            'lokasi' => $portfolio->lokasi ?? $this->defaultData['lokasi'],
            'pendidikan' => $portfolio->pendidikan ?? $this->defaultData['pendidikan'],
            'skills' => !empty($skills) ? $skills : $this->defaultData['skills'],
            'isLoggedIn' => $isLoggedIn,
            'isAdmin' => $isAdmin,
            'loggedInUser' => $loggedInUser,
            'editMode' => $request->session()->get('editMode', false),
            'usingDefaultData' => false
        ]);
    }

    /**
     * Toggle edit mode (admin only)
     */
    public function toggleEdit(Request $request)
    {
        // Cek apakah user login dan admin
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }
        
        $user = Auth::user();
        $isAdmin = ($user->email === 'admin@example.com') || ($user->role === 'admin');
        
        if (!$isAdmin) {
            return redirect('/')->with('error', 'Akses ditolak! Hanya admin yang bisa mengedit.');
        }
        
        $editMode = !$request->session()->get('editMode', false);
        $request->session()->put('editMode', $editMode);
        
        $message = $editMode ? 'Mode edit diaktifkan!' : 'Mode edit dinonaktifkan!';
        return redirect('/')->with('success', $message);
    }

    /**
     * Update data portofolio (admin only)
     */
    public function updatePortfolio(Request $request)
    {
        // Cek apakah user login dan admin
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }
        
        $user = Auth::user();
        $isAdmin = ($user->email === 'admin@example.com') || ($user->role === 'admin');
        
        if (!$isAdmin) {
            return redirect('/')->with('error', 'Akses ditolak! Hanya admin yang bisa mengupdate.');
        }

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'nullable|email',
            'nomortelp' => 'nullable|string',
            'lokasi' => 'nullable|string',
            'pendidikan' => 'nullable|string',
            'github_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'skills.name.*' => 'nullable|string',
            'skills.percentage.*' => 'nullable|integer|min:0|max:100'
        ]);

        // Cari atau buat portfolio baru
        $portfolio = Portfolio::first();
        
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'email' => $request->email,
            'nomortelp' => $request->nomortelp,
            'lokasi' => $request->lokasi,
            'pendidikan' => $request->pendidikan,
            'github_url' => $request->github_url,
            'tiktok_url' => $request->tiktok_url,
            'fotoprofil' => $this->defaultData['fotoprofil']
        ];

        if (!$portfolio) {
            $portfolio = Portfolio::create($data);
            $message = 'Data portofolio berhasil dibuat!';
        } else {
            $portfolio->update($data);
            $message = 'Data portofolio berhasil diupdate!';
        }

        // Hapus skill lama
        $portfolio->skills()->delete();

        // Simpan skill baru
        if ($request->has('skills.name') && $request->has('skills.percentage')) {
            $skillNames = $request->input('skills.name', []);
            $skillPercentages = $request->input('skills.percentage', []);
            
            foreach ($skillNames as $index => $name) {
                if (!empty($name)) {
                    $icon = $this->getDefaultIcon($name);
                    $delay = 0.1 * ($index + 1);
                    
                    Skill::create([
                        'portfolio_id' => $portfolio->id,
                        'name' => $name,
                        'icon' => $icon,
                        'percentage' => intval($skillPercentages[$index] ?? 0),
                        'delay' => $delay
                    ]);
                }
            }
        }

        // Nonaktifkan edit mode setelah menyimpan
        $request->session()->put('editMode', false);

        return redirect('/')->with('success', $message . ' Data tersimpan di database.');
    }

    /**
     * Reset ke data default (admin only)
     */
    public function resetPortfolio(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }
        
        $user = Auth::user();
        $isAdmin = ($user->email === 'admin@example.com') || ($user->role === 'admin');
        
        if (!$isAdmin) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        
        $portfolio = Portfolio::first();
        if ($portfolio) {
            $portfolio->skills()->delete();
            $portfolio->delete();
        }
        
        $request->session()->put('editMode', false);
        
        return redirect('/')->with('success', 'Data berhasil direset ke default!');
    }

    /**
     * Import data default ke database (admin only)
     */
    public function importDefaultData(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }
        
        $user = Auth::user();
        $isAdmin = ($user->email === 'admin@example.com') || ($user->role === 'admin');
        
        if (!$isAdmin) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        $oldPortfolio = Portfolio::first();
        if ($oldPortfolio) {
            $oldPortfolio->skills()->delete();
            $oldPortfolio->delete();
        }

        $portfolio = Portfolio::create([
            'fotoprofil' => $this->defaultData['fotoprofil'],
            'name' => $this->defaultData['name'],
            'description' => $this->defaultData['description'],
            'github_url' => $this->defaultData['github_url'],
            'tiktok_url' => $this->defaultData['tiktok_url'],
            'email' => $this->defaultData['email'],
            'nomortelp' => $this->defaultData['nomortelp'],
            'lokasi' => $this->defaultData['lokasi'],
            'pendidikan' => $this->defaultData['pendidikan'],
        ]);

        foreach ($this->defaultData['skills'] as $skillData) {
            Skill::create([
                'portfolio_id' => $portfolio->id,
                'name' => $skillData['name'],
                'icon' => $skillData['icon'],
                'percentage' => $skillData['percentage'],
                'delay' => $skillData['delay']
            ]);
        }

        return redirect('/')->with('success', 'Data default berhasil diimpor ke database!');
    }

    // ========== HELPER METHODS ==========
    
    /**
     * Mendapatkan icon default berdasarkan nama skill
     */
    private function getDefaultIcon($skillName)
    {
        $icons = [
            'frontend' => 'fas fa-code',
            'backend' => 'fas fa-server',
            'mobile' => 'fas fa-mobile-alt',
            'database' => 'fas fa-database',
            'laravel' => 'fab fa-laravel',
            'php' => 'fab fa-php',
            'javascript' => 'fab fa-js',
            'python' => 'fab fa-python',
            'react' => 'fab fa-react',
            'vue' => 'fab fa-vuejs',
            'angular' => 'fab fa-angular',
            'html' => 'fab fa-html5',
            'css' => 'fab fa-css3-alt',
            'git' => 'fab fa-git-alt',
            'docker' => 'fab fa-docker',
            'aws' => 'fab fa-aws',
            'wordpress' => 'fab fa-wordpress',
        ];

        $skillNameLower = strtolower($skillName);
        
        foreach ($icons as $key => $icon) {
            if (strpos($skillNameLower, $key) !== false) {
                return $icon;
            }
        }
        
        return 'fas fa-code';
    }
}
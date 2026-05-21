<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('style')
</head>
<body>
    <!-- ========== NAVIGASI BARU ========== -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="#home" class="logo">
                <span>Ibni</span>Abiyyu
            </a>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            
            <ul class="nav-menu" id="navMenu">
                <li class="nav-item">
                    <a href="#about" class="nav-link">Tentang</a>
                </li>
                <li class="nav-item">
                    <a href="#skills" class="nav-link">Keahlian</a>
                </li>
                <li class="nav-item">
                    <a href="#contact" class="nav-link">Kontak</a>
                </li>
                
                {{-- Tombol Admin jika login sebagai admin --}}
                @if(isset($isAdmin) && $isAdmin)
                    <li class="nav-item">
                        <form method="POST" action="/toggle-edit" style="margin: 0;">
                            @csrf
                            <button type="submit" class="nav-button" 
                                    style="{{ isset($editMode) && $editMode ? 'background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);' : '' }}">
                                <i class="fas {{ isset($editMode) && $editMode ? 'fa-edit' : 'fa-pencil-alt' }}"></i>
                                {{ isset($editMode) && $editMode ? 'Keluar Edit' : 'Edit Mode' }}
                            </button>
                        </form>
                    </li>
                @endif
                
                {{-- Tombol Login/Logout --}}
                <li class="nav-item">
                    @if(isset($isLoggedIn) && $isLoggedIn)
                        <a href="{{ route('logout') }}" class="nav-button" style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-button">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    @endif
                </li>
            </ul>
        </div>
    </nav>
    
    <!-- Space untuk fixed navbar -->
    <div class="navbar-space"></div>
    
    <!-- Tombol Back to Top -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Header dengan Animasi -->
    <header class="header" id="home">
        <!-- Particles -->
        <div class="particles" id="particles"></div>
        
        <div class="container header-content">
            <h1>{{ $name ?? 'Ibni Abiyyu' }}</h1>
            <div class="title-animation">
                <span class="typing-text">Software Engineer</span>
            </div>
            
            {{-- Tampilkan pesan --}}
            @if(session('success'))
            <div class="alert-success" style="margin-top: 20px;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert-error" style="margin-top: 20px;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif
            
            @if(isset($loginMessage) && $loginMessage)
            <div class="alert-info" style="margin-top: 20px;">
                <i class="fas fa-info-circle"></i> {{ $loginMessage }}
            </div>
            @endif
            
            {{-- Tampilkan status admin --}}
            @if(isset($isAdmin) && $isAdmin && isset($editMode) && $editMode)
            <div class="alert-warning" style="margin-top: 10px;">
                <i class="fas fa-exclamation-triangle"></i> Mode Edit Aktif - Perubahan hanya tersimpan selama sesi ini
            </div>
            @endif
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="container">
        @yield('content')
    </main>

    <!-- Footer dengan Wave Animation -->
    <footer class="footer">
        <div class="container footer-content">
            <p>&copy; 2026 {{ $name ?? 'Ibni Abiyyu' }} - Software Engineer</p>
            @if(isset($isLoggedIn) && $isLoggedIn)
            <p style="font-size: 0.9rem; margin-top: 5px; opacity: 0.8;">
                <i class="fas fa-user-check"></i> 
                Status: {{ isset($loggedInUser) ? $loggedInUser['username'] : 'User' }}
                @if(isset($isAdmin) && $isAdmin)
                    <span style="color: var(--accent);"> (Admin)</span>
                @endif
            </p>
            @endif
        </div>
    </footer>

    <!-- JavaScript untuk Animasi dan Navigasi -->
    <script>
        // Auto hide alert messages
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-success, .alert-error, .alert-info, .alert-warning');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }, 5000);
            });
        });
        
        // Back to top button
        const backToTopButton = document.getElementById('backToTop');
        if (backToTopButton) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.add('show');
                } else {
                    backToTopButton.classList.remove('show');
                }
            });
            
            backToTopButton.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
        
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navMenu = document.getElementById('navMenu');
        
        if (mobileMenuBtn && navMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                navMenu.classList.toggle('active');
            });
        }
        
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        if (navbar) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
        }
    </script>
</body>
</html>
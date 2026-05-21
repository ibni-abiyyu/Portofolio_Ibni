@extends('layouts.main')

@section('content')
<div class="container" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="content-box fade-in" style="max-width: 400px; margin: 50px auto; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 30px;">
        <h2 class="title" style="text-align: center;">Login</h2>
        
        <!-- Session Status -->
        @if(session('status'))
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
        </div>
        @endif
        
        @if($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
            <i class="fas fa-exclamation-circle"></i> 
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Email atau Username</label>
                <input type="text" name="email" value="{{ old('email') }}" required 
                       style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px;"
                       placeholder="Masukkan email atau username">
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Password</label>
                <input type="password" name="password" required 
                       style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px;"
                       placeholder="Masukkan password">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: inline-flex; align-items: center;">
                    <input type="checkbox" name="remember" style="margin-right: 8px;">
                    <span style="color: #666;">Remember me</span>
                </label>
            </div>
            
            <button type="submit" 
                    style="width: 100%; padding: 14px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); 
                           color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;
                           transition: all 0.3s ease;">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
            
            @if (Route::has('password.request'))
            <div style="margin-top: 15px; text-align: center;">
                <a href="{{ route('password.request') }}" style="color: #6366f1; text-decoration: none; font-size: 14px;">
                    Lupa password?
                </a>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection
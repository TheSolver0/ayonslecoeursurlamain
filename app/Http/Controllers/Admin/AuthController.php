<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth,Hash,RateLimiter};
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller {
 public function login() { return view('admin.login'); }
 public function authenticate(Request $r) {
  $credentials=$r->validate(['email'=>'required|email|max:255','password'=>'required|string|max:1024']);
  $key='login:'.Str::lower($credentials['email']).'|'.$r->ip();
  if(RateLimiter::tooManyAttempts($key,5)){ throw ValidationException::withMessages(['email'=>'Trop de tentatives. Réessayez dans '.RateLimiter::availableIn($key).' secondes.']); }
  if(!Auth::attempt([...$credentials,'is_admin'=>true])){ RateLimiter::hit($key,60);throw ValidationException::withMessages(['email'=>'Identifiants incorrects.']); }
  RateLimiter::clear($key);$r->session()->regenerate();return redirect()->intended(route('admin.dashboard'));
 }
 public function logout(Request $r) { Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('login'); }
 public function password(Request $r) {
  $data=$r->validate(['current_password'=>'required|current_password','password'=>'required|string|min:12|max:255|confirmed']);
  $r->user()->update(['password'=>$data['password']]);$r->session()->regenerate();return back()->with('success','Votre mot de passe a été modifié.');
 }
}

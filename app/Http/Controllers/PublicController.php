<?php
namespace App\Http\Controllers;
use App\Models\{Post,Album};
class PublicController extends Controller {
 public function home() { return view('public.home',['posts'=>Post::published()->latest('published_at')->limit(3)->get(),'albums'=>Album::published()->with('photos')->latest('event_date')->limit(3)->get()]); }
 public function news() { return view('public.news',['posts'=>Post::published()->latest('published_at')->paginate(9)]); }
 public function article(string $slug) { return view('public.article',['post'=>Post::published()->with('photos')->where('slug',$slug)->firstOrFail()]); }
 public function gallery() { return view('public.gallery',['albums'=>Album::published()->with('photos')->latest('event_date')->paginate(12)]); }
 public function album(string $slug) { return view('public.album',['album'=>Album::published()->with('photos')->where('slug',$slug)->firstOrFail()]); }
}

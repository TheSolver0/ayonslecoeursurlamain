<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Post,Album,Photo};
class DashboardController extends Controller {
 public function __invoke() { return view('admin.dashboard',['published'=>Post::published()->count(),'drafts'=>Post::where('status','draft')->count(),'albumCount'=>Album::count(),'photoCount'=>Photo::count(),'posts'=>Post::latest('updated_at')->limit(5)->get()]); }
}

<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\{Str};
use Illuminate\Support\Facades\{DB,Storage};
use Illuminate\Validation\Rule;
class PostController extends Controller {
 public function index() { return view('admin.posts.index',['posts'=>Post::latest('updated_at')->paginate(15)]); }
 public function create() { return view('admin.posts.form',['post'=>new Post(['status'=>'draft'])]); }
 public function edit(Post $post) { $post->load('photos');return view('admin.posts.form',compact('post')); }
 public function store(Request $r) { return $this->save($r,new Post); }
 public function update(Request $r,Post $post) { return $this->save($r,$post); }
 private function save(Request $r,Post $post) {
  $data=$r->validate(['title'=>'required|string|max:180','slug'=>['nullable','string','max:200','regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',Rule::unique('posts')->ignore($post->id)],'excerpt'=>'nullable|string|max:500','body'=>'required|string|max:100000','status'=>'required|in:draft,published','published_at'=>'nullable|date','cover'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:5120','remove_cover'=>'nullable|boolean','photos'=>'nullable|array|max:20','photos.*'=>'image|mimes:jpg,jpeg,png,webp|max:5120']);
  if(empty($data['slug'])){ $base=Str::slug($data['title']) ?: 'actualite';$slug=$base;$n=2;while(Post::where('slug',$slug)->when($post->exists,fn($q)=>$q->where('id','!=',$post->id))->exists()){$slug=$base.'-'.$n++;}$data['slug']=$slug; }
  if($data['status']==='published' && empty($data['published_at'])){ $data['published_at']=now(); }
  $old=$post->cover_path;$new=null;$newPhotos=[];
  unset($data['cover'],$data['remove_cover'],$data['photos']);
  if($r->boolean('remove_cover')){ $data['cover_path']=null; }
  if($r->hasFile('cover')){ $new=$r->file('cover')->store('articles','public');$data['cover_path']=$new; }
  DB::beginTransaction();
  try{
   $post->fill($data)->save();
   $order=(int)$post->photos()->max('sort_order');
   foreach($r->file('photos',[]) as $photo){$path=$photo->store('articles/gallery','public');$newPhotos[]=$path;$post->photos()->create(['path'=>$path,'sort_order'=>++$order]);}
   DB::commit();
  }catch(\Throwable $e){DB::rollBack();Storage::disk('public')->delete(array_filter([$new,...$newPhotos]));throw $e;}
  if($old && array_key_exists('cover_path',$data) && $old!==$data['cover_path'])Storage::disk('public')->delete($old);
  return redirect()->route('admin.posts.edit',$post)->with('success','Article enregistré.');
 }
 public function removePhoto(Post $post,PostPhoto $photo) { abort_unless($photo->post_id===$post->id,404);$path=$photo->path;$photo->delete();Storage::disk('public')->delete($path);return back()->with('success','Photo supprimée.'); }
 public function destroy(Post $post) { $paths=[$post->cover_path,...$post->photos()->pluck('path')];$post->delete();Storage::disk('public')->delete(array_filter($paths));return redirect()->route('admin.posts.index')->with('success','Article supprimé.'); }
}

<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Album,Photo};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{DB,Storage};
use Illuminate\Validation\Rule;
class AlbumController extends Controller {
 public function index(){ return view('admin.albums.index',['albums'=>Album::withCount('photos')->latest()->paginate(15)]); }
 public function create(){ return view('admin.albums.form',['album'=>new Album]); }
 public function edit(Album $album){ $album->load('photos');return view('admin.albums.form',compact('album')); }
 public function store(Request $r){ return $this->save($r,new Album); }
 public function update(Request $r,Album $album){ return $this->save($r,$album); }
 private function save(Request $r,Album $album){
  $data=$r->validate(['title'=>'required|string|max:180','slug'=>['nullable','string','max:200','regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',Rule::unique('albums')->ignore($album->id)],'description'=>'nullable|string|max:5000','event_date'=>'nullable|date','is_published'=>'nullable|boolean','photos'=>'nullable|array|max:20','photos.*'=>'required|image|mimes:jpg,jpeg,png,webp|max:5120']);
  if(empty($data['slug'])){ $base=Str::slug($data['title']) ?: 'album';$slug=$base;$n=2;while(Album::where('slug',$slug)->when($album->exists,fn($q)=>$q->where('id','!=',$album->id))->exists()){$slug=$base.'-'.$n++;}$data['slug']=$slug; }
  $data['is_published']=$r->boolean('is_published');unset($data['photos']);$paths=[];
  try{ DB::transaction(function()use($album,$data,$r,&$paths){$album->fill($data)->save();$order=(int)$album->photos()->max('sort_order');foreach($r->file('photos',[]) as $file){$paths[]=$path=$file->store('albums','public');$album->photos()->create(['path'=>$path,'sort_order'=>++$order]);}}); }catch(\Throwable $e){Storage::disk('public')->delete($paths);throw $e;}
  return redirect()->route('admin.albums.edit',$album)->with('success','Album enregistré.');
 }
 public function photo(Request $r,Album $album,Photo $photo){ abort_unless($photo->album_id===$album->id,404);$data=$r->validate(['caption'=>'nullable|string|max:255','sort_order'=>'required|integer|min:0|max:9999']);$photo->update($data);return back()->with('success','Photo mise à jour.'); }
 public function removePhoto(Album $album,Photo $photo){ abort_unless($photo->album_id===$album->id,404);$path=$photo->path;$photo->delete();Storage::disk('public')->delete($path);return back()->with('success','Photo supprimée.'); }
 public function destroy(Album $album){$paths=$album->photos()->pluck('path')->all();$album->delete();Storage::disk('public')->delete($paths);return redirect()->route('admin.albums.index')->with('success','Album supprimé.');}
}

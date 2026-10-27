<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class SettingController extends Controller {
 public function edit(){ return view('admin.settings',['settings'=>Setting::current()]); }
 public function update(Request $r){
  $data=$r->validate(['name'=>'required|string|max:180','tagline'=>'required|string|max:180','email'=>'required|email|max:255','phone_primary'=>'required|string|max:40','phone_secondary'=>'required|string|max:40','whatsapp'=>'required|regex:/^[1-9][0-9]{7,14}$/','orange_money'=>'required|regex:/^[0-9]{9,15}$/','mtn_money'=>'required|regex:/^[0-9]{9,15}$/','bicec'=>'required|string|max:100','scb'=>'required|string|max:100','hero_title'=>'required|string|max:180','hero_text'=>'required|string|max:1000','mission'=>'required|string|max:3000','hero_image'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:5120']);
  $old=Setting::current()['hero_image'];$new=null;unset($data['hero_image']);if($r->hasFile('hero_image')){$new=$r->file('hero_image')->store('site','public');$data['hero_image']=$new;}
  try{Setting::updateOrCreate(['id'=>1],['values'=>array_replace(Setting::current(),$data)]);}catch(\Throwable $e){if($new)Storage::disk('public')->delete($new);throw $e;}
  if($new && $old)Storage::disk('public')->delete($old);return back()->with('success','Le site a été mis à jour.');
 }
}

<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\{Post,Album,Setting};
class DatabaseSeeder extends Seeder {
 public function run(): void {
  $this->call(AdminUserSeeder::class);
  Setting::firstOrCreate(['id'=>1],['values'=>config('association')]);
  $slug='distribution-fournitures-scolaires-septembre-2025';
  if(!Post::where('slug',$slug)->exists()){
   $cover='articles/distribution-2025.webp';Storage::disk('public')->put($cover,file_get_contents(public_path('assets/1.webp')));
   Post::create(['title'=>'Une rentrée solidaire pour les enfants déplacés du NOSO','slug'=>$slug,'excerpt'=>'Le 14 septembre 2025, l’association a distribué des fournitures scolaires aux enfants déplacés internes du Nord-Ouest et du Sud-Ouest.','body'=>"Le dimanche 14 septembre 2025, Ayons le Cœur sur la Main a organisé une distribution de fournitures scolaires aux enfants déplacés internes du Nord-Ouest et du Sud-Ouest (NOSO).\n\nCette action s’inscrit dans notre engagement auprès des enfants et des personnes en situation de vulnérabilité.\n\nVous souhaitez contribuer aux prochaines actions ? Contactez l’association pour proposer un don en nature, un soutien financier ou votre temps.",'cover_path'=>$cover,'status'=>'published','published_at'=>'2025-09-14 12:00:00']);
  }
  if(!Album::where('slug','rentree-solidaire-2025')->exists()){
   $album=Album::create(['title'=>'Rentrée solidaire 2025','slug'=>'rentree-solidaire-2025','description'=>'Distribution de fournitures scolaires aux enfants déplacés internes du NOSO. Photos issues de l’ancien site de l’association.','event_date'=>'2025-09-14','is_published'=>true]);
   foreach([1=>'Enfants, familles et membres réunis.',4=>'Remise de fournitures scolaires.',8=>'Un geste solidaire pour la rentrée.',3=>'Une rencontre avec le fondateur.',6=>'Sur le lieu de l’action.'] as $id=>$caption){$path='albums/rentree-'.$id.'.webp';Storage::disk('public')->put($path,file_get_contents(public_path('assets/'.$id.'.webp')));$album->photos()->create(['path'=>$path,'caption'=>$caption,'sort_order'=>$id]);}
  }
 }
}

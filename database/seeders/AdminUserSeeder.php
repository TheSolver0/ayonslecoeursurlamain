<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
class AdminUserSeeder extends Seeder {
 public function run(): void {
  $email=env('ADMIN_EMAIL','admin@ayonslecoeursurlamain.com');
  $name=env('ADMIN_NAME','Administration');
  $user=User::where('email',$email)->first();
  if($user){$user->update(['name'=>$name,'is_admin'=>true]);return;}
  $password=env('ADMIN_PASSWORD','Ay0nslec0eursurl@m@in' );
  if(!$password){$this->command?->warn('ADMIN_PASSWORD absent : compte administrateur non créé.');return;}
  User::create(['name'=>$name,'email'=>$email,'password'=>$password,'is_admin'=>true]);
 }
}

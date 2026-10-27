<?php
namespace App\Console\Commands;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
class CreateAdmin extends Command {
 protected $signature='association:admin';
 protected $description='Créer un administrateur ou réinitialiser son mot de passe de manière interactive';
 public function handle(): int {
  $name=$this->ask('Nom de l’administrateur');$email=$this->ask('Adresse e-mail');$password=$this->secret('Mot de passe (12 caractères minimum)');$confirmation=$this->secret('Confirmer le mot de passe');
  $v=Validator::make(compact('name','email','password'),['name'=>'required|string|max:255','email'=>'required|email|max:255','password'=>'required|string|min:12|max:255']);
  if($v->fails() || $password!==$confirmation){$this->error($v->fails() ? $v->errors()->first() : 'Les mots de passe diffèrent.');return self::FAILURE;}
  $user=User::where('email',$email)->first();if($user && !$this->confirm('Ce compte existe. Réinitialiser son accès administrateur ?'))return self::FAILURE;
  User::updateOrCreate(['email'=>$email],['name'=>$name,'password'=>$password,'is_admin'=>true]);$this->info('Compte administrateur prêt. Connectez-vous sur /admin.');return self::SUCCESS;
 }
}

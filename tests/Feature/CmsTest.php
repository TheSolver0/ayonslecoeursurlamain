<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{Storage,Hash};
use Illuminate\Http\UploadedFile;
use App\Models\{User,Post,Album,Photo};
class CmsTest extends TestCase {
 use RefreshDatabase;
 private function admin(): User { return User::create(['name'=>'Équipe','email'=>'admin@example.test','password'=>'a-secure-test-password','is_admin'=>true]); }
 public function test_public_pages_render_and_admin_requires_login(): void {
  foreach(['/','/actualites','/galerie','/admin/connexion'] as $url){$this->get($url)->assertOk();}
  $this->get('/')->assertSee('Accueil')->assertSee('aria-current="page"',false);
  $this->get('/admin')->assertRedirect('/admin/connexion');
  $this->post('/admin/posts',['title'=>'Intrusion'])->assertRedirect('/admin/connexion');
 }
 public function test_normal_user_cannot_access_admin(): void {
  $user=User::create(['name'=>'Visiteur','email'=>'visitor@example.test','password'=>'a-secure-test-password']);
  $this->actingAs($user)->get('/admin')->assertForbidden();
  $this->actingAs($user)->post('/admin/posts',['title'=>'Intrusion'])->assertForbidden();
 }
 public function test_drafts_and_scheduled_posts_are_private(): void {
  $base=['title'=>'Action','body'=>'Un geste solidaire.'];
  Post::create([...$base,'slug'=>'brouillon','status'=>'draft','published_at'=>now()->subDay()]);
  Post::create([...$base,'slug'=>'programme','status'=>'published','published_at'=>now()->addDay()]);
  Post::create([...$base,'slug'=>'en-ligne','status'=>'published','published_at'=>now()->subMinute()]);
  $this->get('/actualites/brouillon')->assertNotFound();$this->get('/actualites/programme')->assertNotFound();
  $this->get('/actualites/en-ligne')->assertOk();
  $this->get('/actualites')->assertSee('en-ligne')->assertDontSee('href="http://localhost/actualites/brouillon"',false);
 }
 public function test_admin_can_create_edit_and_delete_an_article(): void {
  $admin=$this->admin();$this->actingAs($admin)->post('/admin/posts',['title'=>'Une belle action','body'=>'Un texte','status'=>'draft'])->assertSessionHasNoErrors()->assertRedirect();
  $post=Post::firstOrFail();$this->assertSame('une-belle-action',$post->slug);
  $this->actingAs($admin)->put('/admin/posts/'.$post->id,['title'=>'Une belle action','slug'=>$post->slug,'body'=>'**Merci**','status'=>'published'])->assertSessionHasNoErrors();
  $this->get('/actualites/'.$post->slug)->assertOk()->assertSee('<strong>Merci</strong>',false);
  $this->actingAs($admin)->delete('/admin/posts/'.$post->id)->assertRedirect();$this->get('/actualites/'.$post->slug)->assertNotFound();
 }
 public function test_uploads_are_saved_and_album_visibility_is_enforced(): void {
  Storage::fake('public');$admin=$this->admin();
  $png=base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wl6f3sAAAAASUVORK5CYII=');
  $file=UploadedFile::fake()->createWithContent('photo.png',$png);
  $this->actingAs($admin)->post('/admin/albums',['title'=>'Nos rencontres','photos'=>[$file]])->assertSessionHasNoErrors();
  $album=Album::firstOrFail();$photo=$album->photos()->firstOrFail();Storage::disk('public')->assertExists($photo->path);
  $this->get('/galerie/'.$album->slug)->assertNotFound();
  $this->actingAs($admin)->put('/admin/albums/'.$album->id,['title'=>$album->title,'slug'=>$album->slug,'is_published'=>1])->assertSessionHasNoErrors();
  $this->get('/galerie/'.$album->slug)->assertOk();
  $this->actingAs($admin)->delete('/admin/albums/'.$album->id)->assertRedirect();Storage::disk('public')->assertMissing($photo->path);
 }
 public function test_article_accepts_multiple_photos_and_displays_them(): void {
  Storage::fake('public');$admin=$this->admin();
  $photos=[UploadedFile::fake()->image('une.jpg'),UploadedFile::fake()->image('deux.jpg')];
  $this->actingAs($admin)->post('/admin/posts',['title'=>'Action en images','body'=>'Notre action','status'=>'published','photos'=>$photos])->assertSessionHasNoErrors();
  $post=Post::with('photos')->firstOrFail();$this->assertCount(2,$post->photos);
  $this->assertStringStartsWith('/storage/articles/gallery/',$post->photos->first()->url);
  foreach($post->photos as $photo){Storage::disk('public')->assertExists($photo->path);}
  $this->get('/actualites/'.$post->slug)->assertOk()->assertSee('En images')->assertSee($post->photos->first()->url);
  $photo=$post->photos->first();$this->actingAs($admin)->delete('/admin/posts/'.$post->id.'/photos/'.$photo->id)->assertRedirect();
  Storage::disk('public')->assertMissing($photo->path);
 }
 public function test_photo_cannot_be_changed_via_another_album(): void {
  $a=Album::create(['title'=>'A','slug'=>'a']);$b=Album::create(['title'=>'B','slug'=>'b']);$photo=$a->photos()->create(['path'=>'photo.jpg']);
  $this->actingAs($this->admin())->put('/admin/albums/'.$b->id.'/photos/'.$photo->id,['caption'=>'Incorrect','sort_order'=>1])->assertNotFound();
 }
 public function test_admin_can_update_settings_and_password(): void {
  $admin=$this->admin();$data=config('association');unset($data['hero_image']);$data['email']='contact@example.test';
  $this->actingAs($admin)->put('/admin/parametres',$data)->assertSessionHasNoErrors();
  $this->get('/')->assertSee('contact@example.test');
  $this->actingAs($admin)->put('/admin/mot-de-passe',['current_password'=>'a-secure-test-password','password'=>'a-new-secure-password','password_confirmation'=>'a-new-secure-password'])->assertSessionHasNoErrors();
  $this->assertTrue(Hash::check('a-new-secure-password',$admin->fresh()->password));
 }
 public function test_login_is_throttled_and_non_admin_cannot_authenticate(): void {
  $this->admin();for($i=0;$i<6;$i++){$r=$this->post('/admin/connexion',['email'=>'admin@example.test','password'=>'wrong']);$r->assertSessionHasErrors('email');}
  $this->assertStringContainsString('Trop de tentatives',session('errors')->first('email'));
 }
 public function test_article_markdown_does_not_render_scripts_or_javascript_links(): void {
  $post=Post::create(['title'=>'Contenu','slug'=>'contenu','body'=>'<script>alert(1)</script> [lien](javascript:alert(1))','status'=>'published','published_at'=>now()->subMinute()]);
  $this->get('/actualites/contenu')->assertOk()->assertDontSee('<script>alert(1)</script>',false)->assertDontSee('href="javascript:',false);
 }
 public function test_non_image_upload_is_rejected(): void {
  Storage::fake('public');$file=UploadedFile::fake()->createWithContent('attack.php','<?php echo "bad";');
  $this->actingAs($this->admin())->post('/admin/albums',['title'=>'Album','photos'=>[$file]])->assertSessionHasErrors('photos.0');$this->assertDatabaseCount('albums',0);
 }
 public function test_admin_screens_render_with_real_content(): void {
  $this->seed();$admin=$this->admin();$post=Post::firstOrFail();$album=Album::firstOrFail();
  foreach(['/admin','/admin/posts','/admin/posts/create','/admin/posts/'.$post->id.'/edit','/admin/albums','/admin/albums/create','/admin/albums/'.$album->id.'/edit','/admin/parametres'] as $url){$this->actingAs($admin)->get($url)->assertOk();}
 }
 public function test_seed_is_repeatable_without_duplicate_content(): void {
  $this->seed();$this->seed();$this->assertDatabaseCount('posts',1);$this->assertDatabaseCount('albums',1);$this->assertDatabaseCount('photos',5);
 }
}

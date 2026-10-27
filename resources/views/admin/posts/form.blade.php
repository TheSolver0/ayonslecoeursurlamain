@extends('layouts.admin')
@section('title',$post->exists ? 'Modifier une actualité':'Nouvelle actualité')
@section('heading',$post->exists ? 'Faites grandir votre histoire.':'Une nouvelle à partager.')
@section('intro','Un titre clair, quelques mots et de belles photos : c’est un bon début.')
@section('action')<a class="btn outline" href="{{ route('admin.posts.index') }}">← Toutes les actualités</a>@endsection
@section('content')
<form method="post" enctype="multipart/form-data" action="{{ $post->exists ? route('admin.posts.update',$post) : route('admin.posts.store') }}" class="editor-grid">
 @csrf
 @if($post->exists) @method('PUT') @endif
 <div>
  <section class="panel padded">
   <h2>Votre article</h2>
   <label for="title">Titre de l’actualité <span>*</span></label><input id="title" name="title" required maxlength="180" value="{{ old('title',$post->title) }}" placeholder="Ex. Une rentrée solidaire pour les enfants">
   <label for="excerpt">Résumé</label><textarea id="excerpt" name="excerpt" rows="3" maxlength="500" placeholder="Quelques lignes pour donner envie de lire…">{{ old('excerpt',$post->excerpt) }}</textarea><div class="field-tip">Affiché sur la page des actualités et sous le titre.</div>
   <label for="body">Contenu de l’article <span>*</span></label><div class="editor-toolbar" aria-label="Mise en forme"><button type="button" data-mark="bold"><b>Gras</b></button><button type="button" data-mark="italic"><em>Italique</em></button><button type="button" data-mark="heading">Titre</button><button type="button" data-mark="list">Liste</button><span>Texte Markdown</span></div><textarea class="body-editor" id="body" name="body" rows="18" required maxlength="100000" placeholder="Racontez votre action, les personnes rencontrées, les moments forts…">{{ old('body',$post->body) }}</textarea><div class="field-tip">Séparez les paragraphes par une ligne vide. Les boutons ajoutent une mise en forme simple.</div>
  </section>
  <details class="panel padded advanced"><summary>Adresse de la page (facultatif)</summary><label for="slug">Identifiant dans l’URL</label><input id="slug" name="slug" value="{{ old('slug',$post->slug) }}" maxlength="200" pattern="[a-z0-9]+(-[a-z0-9]+)*"><div class="field-tip">Laissez vide pour créer l’adresse à partir du titre. Lettres minuscules, chiffres et tirets.</div></details>
 </div>
 <div>
  <section class="panel padded"><h2>Publication</h2><label for="status">Visibilité</label><select name="status" id="status"><option value="draft" @selected(old('status',$post->status)==='draft')>Brouillon · visible uniquement ici</option><option value="published" @selected(old('status',$post->status)==='published')>Publié · visible sur le site</option></select><label for="published_at">Date et heure de publication</label><input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at',$post->published_at?->format('Y-m-d\TH:i')) }}"><div class="field-tip">Heure de Douala. Vide : publication immédiate si le statut est « Publié ».</div><button class="btn full save-button" type="submit">Enregistrer l’article ✓</button>@if($post->status==='published' && $post->published_at?->isPast())<a class="preview-link" target="_blank" rel="noopener" href="{{ route('article',$post->slug) }}">Voir la version publique ↗</a>@endif</section>
  <section class="panel padded"><h2>Photo principale</h2>@if($post->cover_path)<img class="cover-preview" src="{{ $post->cover_url }}" alt="Couverture actuelle"><label class="check"><input type="checkbox" name="remove_cover" value="1"> Retirer la photo actuelle</label>@endif<label for="cover">Choisir la couverture</label><input id="cover" type="file" name="cover" accept="image/jpeg,image/png,image/webp" data-preview="cover-preview"><img id="cover-preview" class="cover-preview" hidden alt="Aperçu de la nouvelle couverture"><div class="field-tip">JPG, PNG ou WebP · 5 Mo maximum. Privilégiez une photo horizontale.</div></section>
  <section class="panel padded"><h2>Photos supplémentaires</h2><label for="photos">Ajouter plusieurs photos</label><input id="photos" type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" data-files><div class="upload-hint" id="files-info">Sélectionnez jusqu’à 20 photos par envoi.<br>JPG, PNG ou WebP · 5 Mo maximum par photo.</div></section>
 </div>
</form>
@if($post->exists && $post->photos->isNotEmpty())
<section class="panel padded"><div class="panel-heading compact"><h2>Photos de l’article</h2><span>{{ $post->photos->count() }} photo(s)</span></div><div class="admin-photo-grid">@foreach($post->photos as $photo)<article class="photo-item"><img src="{{ $photo->url }}" alt="Photo supplémentaire de l’article"><form method="post" action="{{ route('admin.post-photos.destroy',[$post,$photo]) }}" data-confirm="Supprimer cette photo définitivement ?">@csrf @method('DELETE')<button class="danger-link">Supprimer cette photo</button></form></article>@endforeach</div></section>
@endif
@endsection

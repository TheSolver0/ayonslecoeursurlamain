@extends('layouts.site')
@section('title',$post->title.' — '.$site['name'])
@section('description',$post->excerpt ?: $post->title)
@section('content')
<article class="article wrap">
 <a class="text-link" href="{{ route('news') }}">← Toutes les actualités</a>
 <div class="eyebrow">{{ $post->published_at->format('d.m.Y') }} · SUR LE TERRAIN</div>
 <h1>{{ $post->title }}</h1>
 @if($post->excerpt)<p class="article-lead">{{ $post->excerpt }}</p>@endif
 <img class="article-cover" src="{{ $post->cover_url }}" alt="{{ $post->title }}">
 <div class="prose">{!! $post->html !!}</div>
 @if($post->photos->isNotEmpty())
  <section class="article-gallery" aria-labelledby="article-gallery-title">
   <h2 id="article-gallery-title">En images</h2>
   <div class="photo-grid">@foreach($post->photos as $photo)<figure><button class="photo-open" data-full="{{ $photo->url }}" data-caption="{{ $post->title }}" aria-label="Agrandir une photo de l’article"><img src="{{ $photo->url }}" alt="Photo de l’article {{ $post->title }}" loading="lazy"><span>↗</span></button></figure>@endforeach</div>
  </section>
 @endif
 <div class="article-end"><h3>Cette action vous donne envie d’aider ?</h3><p>Votre temps et votre solidarité peuvent faire la différence.</p><button class="button" data-donate>Je soutiens l’association ↗</button></div>
</article>
@endsection

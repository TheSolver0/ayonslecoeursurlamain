@extends('layouts.site')
@section('title',$album->title.' — '.$site['name'])
@section('content')
<section class="page-intro wrap"><a class="text-link" href="{{ route('gallery') }}">← Tous les albums</a><div class="eyebrow">{{ $album->photos->count() }} PHOTOS @if($album->event_date) · {{ $album->event_date->format('d.m.Y') }} @endif</div><h1>{{ $album->title }}</h1><p>{{ $album->description }}</p></section>
<section class="wrap listing"><div class="photo-grid">@forelse($album->photos as $photo)<figure><button class="photo-open" data-full="{{ $photo->url }}" data-caption="{{ $photo->caption ?: $album->title }}" aria-label="Agrandir : {{ $photo->caption ?: $album->title }}"><img src="{{ $photo->url }}" alt="{{ $photo->caption ?: $album->title }}" loading="lazy"><span>↗</span></button>@if($photo->caption)<figcaption>{{ $photo->caption }}</figcaption>@endif</figure>@empty<p>Les photos de cet album seront ajoutées prochainement.</p>@endforelse</div></section>
@endsection

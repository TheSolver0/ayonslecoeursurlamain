@extends('layouts.site')
@section('title','Galerie — '.$site['name'])
@section('content')
<section class="page-intro wrap"><div class="eyebrow">LES VISAGES DE LA SOLIDARITÉ</div><h1>Des rencontres.<br><em>Des souvenirs qui restent.</em></h1><p>Explorez nos albums et les moments partagés sur le terrain.</p></section>
<section class="wrap listing"><div class="news-grid">@forelse($albums as $album) @include('public.album-card') @empty <div class="empty-state"><h3>La galerie se prépare.</h3><p>Nos prochains albums seront bientôt disponibles.</p></div> @endforelse</div>{{ $albums->links() }}</section>
@endsection

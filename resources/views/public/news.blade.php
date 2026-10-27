@extends('layouts.site')
@section('title', 'Actualités — '.$site['name'])
@section('content')
<section class="page-intro wrap"><div class="eyebrow">LES NOUVELLES DU TERRAIN</div><h1>Des gestes.<br><em>Des histoires à partager.</em></h1><p>Retrouvez les actions et les nouvelles de notre association.</p></section>
<section class="wrap listing"><div class="news-grid">@forelse($posts as $post) @include('public.post-card') @empty <div class="empty-state"><h3>Les nouvelles arrivent bientôt.</h3><p>Revenez découvrir les prochaines actions de l’association.</p></div> @endforelse</div>{{ $posts->links() }}</section>
@endsection

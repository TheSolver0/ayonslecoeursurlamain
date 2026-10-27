<!doctype html>
<html lang="fr">
<head>
 <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow">
 <title>Connexion — {{ $site['name'] }}</title>
 <link rel="stylesheet" href="{{ asset('admin.css') }}"><link rel="stylesheet" href="{{ asset('icons.css') }}"><link rel="stylesheet" href="{{ asset('login.css') }}">
 <script src="{{ asset('icons.js') }}" defer></script><script src="{{ asset('login.js') }}" defer></script>
</head>
<body class="login-page">
 <div class="login-story">
  <a class="login-back" href="{{ route('home') }}">← Revenir au site</a>
  <div><img src="{{ asset('assets/logo.webp') }}" alt="Logo de l’association"><div class="eyebrow">L’ESPACE DE CEUX QUI AGISSENT</div><h1>Faites vivre<br><em>la solidarité.</em></h1><p>Une actualité, une photo, une nouvelle à partager.<br>Votre association continue de grandir ici.</p></div>
  <small>{{ $site['name'] }} · Depuis 2018</small>
 </div>
 <main class="login-main">
  <form action="{{ route('login.store') }}" method="post" class="login-form">
   @csrf
   <div class="eyebrow">BIENVENUE DANS VOTRE ESPACE</div><h2>Heureux de vous revoir.</h2><p>Connectez-vous pour mettre à jour le site.</p>
   @if($errors->any())<div class="notice error" role="alert">{{ $errors->first() }}</div>@endif
   <label for="email">Adresse e-mail</label><input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" autofocus>
   <label for="password">Mot de passe</label>
   <div class="password-field"><input id="password" type="password" name="password" required autocomplete="current-password"><button class="password-toggle" type="button" data-password-toggle aria-label="Afficher le mot de passe" aria-pressed="false"><svg class="eye" viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg><svg class="eye-off" viewBox="0 0 24 24" aria-hidden="true"><path d="m3 3 18 18M10.6 6.2A10 10 0 0 1 12 6c6 0 9.5 6 9.5 6a16 16 0 0 1-2.1 2.8M6.2 6.2C3.8 7.8 2.5 12 2.5 12s3.5 6 9.5 6a10 10 0 0 0 3.2-.5M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg></button></div>
   <button class="btn full">Me connecter <span>↗</span></button><p class="hint">Accès réservé à l’équipe de l’association. En cas d’oubli du mot de passe, contactez l’administrateur technique.</p>
  </form>
 </main>
</body>
</html>

<?php
return [
 'required'=>'Le champ :attribute est obligatoire.', 'email'=>'Le champ :attribute doit contenir une adresse e-mail valide.',
 'string'=>'Le champ :attribute doit être un texte.', 'image'=>'Le fichier :attribute doit être une image.',
 'mimes'=>'Le fichier :attribute doit être au format :values.', 'date'=>'Le champ :attribute doit être une date valide.',
 'boolean'=>'Le champ :attribute doit être vrai ou faux.', 'integer'=>'Le champ :attribute doit être un entier.',
 'regex'=>'Le format du champ :attribute n’est pas valide.', 'unique'=>'Cette valeur de :attribute est déjà utilisée.',
 'in'=>'La valeur de :attribute n’est pas valide.', 'confirmed'=>'La confirmation du champ :attribute ne correspond pas.',
 'current_password'=>'Le mot de passe actuel est incorrect.', 'array'=>'Le champ :attribute doit être une liste.',
 'uploaded'=>'Le téléversement de :attribute a échoué. Vérifiez la taille totale des fichiers autorisée par votre hébergement.',
 'max'=>['string'=>'Le champ :attribute ne doit pas dépasser :max caractères.','file'=>'Le fichier :attribute ne doit pas dépasser :max Ko.','array'=>'Le champ :attribute ne doit pas contenir plus de :max éléments.','numeric'=>'Le champ :attribute ne doit pas dépasser :max.'],
 'min'=>['string'=>'Le champ :attribute doit contenir au moins :min caractères.','numeric'=>'Le champ :attribute doit être au moins :min.'],
 'attributes'=>['title'=>'titre','body'=>'contenu','excerpt'=>'résumé','slug'=>'adresse de la page','password'=>'mot de passe','current_password'=>'mot de passe actuel','cover'=>'photo de couverture','photos'=>'photos','photos.*'=>'photo','name'=>'nom','email'=>'e-mail','published_at'=>'date de publication','event_date'=>'date de l’événement','whatsapp'=>'numéro WhatsApp','hero_image'=>'photo principale','hero_title'=>'titre de l’accueil','hero_text'=>'texte d’accueil','mission'=>'présentation','caption'=>'légende','sort_order'=>'ordre d’affichage'],
];

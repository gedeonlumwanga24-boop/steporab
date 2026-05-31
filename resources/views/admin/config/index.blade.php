@extends('layouts.admin')

@section('title', 'Configuration de la Page d\'Accueil')

@section('content')
<div class="admin-card" style="max-width: 900px;">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Paramètres du Hero (Bannière d'accueil)</h3>
    </div>
    
    <div style="padding: 1.5rem;">
        <form action="{{ route('admin.config.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Gauche: Textes -->
                <div>
                    <div class="admin-form-group">
                        <label class="admin-label">Slogan (Tagline)</label>
                        <input type="text" name="hero_tagline" class="admin-input" value="{{ $configs['hero_tagline'] ?? '' }}" placeholder="Ex: Nouvelle Collection">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-label">Titre Principal</label>
                        <textarea name="hero_title" class="admin-textarea" rows="3" placeholder="Ex: L'élégance à chaque pas.">{{ $configs['hero_title'] ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Droite: Image -->
                <div>
                    <div class="admin-form-group">
                        <label class="admin-label">Image de fond (Hero Image)</label>
                        
                        @if(isset($configs['hero_image']))
                            <div style="margin-bottom: 1rem;">
                                @php
                                    $img = $configs['hero_image'];
                                    $url = str_starts_with($img, 'http') ? $img : asset('storage/' . $img);
                                @endphp
                                <img src="{{ $url }}" alt="Hero current" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid var(--admin-border);">
                            </div>
                        @endif

                        <input type="file" name="hero_image" class="admin-input" accept="image/*">
                        <p style="font-size: 0.8rem; color: #6b7280; margin-top: 0.5rem;">L'upload d'une nouvelle image remplacera l'image actuelle.</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border); text-align: right;">
                <button type="submit" class="btn-primary-sm" style="padding: 0.75rem 2rem; font-size: 1rem;">
                    <i class="fa-solid fa-save" style="margin-right: 5px;"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Configuration Bannière Vidéo (Tendances) -->
<div class="admin-card" style="max-width: 900px; margin-top: 2rem;">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Paramètres de la Bannière Vidéo (Tendances)</h3>
    </div>
    
    <div style="padding: 1.5rem;">
        <form action="{{ route('admin.config.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Gauche: Textes -->
                <div>
                    <div class="admin-form-group">
                        <label class="admin-label">Slogan Vidéo (ex: Les essentiels de l'été)</label>
                        <input type="text" name="trending_tagline" class="admin-input" value="{{ $configs['trending_tagline'] ?? '' }}" placeholder="Ex: Les essentiels de l'été">
                    </div>

                    <div class="admin-form-group">
                        <label class="admin-label">Titre Vidéo (ex: Pour ceux qui bougent.)</label>
                        <textarea name="trending_title" class="admin-textarea" rows="3" placeholder="Ex: Pour ceux qui bougent.">{{ $configs['trending_title'] ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Droite: Vidéo -->
                <div>
                    <div class="admin-form-group">
                        <label class="admin-label">Vidéo de fond (MP4 recommandé)</label>
                        
                        @if(isset($configs['trending_video']))
                            <div style="margin-bottom: 1rem;">
                                @php
                                    $vid = $configs['trending_video'];
                                    $urlVid = str_starts_with($vid, 'http') ? $vid : asset('storage/' . $vid);
                                @endphp
                                <video src="{{ $urlVid }}" autoplay loop muted playsinline style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid var(--admin-border);"></video>
                            </div>
                        @endif

                        <input type="file" name="trending_video" class="admin-input" accept="video/mp4,video/webm">
                        <p style="font-size: 0.8rem; color: #6b7280; margin-top: 0.5rem;">L'upload d'une nouvelle vidéo remplacera la vidéo actuelle. Taille max recommandée: 15 Mo.</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border); text-align: right;">
                <button type="submit" class="btn-primary-sm" style="padding: 0.75rem 2rem; font-size: 1rem;">
                    <i class="fa-solid fa-save" style="margin-right: 5px;"></i> Enregistrer la bannière vidéo
                </button>
            </div>
        </form>
    </div>
</div>

<div class="admin-card" style="max-width: 900px; margin-top: 2rem;">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Aperçu des Animations</h3>
    </div>
    <div style="padding: 1.5rem;">
        <p>Les animations suivantes sont maintenant actives sur la page d'accueil :</p>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-check" style="color: #10b981; margin-right: 10px;"></i> <strong>Zoom progressif</strong> sur l'image de fond du Hero.</li>
            <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-check" style="color: #10b981; margin-right: 10px;"></i> <strong>Apparition en cascade</strong> (Fade in & Slide up) des textes du Hero.</li>
            <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-check" style="color: #10b981; margin-right: 10px;"></i> <strong>Révélation au défilement</strong> (Scroll Reveal) pour les sections Collections, Tendances et Valeurs.</li>
            <li style="margin-bottom: 0.5rem;"><i class="fa-solid fa-check" style="color: #10b981; margin-right: 10px;"></i> <strong>Bannière vidéo</strong> en arrière-plan dans la section Tendances.</li>
        </ul>
    </div>
</div>
@endsection


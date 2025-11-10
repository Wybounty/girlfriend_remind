# 🎨 Système Avatar Pixel Art avec Ollama

## 📋 Prérequis

### 1. Installer Ollama

Téléchargez et installez Ollama depuis : https://ollama.ai

### 2. Télécharger le modèle LLaVA

Ouvrez un terminal et exécutez :

```bash
ollama pull llava:latest
```

Le téléchargement peut prendre quelques minutes (environ 4-5GB).

### 3. Démarrer Ollama

```bash
ollama serve
```

Ollama sera accessible sur `http://localhost:11434`

## 🚀 Utilisation

### Créer un Avatar Pixel Art

1. Allez sur la fiche de votre girlfriend
2. Cliquez sur le bouton **"🎨 Créer Avatar Pixel Art"**
3. Uploadez une photo (PNG, JPG, max 10MB)
4. Choisissez la taille des pixels (8-32px)
   - 8px = Très pixelisé (style retro NES)
   - 16px = Pixelisé (style SNES)
   - 24px = Modéré (style GBA)
   - 32px = Détaillé (style DS)
5. Cliquez sur **"Créer l'avatar pixel art"**

### Processus de traitement

Le système va automatiquement :

1. ✨ **Analyser** la photo avec Ollama (IA locale)
2. 🎭 **Segmenter** pour isoler la personne du fond
3. 🎮 **Pixelater** l'image pour créer l'effet retro
4. 🎬 **Générer** 6 frames d'animation idle

⏱️ Le traitement prend environ 30-60 secondes.

## 🎯 Fonctionnalités

- **IA Locale** : Ollama tourne sur votre PC, aucune donnée n'est envoyée sur internet
- **Pixel Art Automatique** : Conversion automatique en style retro
- **Animations** : Génération automatique d'animations idle (6 frames)
- **Contrôles** : Play/Pause pour les animations
- **Téléchargement** : Téléchargez vos avatars créés

## 🔧 Configuration

Dans votre fichier `.env` :

```env
OLLAMA_URL=http://localhost:11434
OLLAMA_MODEL=llava:latest
```

## 📊 Modèles Ollama disponibles

- `llava:latest` (recommandé) - Modèle vision + langage
- `llava:7b` - Version légère
- `llava:13b` - Version plus précise

Pour changer de modèle :

```bash
ollama pull llava:7b
```

Puis modifiez `OLLAMA_MODEL=llava:7b` dans `.env`

## 🐛 Dépannage

### Ollama non connecté

**Symptôme** : Message d'avertissement "Ollama non connecté"

**Solution** :
1. Vérifiez qu'Ollama est démarré : `ollama serve`
2. Testez l'accès : `curl http://localhost:11434/api/tags`
3. Vérifiez que le port 11434 n'est pas bloqué

### Le traitement est trop lent

**Solutions** :
- Utilisez une photo plus petite (< 2MB)
- Utilisez `llava:7b` au lieu de `llava:latest`
- Augmentez la taille des pixels (moins de détails à traiter)

### Erreur mémoire

**Solution** :
- Fermez d'autres applications
- Utilisez une version plus légère : `ollama pull llava:7b`

## 🎨 Utilisation des avatars créés

Les avatars pixel art peuvent être utilisés pour :

- 🎮 **Sprites de jeu** - Utilisez dans vos projets de jeux 2D
- 💬 **Avatars Discord/Social** - Style retro unique
- 🖼️ **Fond d'écran** - Look nostalgique
- 🎨 **Éléments graphiques** - Pour vos designs

## 📁 Structure des fichiers

```
storage/app/public/avatars/
├── originals/          # Photos originales uploadées
├── segmented_*.png     # Photos avec fond retiré
├── pixel_*.png         # Versions pixel art
└── frames/
    └── frame_*.png     # Frames d'animation
```

## 🚀 Améliorations futures possibles

- [ ] Choix des couleurs dominantes
- [ ] Plus de styles de pixelation (dithering, etc.)
- [ ] Plus d'animations (marche, course, etc.)
- [ ] Export en spritesheet
- [ ] Meilleure segmentation avec d'autres modèles IA

## ❤️ Enjoy !

Créé avec amour pour conserver vos souvenirs précieux en pixel art ! 💕🎮


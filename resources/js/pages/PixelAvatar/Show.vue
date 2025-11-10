<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Avatar3DViewer from '@/components/Avatar3DViewer.vue';

interface Avatar {
    id: number;
    characteristics: {
        skin_tone: string;
        hair_color: string;
        hair_length: string;
        hair_style: string;
        eye_color: string;
        gender: string;
        age_range: string;
        body_type: string;
        clothing_color_1: string;
        clothing_color_2: string;
        accessories: string[];
    };
    avatar_type: string;
    created_at: string;
}

interface Girlfriend {
    id: number;
    prenom: string;
    nom: string;
}

const props = defineProps<{
    girlfriend: Girlfriend;
    avatar: Avatar;
}>();
</script>

<template>
    <Head :title="`Avatar de ${girlfriend.prenom}`" />
    
    <main class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-rose-50 p-4 sm:p-6 lg:p-8">
        <div class="container mx-auto max-w-6xl">
            <!-- En-tête -->
            <div class="flex items-center justify-between mb-6 sm:mb-8 flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <Link :href="`/girlfriends/${girlfriend.id}`" class="btn btn-circle btn-ghost">
                        ←
                    </Link>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary flex items-center gap-2">
                        <span class="text-3xl sm:text-4xl">🎮</span>
                        <span>Avatar 3D de {{ girlfriend.prenom }}</span>
                    </h1>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-1">
                <!-- Avatar 3D -->
                <div class="card bg-gradient-to-br from-purple-500 to-pink-500 text-white shadow-2xl">
                    <div class="card-body items-center p-4 sm:p-6">
                        <h2 class="card-title text-2xl mb-6">✨ Avatar 3D Interactif</h2>
                        
                        <!-- Viewer 3D -->
                        <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                            <Avatar3DViewer 
                                :characteristics="avatar.characteristics"
                                :width="700"
                                :height="550"
                            />
                        </div>

                        <p class="text-sm opacity-80 mt-4 text-center">
                            🚶 L'avatar se déplace dans sa chambre
                        </p>

                        <!-- Stats -->
                        <div class="stats stats-vertical lg:stats-horizontal shadow mt-6 bg-white/20 backdrop-blur-sm w-full">
                            <div class="stat">
                                <div class="stat-title text-white/80">Type</div>
                                <div class="stat-value text-white text-2xl">3D</div>
                            </div>
                            <div class="stat">
                                <div class="stat-title text-white/80">Créé le</div>
                                <div class="stat-value text-white text-lg">
                                    {{ new Date(avatar.created_at).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' }) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Caractéristiques détectées -->
                <div class="space-y-6">
                    <!-- Détails physiques -->
                    <div class="card bg-white/90 backdrop-blur-sm shadow-xl border border-pink-100">
                        <div class="card-body">
                            <h3 class="card-title text-xl mb-4">👤 Caractéristiques détectées</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 bg-gradient-to-r from-pink-50 to-rose-50 rounded-lg">
                                    <span class="font-semibold flex items-center gap-2">
                                        <span>👤</span>
                                        <span>Teint</span>
                                    </span>
                                    <span class="badge badge-primary">{{ avatar.characteristics.skin_tone }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center p-3 bg-gradient-to-r from-rose-50 to-pink-50 rounded-lg">
                                    <span class="font-semibold flex items-center gap-2">
                                        <span>💇</span>
                                        <span>Cheveux</span>
                                    </span>
                                    <span class="badge badge-secondary">{{ avatar.characteristics.hair_color }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center p-3 bg-gradient-to-r from-pink-50 to-rose-50 rounded-lg">
                                    <span class="font-semibold flex items-center gap-2">
                                        <span>✂️</span>
                                        <span>Longueur</span>
                                    </span>
                                    <span class="badge badge-accent">{{ avatar.characteristics.hair_length }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center p-3 bg-gradient-to-r from-rose-50 to-pink-50 rounded-lg">
                                    <span class="font-semibold flex items-center gap-2">
                                        <span>👁️</span>
                                        <span>Yeux</span>
                                    </span>
                                    <span class="badge badge-info">{{ avatar.characteristics.eye_color }}</span>
                                </div>

                                <div class="flex justify-between items-center p-3 bg-gradient-to-r from-pink-50 to-rose-50 rounded-lg">
                                    <span class="font-semibold flex items-center gap-2">
                                        <span>👕</span>
                                        <span>Couleurs vêtements</span>
                                    </span>
                                    <div class="flex gap-2">
                                        <div 
                                            class="w-8 h-8 rounded-full border-2 border-white shadow"
                                            :style="{ backgroundColor: avatar.characteristics.clothing_color_1 }"
                                        ></div>
                                        <div 
                                            class="w-8 h-8 rounded-full border-2 border-white shadow"
                                            :style="{ backgroundColor: avatar.characteristics.clothing_color_2 }"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Utilisation -->
                    <div class="card bg-white/90 backdrop-blur-sm shadow-xl border border-pink-100">
                        <div class="card-body">
                            <h3 class="card-title text-xl mb-4">💡 Avantages de l'avatar 3D</h3>
                            
                            <div class="space-y-3 text-sm">
                                <div class="flex items-start gap-3">
                                    <span class="text-2xl flex-shrink-0">🔄</span>
                                    <div>
                                        <p class="font-semibold">Interactif</p>
                                        <p class="text-base-content/70">Rotation automatique à 360°</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-3">
                                    <span class="text-2xl flex-shrink-0">🎨</span>
                                    <div>
                                        <p class="font-semibold">Personnalisé</p>
                                        <p class="text-base-content/70">Basé sur l'analyse IA de la photo</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-3">
                                    <span class="text-2xl flex-shrink-0">💻</span>
                                    <div>
                                        <p class="font-semibold">Performant</p>
                                        <p class="text-base-content/70">Rendu en temps réel avec Three.js</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <span class="text-2xl flex-shrink-0">🌐</span>
                                    <div>
                                        <p class="font-semibold">Compatible</p>
                                        <p class="text-base-content/70">Fonctionne sur tous les navigateurs</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card bg-gradient-to-br from-rose-500 to-pink-500 text-white shadow-xl">
                        <div class="card-body">
                            <h3 class="card-title mb-4">⚡ Actions</h3>
                            <div class="flex flex-col gap-3">
                                <Link 
                                    :href="`/girlfriends/${girlfriend.id}/pixel-avatar/create`"
                                    class="btn btn-lg bg-white text-pink-600 border-0 hover:bg-pink-50"
                                >
                                    <span class="text-xl">🎨</span>
                                    <span>Créer un nouveau</span>
                                </Link>
                                <Link 
                                    :href="`/girlfriends/${girlfriend.id}`"
                                    class="btn btn-lg bg-white/20 border-white/40 hover:bg-white/30"
                                >
                                    <span class="text-xl">👤</span>
                                    <span>Retour au profil</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

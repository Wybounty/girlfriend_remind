<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { route } from '@/utils/route';

interface Girlfriend {
    id: number;
    prenom: string;
    nom: string;
    surnom: string;
    date_anniversaire: string;
    signe_astro: string;
    allergie: string;
    date_rencontre: string;
    nom_doudou: string;
    plat_prefere: string;
}

defineProps<{
    girlfriends: Girlfriend[];
}>();

// Fonction pour afficher une date sans problème de fuseau horaire
const formatDateLocal = (dateString: string): string => {
    if (!dateString) return '';
    // Parser la date comme date locale (pas UTC)
    const [year, month, day] = dateString.substring(0, 10).split('-');
    const date = new Date(parseInt(year), parseInt(month) - 1, parseInt(day));
    return date.toLocaleDateString('fr-FR');
};
</script>

<template>
    <Head title="Ma Girlfriend" />
    
    <main class="min-h-screen bg-gradient-to-br from-pink-50 via-rose-50 to-red-50 p-4 sm:p-6 lg:p-8">
        <div class="container mx-auto max-w-6xl">
            <!-- En-tête avec bouton retour mobile -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 sm:mb-8">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <Link href="/" class="btn btn-circle btn-ghost btn-sm sm:btn-md">
                        ←
                    </Link>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary flex items-center gap-2">
                        <span class="text-3xl sm:text-4xl">💕</span>
                        <span>Ma Girlfriend</span>
                    </h1>
                </div>
                <Link :href="route('girlfriends.create')" class="btn btn-primary w-full sm:w-auto shadow-lg hover:shadow-xl transition-shadow">
                    <span class="text-lg">➕</span>
                    <span>Ajouter</span>
                </Link>
            </div>

            <!-- État vide avec design amélioré -->
            <div v-if="girlfriends.length === 0" class="card bg-white/80 backdrop-blur-sm shadow-2xl border border-pink-100 p-6 sm:p-8 md:p-12 text-center">
                <div class="text-6xl sm:text-7xl md:text-8xl mb-4 sm:mb-6 animate-pulse">💔</div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-semibold mb-3 sm:mb-4 text-primary">
                    Aucune information enregistrée
                </h2>
                <p class="text-sm sm:text-base text-base-content/70 mb-6 sm:mb-8 max-w-md mx-auto">
                    Commencez par ajouter les informations de votre girlfriend pour ne rien oublier d'important !
                </p>
                <Link :href="route('girlfriends.create')" class="btn btn-primary btn-lg btn-wide shadow-lg hover:shadow-xl transition-all hover:scale-105">
                    <span class="text-xl">✨</span>
                    <span>Commencer maintenant</span>
                </Link>
            </div>

            <!-- Grille de cards responsive -->
            <div v-else class="grid gap-4 sm:gap-6 grid-cols-1 lg:grid-cols-2">
                <div v-for="girlfriend in girlfriends" :key="girlfriend.id" 
                     class="card bg-white/90 backdrop-blur-sm shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-[1.02] border border-pink-100 group">
                    <div class="card-body p-4 sm:p-6">
                        <!-- En-tête de la card -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 mb-4">
                            <h2 class="card-title text-xl sm:text-2xl flex-wrap">
                                <span>{{ girlfriend.prenom }} {{ girlfriend.nom }}</span>
                            </h2>
                            <span class="badge badge-secondary badge-lg px-4 py-3 text-sm whitespace-nowrap">
                                {{ girlfriend.surnom }}
                            </span>
                        </div>
                        
                        <!-- Informations avec icônes -->
                        <div class="space-y-3 my-4">
                            <div class="flex items-start sm:items-center gap-3 p-3 rounded-lg bg-pink-50 hover:bg-pink-100 transition-colors">
                                <span class="text-2xl flex-shrink-0">🎂</span>
                                <span class="text-sm sm:text-base break-words">{{ formatDateLocal(girlfriend.date_anniversaire) }}</span>
                            </div>
                            
                            <div class="flex items-start sm:items-center gap-3 p-3 rounded-lg bg-rose-50 hover:bg-rose-100 transition-colors">
                                <span class="text-2xl flex-shrink-0">⭐</span>
                                <span class="text-sm sm:text-base">{{ girlfriend.signe_astro }}</span>
                            </div>
                            
                            <div class="flex items-start sm:items-center gap-3 p-3 rounded-lg bg-pink-50 hover:bg-pink-100 transition-colors">
                                <span class="text-2xl flex-shrink-0">💝</span>
                                <span class="text-sm sm:text-base break-words">Ensemble depuis le {{ formatDateLocal(girlfriend.date_rencontre) }}</span>
                            </div>
                            
                            <div class="flex items-start sm:items-center gap-3 p-3 rounded-lg bg-rose-50 hover:bg-rose-100 transition-colors">
                                <span class="text-2xl flex-shrink-0">🧸</span>
                                <span class="text-sm sm:text-base break-words"><strong>Doudou :</strong> {{ girlfriend.nom_doudou }}</span>
                            </div>
                            
                            <div class="flex items-start sm:items-center gap-3 p-3 rounded-lg bg-pink-50 hover:bg-pink-100 transition-colors">
                                <span class="text-2xl flex-shrink-0">🍽️</span>
                                <span class="text-sm sm:text-base break-words"><strong>Plat préféré :</strong> {{ girlfriend.plat_prefere }}</span>
                            </div>
                        </div>

                        <!-- Actions avec design amélioré -->
                        <div class="card-actions justify-end mt-6 flex-wrap gap-2">
                            <Link :href="route('girlfriends.show', girlfriend.id)" 
                                  class="btn btn-info btn-sm sm:btn-md flex-1 sm:flex-none shadow-md hover:shadow-lg transition-shadow">
                                <span class="text-lg">👁️</span>
                                <span class="hidden sm:inline">Voir</span>
                            </Link>
                            <Link :href="route('girlfriends.edit', girlfriend.id)" 
                                  class="btn btn-warning btn-sm sm:btn-md flex-1 sm:flex-none shadow-md hover:shadow-lg transition-shadow">
                                <span class="text-lg">✏️</span>
                                <span class="hidden sm:inline">Modifier</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>


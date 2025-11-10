<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { route } from '@/utils/route';

interface GirlfriendInfo {
    id: number;
    titre: string;
    reponses: string;
    created_at: string;
}

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
    infos?: GirlfriendInfo[];
}

const props = defineProps<{
    girlfriend: Girlfriend;
    age: number;
    jours_ensemble: number;
}>();

const showDeleteModal = ref(false);

const deleteGirlfriend = () => {
    router.delete(route('girlfriends.destroy', props.girlfriend.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
        }
    });
};

const deleteInfo = (infoId: number) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette information ?')) {
        router.delete(`/girlfriends/${props.girlfriend.id}/infos/${infoId}`, {
            preserveScroll: true,
        });
    }
};

// Fonction pour afficher une date sans problème de fuseau horaire
const formatDateLocal = (dateString: string): string => {
    if (!dateString) return '';
    // Parser la date comme date locale (pas UTC) en extrayant année, mois, jour
    const [year, month, day] = dateString.substring(0, 10).split('-');
    const date = new Date(parseInt(year), parseInt(month) - 1, parseInt(day));
    
    return date.toLocaleDateString('fr-FR', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
};
</script>

<template>
    <Head :title="`${girlfriend.prenom} ${girlfriend.nom}`" />
    
    <main class="min-h-screen bg-base-200 p-8">
        <div class="container mx-auto max-w-4xl">
            <div class="flex items-center gap-4 mb-8">
                <Link :href="route('girlfriends.index')" class="btn btn-circle btn-ghost">
                    ← 
                </Link>
                <h1 class="text-4xl font-bold text-primary flex-1">
                    💕 {{ girlfriend.prenom }} {{ girlfriend.nom }}
                </h1>
                <div class="flex gap-2">
                    <Link :href="route('girlfriends.edit', girlfriend.id)" class="btn btn-warning">
                        ✏️ Modifier
                    </Link>
                    <button @click="showDeleteModal = true" class="btn btn-error">
                        🗑️ Supprimer
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-3 mb-6">
                <div class="card bg-gradient-to-br from-primary to-secondary text-primary-content shadow-xl">
                    <div class="card-body text-center">
                        <div class="text-5xl mb-2">🎂</div>
                        <h3 class="text-3xl font-bold">{{ age }} ans</h3>
                        <p class="opacity-80">Son âge</p>
                    </div>
                </div>

                <div class="card bg-gradient-to-br from-accent to-info text-accent-content shadow-xl">
                    <div class="card-body text-center">
                        <div class="text-5xl mb-2">💝</div>
                        <h3 class="text-3xl font-bold">{{ jours_ensemble }}</h3>
                        <p class="opacity-80">Jours ensemble</p>
                    </div>
                </div>

                <div class="card bg-gradient-to-br from-success to-warning text-success-content shadow-xl">
                    <div class="card-body text-center">
                        <div class="text-5xl mb-2">⭐</div>
                        <h3 class="text-2xl font-bold">{{ girlfriend.signe_astro }}</h3>
                        <p class="opacity-80">Signe astro</p>
                    </div>
                </div>
            </div>

            <!-- Détails complets -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-6">📋 Informations complètes</h2>
                    
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Surnom -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-lg">💖 Surnom</span>
                            </label>
                            <div class="bg-base-200 p-4 rounded-lg">
                                <p class="text-lg">{{ girlfriend.surnom }}</p>
                            </div>
                        </div>

                        <!-- Date d'anniversaire -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-lg">🎂 Date d'anniversaire</span>
                            </label>
                            <div class="bg-base-200 p-4 rounded-lg">
                                <p class="text-lg">{{ formatDateLocal(girlfriend.date_anniversaire) }}</p>
                            </div>
                        </div>

                        <!-- Date de rencontre -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-lg">💝 Date de rencontre</span>
                            </label>
                            <div class="bg-base-200 p-4 rounded-lg">
                                <p class="text-lg">{{ formatDateLocal(girlfriend.date_rencontre) }}</p>
                            </div>
                        </div>

                        <!-- Nom du doudou -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-lg">🧸 Nom du doudou</span>
                            </label>
                            <div class="bg-base-200 p-4 rounded-lg">
                                <p class="text-lg">{{ girlfriend.nom_doudou }}</p>
                            </div>
                        </div>

                        <!-- Plat préféré -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold text-lg">🍽️ Plat préféré</span>
                            </label>
                            <div class="bg-base-200 p-4 rounded-lg">
                                <p class="text-lg">{{ girlfriend.plat_prefere }}</p>
                            </div>
                        </div>

                        <!-- Allergies -->
                        <div class="form-control md:col-span-2">
                            <label class="label">
                                <span class="label-text font-semibold text-lg">⚠️ Allergies</span>
                            </label>
                            <div class="bg-base-200 p-4 rounded-lg">
                                <p class="text-lg whitespace-pre-wrap">{{ girlfriend.allergie }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations supplémentaires -->
            <div class="card bg-base-100 shadow-xl mt-6">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="card-title text-2xl">📝 Informations supplémentaires</h2>
                        <Link 
                            :href="`/girlfriends/${girlfriend.id}/infos/create`" 
                            class="btn btn-primary btn-sm"
                        >
                            ➕ Ajouter une info
                        </Link>
                    </div>

                    <div v-if="!girlfriend.infos || girlfriend.infos.length === 0" class="text-center py-8 text-base-content/60">
                        <div class="text-4xl mb-3">📋</div>
                        <p>Aucune information supplémentaire pour le moment</p>
                        <Link 
                            :href="`/girlfriends/${girlfriend.id}/infos/create`" 
                            class="btn btn-primary btn-sm mt-4"
                        >
                            Ajouter la première info
                        </Link>
                    </div>

                    <div v-else class="grid gap-4">
                        <div 
                            v-for="info in girlfriend.infos" 
                            :key="info.id"
                            class="card bg-base-200"
                        >
                            <div class="card-body">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-semibold text-lg">💡 {{ info.titre }}</h3>
                                    <div class="flex gap-2">
                                        <Link 
                                            :href="`/girlfriends/${girlfriend.id}/infos/${info.id}/edit`" 
                                            class="btn btn-warning btn-xs"
                                        >
                                            ✏️
                                        </Link>
                                        <button 
                                            @click="() => deleteInfo(info.id)" 
                                            class="btn btn-error btn-xs"
                                        >
                                            🗑️
                                        </button>
                                    </div>
                                </div>
                                <p class="whitespace-pre-wrap mt-2">{{ info.reponses }}</p>
                                <div class="text-xs text-base-content/50 mt-2">
                                    Ajouté le {{ new Date(info.created_at).toLocaleDateString('fr-FR') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal de confirmation de suppression -->
    <dialog :class="{ 'modal modal-open': showDeleteModal }" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">⚠️ Confirmer la suppression</h3>
            <p class="py-4">Êtes-vous sûr de vouloir supprimer ces informations ? Cette action est irréversible.</p>
            <div class="modal-action">
                <button @click="showDeleteModal = false" class="btn btn-ghost">
                    Annuler
                </button>
                <button @click="deleteGirlfriend" class="btn btn-error">
                    Supprimer définitivement
                </button>
            </div>
        </div>
        <div class="modal-backdrop" @click="showDeleteModal = false"></div>
    </dialog>
</template>


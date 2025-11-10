<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

interface Girlfriend {
    id: number;
    prenom: string;
    nom: string;
}

interface GirlfriendInfo {
    id: number;
    titre: string;
    reponses: string;
}

const props = defineProps<{
    girlfriend: Girlfriend;
    info: GirlfriendInfo;
}>();

const form = useForm({
    titre: props.info.titre,
    reponses: props.info.reponses,
});

const submit = () => {
    form.put(`/girlfriends/${props.girlfriend.id}/infos/${props.info.id}`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Modifier - ${info.titre}`" />
    
    <main class="min-h-screen bg-base-200 p-8">
        <div class="container mx-auto max-w-3xl">
            <div class="flex items-center gap-4 mb-8">
                <Link :href="`/girlfriends/${girlfriend.id}`" class="btn btn-circle btn-ghost">
                    ← 
                </Link>
                <h1 class="text-4xl font-bold text-primary">✏️ Modifier l'information</h1>
            </div>

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="mb-4 p-4 bg-base-200 rounded-lg">
                        <p class="text-sm text-base-content/70">
                            Modification pour <strong>{{ girlfriend.prenom }}</strong>
                        </p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Titre -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">💡 Titre *</span>
                            </label>
                            <input 
                                v-model="form.titre" 
                                type="text" 
                                placeholder="Ex: Couleur préférée, Films préférés, Amies proches..."
                                class="input input-bordered input-lg"
                                :class="{ 'input-error': form.errors.titre }"
                                required
                            />
                            <label v-if="form.errors.titre" class="label">
                                <span class="label-text-alt text-error">{{ form.errors.titre }}</span>
                            </label>
                        </div>

                        <!-- Réponses -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">💭 Réponses / Détails *</span>
                            </label>
                            <textarea 
                                v-model="form.reponses" 
                                placeholder="Notez ici les réponses ou informations détaillées..."
                                class="textarea textarea-bordered h-64"
                                :class="{ 'textarea-error': form.errors.reponses }"
                                required
                            ></textarea>
                            <label v-if="form.errors.reponses" class="label">
                                <span class="label-text-alt text-error">{{ form.errors.reponses }}</span>
                            </label>
                        </div>

                        <!-- Boutons -->
                        <div class="card-actions justify-end pt-6">
                            <Link :href="`/girlfriends/${girlfriend.id}`" class="btn btn-ghost">
                                Annuler
                            </Link>
                            <button 
                                type="submit" 
                                class="btn btn-primary"
                                :class="{ 'loading': form.processing }"
                                :disabled="form.processing"
                            >
                                💾 Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</template>


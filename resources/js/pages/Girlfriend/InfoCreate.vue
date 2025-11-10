<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

interface Girlfriend {
    id: number;
    prenom: string;
    nom: string;
}

const props = defineProps<{
    girlfriend: Girlfriend;
}>();

const form = useForm({
    titre: '',
    reponses: '',
});

const submit = () => {
    form.post(`/girlfriends/${props.girlfriend.id}/infos`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Ajouter une information - ${girlfriend.prenom}`" />
    
    <main class="min-h-screen bg-base-200 p-8">
        <div class="container mx-auto max-w-3xl">
            <div class="flex items-center gap-4 mb-8">
                <Link :href="`/girlfriends/${girlfriend.id}`" class="btn btn-circle btn-ghost">
                    ← 
                </Link>
                <h1 class="text-4xl font-bold text-primary">📝 Nouvelle information</h1>
            </div>

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="mb-4 p-4 bg-base-200 rounded-lg">
                        <p class="text-sm text-base-content/70">
                            Ajout d'une information pour <strong>{{ girlfriend.prenom }}</strong>
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
                                placeholder="Notez ici les réponses ou informations détaillées...&#10;&#10;Exemples:&#10;- Rose pâle&#10;- Elle adore les comédies romantiques&#10;- Sarah, Emma, Julie (depuis le lycée)"
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
                                💾 Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</template>


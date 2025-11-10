<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Girlfriend {
    id: number;
    prenom: string;
    nom: string;
}

const props = defineProps<{
    girlfriend: Girlfriend;
    ollamaConnected: boolean;
    availableModels: any[];
}>();

const form = useForm({
    photo: null as File | null,
});

const previewUrl = ref<string | null>(null);
const processing = ref(false);

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    
    if (file) {
        form.photo = file;
        
        // Créer un aperçu
        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    processing.value = true;
    form.post(`/girlfriends/${props.girlfriend.id}/pixel-avatar`, {
        onFinish: () => {
            processing.value = false;
        },
    });
};
</script>

<template>
    <Head title="Créer un avatar pixel art" />
    
    <main class="min-h-screen bg-gradient-to-br from-pink-50 via-rose-50 to-red-50 p-4 sm:p-6 lg:p-8">
        <div class="container mx-auto max-w-4xl">
            <!-- En-tête -->
            <div class="flex items-center gap-4 mb-6 sm:mb-8">
                <Link :href="`/girlfriends/${girlfriend.id}`" class="btn btn-circle btn-ghost">
                    ←
                </Link>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary flex items-center gap-2">
                    <span class="text-3xl sm:text-4xl">🎨</span>
                    <span>Avatar 3D</span>
                </h1>
            </div>

            <!-- Statut Ollama -->
            <div v-if="!ollamaConnected" class="alert alert-warning shadow-lg mb-6">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h3 class="font-bold">Ollama non connecté</h3>
                        <div class="text-xs">Assurez-vous qu'Ollama est démarré sur votre PC (http://localhost:11434)</div>
                        <div class="text-xs mt-1">Commande: <code class="bg-base-200 px-2 py-1 rounded">ollama serve</code></div>
                    </div>
                </div>
            </div>

            <div v-else class="alert alert-success shadow-lg mb-6">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h3 class="font-bold">Ollama connecté !</h3>
                        <div class="text-xs">Modèles disponibles: {{ availableModels.length }}</div>
                    </div>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="card bg-white/90 backdrop-blur-sm shadow-2xl border border-pink-100">
                <div class="card-body p-4 sm:p-6 md:p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Upload photo -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-lg">📸 Photo de {{ girlfriend.prenom }}</span>
                            </label>
                            
                            <div class="flex flex-col items-center gap-4">
                                <!-- Aperçu -->
                                <div v-if="previewUrl" class="relative w-full max-w-md aspect-square rounded-2xl overflow-hidden border-4 border-pink-200 shadow-lg">
                                    <img :src="previewUrl" class="w-full h-full object-cover" alt="Aperçu" />
                                </div>

                                <!-- Zone de drop -->
                                <label class="w-full max-w-md cursor-pointer">
                                    <div class="border-4 border-dashed border-pink-300 rounded-2xl p-8 sm:p-12 text-center hover:border-pink-500 hover:bg-pink-50/50 transition-all">
                                        <div class="text-6xl mb-4">🖼️</div>
                                        <p class="text-lg font-semibold mb-2">Cliquez ou glissez une photo</p>
                                        <p class="text-sm text-base-content/60">PNG, JPG jusqu'à 10MB</p>
                                    </div>
                                    <input 
                                        type="file" 
                                        @change="handleFileChange" 
                                        accept="image/*"
                                        class="hidden"
                                        required
                                    />
                                </label>
                            </div>

                            <label v-if="form.errors.photo" class="label">
                                <span class="label-text-alt text-error">{{ form.errors.photo }}</span>
                            </label>
                        </div>

                        <!-- Boutons -->
                        <div class="card-actions justify-end pt-6 flex-wrap gap-3">
                            <Link :href="`/girlfriends/${girlfriend.id}`" class="btn btn-ghost">
                                Annuler
                            </Link>
                            <button 
                                type="submit" 
                                class="btn btn-primary btn-lg shadow-lg"
                                :class="{ 'loading': processing }"
                                :disabled="!form.photo || !ollamaConnected || processing"
                            >
                                <span v-if="!processing" class="text-xl">✨</span>
                                <span>{{ processing ? 'Analyse en cours...' : 'Créer l\'avatar 3D' }}</span>
                            </button>
                        </div>

                        <!-- Info traitement -->
                        <div v-if="processing" class="alert alert-info">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current flex-shrink-0 w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="font-bold">Analyse en cours</h3>
                                    <div class="text-xs">Ollama analyse les caractéristiques physiques de la personne... Cela peut prendre 30-60 secondes.</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Explications -->
            <div class="card bg-white/60 backdrop-blur-sm border border-pink-100 mt-6">
                <div class="card-body p-4 sm:p-6">
                    <h3 class="font-bold text-lg mb-3 flex items-center gap-2">
                        <span>ℹ️</span>
                        <span>Comment ça marche ?</span>
                    </h3>
                    <ol class="list-decimal list-inside space-y-2 text-sm">
                        <li><strong>Ollama analyse</strong> la photo avec l'IA pour extraire les caractéristiques</li>
                        <li><strong>Détection automatique</strong> : couleur de peau, cheveux, yeux, vêtements</li>
                        <li><strong>Génération 3D</strong> d'un avatar personnalisé avec Three.js 🎮</li>
                        <li><strong>Avatar interactif</strong> que vous pouvez faire pivoter à 360°</li>
                    </ol>
                </div>
            </div>
        </div>
    </main>
</template>


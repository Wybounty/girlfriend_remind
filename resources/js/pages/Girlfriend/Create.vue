<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from '@/utils/route';

const form = useForm({
    prenom: '',
    nom: '',
    surnom: '',
    date_anniversaire: '',
    allergie: '',
    date_rencontre: '',
    nom_doudou: '',
    plat_prefere: '',
});

const submit = () => {
    form.post(route('girlfriends.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Ajouter ma Girlfriend" />
    
    <main class="min-h-screen bg-base-200 p-8">
        <div class="container mx-auto max-w-3xl">
            <div class="flex items-center gap-4 mb-8">
                <Link :href="route('girlfriends.index')" class="btn btn-circle btn-ghost">
                    ← 
                </Link>
                <h1 class="text-4xl font-bold text-primary">💕 Ajouter les infos</h1>
            </div>

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Prénom -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">👤 Prénom *</span>
                            </label>
                            <input 
                                v-model="form.prenom" 
                                type="text" 
                                placeholder="Ex: Marie"
                                class="input input-bordered"
                                :class="{ 'input-error': form.errors.prenom }"
                                required
                            />
                            <label v-if="form.errors.prenom" class="label">
                                <span class="label-text-alt text-error">{{ form.errors.prenom }}</span>
                            </label>
                        </div>

                        <!-- Nom -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">👤 Nom *</span>
                            </label>
                            <input 
                                v-model="form.nom" 
                                type="text" 
                                placeholder="Ex: Dupont"
                                class="input input-bordered"
                                :class="{ 'input-error': form.errors.nom }"
                                required
                            />
                            <label v-if="form.errors.nom" class="label">
                                <span class="label-text-alt text-error">{{ form.errors.nom }}</span>
                            </label>
                        </div>

                        <!-- Surnom -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">💖 Surnom *</span>
                            </label>
                            <input 
                                v-model="form.surnom" 
                                type="text" 
                                placeholder="Ex: Mon amour, Chérie..."
                                class="input input-bordered"
                                :class="{ 'input-error': form.errors.surnom }"
                                required
                            />
                            <label v-if="form.errors.surnom" class="label">
                                <span class="label-text-alt text-error">{{ form.errors.surnom }}</span>
                            </label>
                        </div>

                        <!-- Date d'anniversaire -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">🎂 Date d'anniversaire *</span>
                            </label>
                            <input 
                                v-model="form.date_anniversaire" 
                                type="date" 
                                class="input input-bordered"
                                :class="{ 'input-error': form.errors.date_anniversaire }"
                                required
                            />
                            <label class="label">
                                <span class="label-text-alt">Le signe astrologique sera calculé automatiquement ⭐</span>
                            </label>
                            <label v-if="form.errors.date_anniversaire" class="label">
                                <span class="label-text-alt text-error">{{ form.errors.date_anniversaire }}</span>
                            </label>
                        </div>

                        <!-- Allergies -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">⚠️ Allergies *</span>
                            </label>
                            <textarea 
                                v-model="form.allergie" 
                                placeholder="Ex: Arachides, lactose, pollen..."
                                class="textarea textarea-bordered h-24"
                                :class="{ 'textarea-error': form.errors.allergie }"
                                required
                            ></textarea>
                            <label v-if="form.errors.allergie" class="label">
                                <span class="label-text-alt text-error">{{ form.errors.allergie }}</span>
                            </label>
                        </div>

                        <!-- Date de rencontre -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">💝 Date de rencontre *</span>
                            </label>
                            <input 
                                v-model="form.date_rencontre" 
                                type="date" 
                                class="input input-bordered"
                                :class="{ 'input-error': form.errors.date_rencontre }"
                                required
                            />
                            <label v-if="form.errors.date_rencontre" class="label">
                                <span class="label-text-alt text-error">{{ form.errors.date_rencontre }}</span>
                            </label>
                        </div>

                        <!-- Nom du doudou -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">🧸 Nom du doudou *</span>
                            </label>
                            <input 
                                v-model="form.nom_doudou" 
                                type="text" 
                                placeholder="Ex: Mr. Ours"
                                class="input input-bordered"
                                :class="{ 'input-error': form.errors.nom_doudou }"
                                required
                            />
                            <label v-if="form.errors.nom_doudou" class="label">
                                <span class="label-text-alt text-error">{{ form.errors.nom_doudou }}</span>
                            </label>
                        </div>

                        <!-- Plat préféré -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">🍽️ Plat préféré *</span>
                            </label>
                            <input 
                                v-model="form.plat_prefere" 
                                type="text" 
                                placeholder="Ex: Lasagnes, Sushi..."
                                class="input input-bordered"
                                :class="{ 'input-error': form.errors.plat_prefere }"
                                required
                            />
                            <label v-if="form.errors.plat_prefere" class="label">
                                <span class="label-text-alt text-error">{{ form.errors.plat_prefere }}</span>
                            </label>
                        </div>

                        <!-- Boutons -->
                        <div class="card-actions justify-end pt-6">
                            <Link :href="route('girlfriends.index')" class="btn btn-ghost">
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


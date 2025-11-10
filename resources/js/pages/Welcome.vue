<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Girlfriend Remind">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <main class="w-full min-h-screen bg-gradient-to-br from-pink-50 via-rose-50 to-red-50 flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="container max-w-6xl w-full">
            <!-- En-tête avec animation -->
            <div class="text-center mb-8 sm:mb-12 animate-fade-in">
                <div class="mb-4 sm:mb-6">
                    <span class="text-6xl sm:text-7xl md:text-8xl animate-bounce inline-block">💕</span>
                </div>
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-primary mb-3 sm:mb-4">
                    Girlfriend Remind
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-base-content/70 max-w-2xl mx-auto px-4">
                    Gardez précieusement toutes les informations importantes sur celle qui compte pour vous
                </p>
            </div>

            <!-- Card principale avec effet 3D -->
            <div class="flex justify-center mb-6 sm:mb-8 px-4">
                <Link href="/girlfriends" 
                      class="card bg-gradient-to-br from-pink-500 via-pink-400 to-rose-500 text-white shadow-2xl hover:shadow-[0_20px_60px_-15px_rgba(236,72,153,0.5)] transition-all duration-300 hover:scale-105 hover:-translate-y-2 cursor-pointer w-full max-w-2xl group">
                    <div class="card-body items-center text-center p-6 sm:p-8 md:p-12">
                        <div class="text-6xl sm:text-7xl md:text-8xl lg:text-9xl mb-4 sm:mb-6 group-hover:scale-110 transition-transform duration-300">
                            💕
                        </div>
                        <h2 class="card-title text-3xl sm:text-4xl md:text-5xl mb-3 sm:mb-4 md:mb-6 flex-wrap justify-center">
                            Ma Girlfriend
                        </h2>
                        <p class="text-sm sm:text-base md:text-lg lg:text-xl opacity-90 mb-4 sm:mb-6 md:mb-8 max-w-lg">
                            Toutes les informations importantes : anniversaire, allergies, plat préféré, et bien plus encore
                        </p>
                        <div class="badge badge-lg bg-white text-pink-600 border-0 gap-2 text-sm sm:text-base md:text-lg px-4 sm:px-6 py-3 sm:py-4 group-hover:gap-4 transition-all">
                            <span>Accéder à la fiche</span>
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </div>
                    </div>
                </Link>
            </div>

            <!-- Stats avec icônes animées -->
            <div class="card bg-white/80 backdrop-blur-sm shadow-xl border border-pink-100 mx-4">
                <div class="card-body p-4 sm:p-6">
                    <h3 class="text-lg sm:text-xl font-semibold mb-4 sm:mb-6 text-center flex items-center justify-center gap-2">
                        <span class="text-2xl">🎯</span>
                        <span>Fonctionnalités</span>
                    </h3>
                    <div class="grid gap-3 sm:gap-4 grid-cols-1 sm:grid-cols-3">
                        <div class="text-center p-4 sm:p-6 rounded-2xl bg-gradient-to-br from-pink-50 to-rose-50 hover:from-pink-100 hover:to-rose-100 transition-all hover:scale-105 group">
                            <div class="text-3xl sm:text-4xl mb-2 sm:mb-3 group-hover:scale-110 transition-transform">📝</div>
                            <p class="font-semibold text-base sm:text-lg text-primary">Créer</p>
                            <p class="text-xs sm:text-sm text-base-content/60 mt-1">Ajoutez des informations</p>
                        </div>
                        <div class="text-center p-4 sm:p-6 rounded-2xl bg-gradient-to-br from-rose-50 to-pink-50 hover:from-rose-100 hover:to-pink-100 transition-all hover:scale-105 group">
                            <div class="text-3xl sm:text-4xl mb-2 sm:mb-3 group-hover:scale-110 transition-transform">✏️</div>
                            <p class="font-semibold text-base sm:text-lg text-primary">Modifier</p>
                            <p class="text-xs sm:text-sm text-base-content/60 mt-1">Mettez à jour</p>
                        </div>
                        <div class="text-center p-4 sm:p-6 rounded-2xl bg-gradient-to-br from-pink-50 to-rose-50 hover:from-pink-100 hover:to-rose-100 transition-all hover:scale-105 group sm:col-span-1 col-span-1">
                            <div class="text-3xl sm:text-4xl mb-2 sm:mb-3 group-hover:scale-110 transition-transform">👁️</div>
                            <p class="font-semibold text-base sm:text-lg text-primary">Consulter</p>
                            <p class="text-xs sm:text-sm text-base-content/60 mt-1">Retrouvez rapidement</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer discret -->
            <div class="text-center mt-8 text-xs sm:text-sm text-base-content/50">
                <p>Fait avec 💝 pour garder vos souvenirs précieux</p>
            </div>
        </div>
    </main>
</template>

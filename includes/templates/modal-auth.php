<div id="wpma-modal" class="fixed inset-0 bg-slate-950/70 hidden items-center justify-center z-50 px-4"
    style="z-index:100001;">
    <div
        class="w-full max-w-md rounded-2xl border border-slate-700/70 bg-slate-900 text-slate-50 shadow-2xl p-6 sm:p-8 relative">
        <button type="button" id="wpma-close"
            class="absolute right-4 top-4 inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-800/70 text-slate-300 hover:bg-slate-700 hover:text-white transition-colors duration-150">
            <span class="sr-only">Fermer la fenêtre</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <h2 id="wpma-title" class="text-xl sm:text-2xl font-semibold text-center mb-4">Connexion</h2>

        <div id="wpma-message"
            class="hidden mb-5 rounded-xl border border-red-500/40 bg-red-500/10 text-red-200 text-sm px-4 py-3 flex items-start justify-between gap-3">
            <div class="flex-1 leading-snug">
                <span id="wpma-message-text"></span>
            </div>
            <button type="button" id="wpma-message-close"
                class="ml-2 inline-flex h-6 w-6 items-center justify-center rounded-full text-red-300 hover:text-red-100 hover:bg-red-500/20 transition-colors duration-150">
                <span class="sr-only">Fermer le message</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3 w-3">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <div id="wpma-login-form" class="space-y-4">
            <input id="wpma-login" type="text"
                class="w-full bg-slate-950/60 border border-slate-700/70 rounded-xl px-4 py-3 text-sm text-slate-50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Email ou identifiant">
            <input id="wpma-password" type="password"
                class="w-full bg-slate-950/60 border border-slate-700/70 rounded-xl px-4 py-3 text-sm text-slate-50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Mot de passe">
            <label class="flex items-center gap-2 text-xs text-slate-300">
                <input id="wpma-remember" type="checkbox"
                    class="h-4 w-4 rounded border-slate-600 bg-slate-950 focus:ring-indigo-500" checked>
                <span>Mémoriser la connexion</span>
            </label>
            <button id="wpma-submit"
                class="relative w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-sm font-semibold text-white py-3 rounded-full shadow-sm hover:shadow transition-colors duration-150">
                <div
                    class="absolute left-[20px] inline-flex h-fit w-fit items-center justify-center rounded-full bg-indigo-500/80 text-[11px];">
                    <img src="<?php echo esc_url(WPMA_URL . 'assets/images/wpma-login-gris.svg'); ?>" alt="Connexion"
                        loading="lazy" class="w-[50px] h-[50px]" />
                </div>
                <span>Se connecter</span>
            </button>
            <p class=" wpma-link mt-2 pointer" data-action="forgot">Mot de passe oublié ?</p>
        </div>

        <div id="wpma-forgot-form" class="hidden space-y-4">
            <input id="wpma-email" type="email"
                class="w-full bg-slate-950/60 border border-slate-700/70 rounded-xl px-4 py-3 text-sm text-slate-50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Email">
            <button id="wpma-forgot-submit"
                class="relative w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-sm font-semibold text-white py-3 rounded-full shadow-sm hover:shadow transition-colors duration-150">
                <div
                    class="absolute left-[20px] inline-flex h-fit w-fit items-center justify-center rounded-full bg-indigo-500/80 text-[11px];">
                    <img src="<?php echo esc_url(WPMA_URL . 'assets/images/email.svg'); ?>" alt="Connexion"
                        loading="lazy" class="w-[50px] h-[50px]" />
                </div>
                <span>Envoyer</span>
            </button>
            <p id="wpma-status" class="mt-1 text-xs text-center text-slate-300 hidden"></p>
            <p class="wpma-link mt-2 pointer" data-action="login">Retour</p>
        </div>
    </div>
</div>
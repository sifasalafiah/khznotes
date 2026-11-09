<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>A Note For You</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Cormorant Garamond & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,400;1,500&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        serif: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                    }
                }
            }
        }
    </script>

    <!-- Canvas Confetti CDN -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.4/dist/confetti.browser.min.js"></script>

    <style>
        /* Lock scrolling completely on mobile */
        html, body {
            height: 100%;
            height: 100dvh;
            overflow: hidden !important;
            overscroll-behavior: none !important;
            touch-action: pan-x pinch-zoom;
            background-color: #f6f4ef;
            color: #2b2623;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        /* Allow natural document scrolling on desktop & tablet */
        @media (min-width: 640px) {
            html, body {
                height: auto;
                min-height: 100vh;
                overflow: auto !important;
                touch-action: auto;
            }

            .stationery-card {
                background: #ffffff;
                border: 1px solid #ebdcd0;
                box-shadow: 
                    0 2px 4px rgba(0, 0, 0, 0.02),
                    0 20px 40px -15px rgba(80, 60, 50, 0.07);
                border-radius: 1.5rem;
            }
        }

        /* Subtle separator line */
        .stationery-line {
            border-bottom: 1px dashed #e8ded4;
        }

        /* Understated Action Buttons */
        .btn-choice {
            background: #ffffff;
            border: 1px solid #e2d6ca;
            color: #3b322e;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
        }
        .btn-choice:hover {
            background: #faf7f2;
            border-color: #cfbeae;
            color: #1a1614;
        }

        .btn-dark {
            background: #231f1c;
            color: #ffffff;
            transition: all 0.2s ease;
        }
        .btn-dark:hover {
            background: #38322e;
        }

        /* Smooth step transition */
        .step-fade-enter-active, .step-fade-leave-active {
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .step-fade-enter-from {
            opacity: 0;
            transform: translateY(4px);
        }
        .step-fade-leave-to {
            opacity: 0;
            transform: translateY(-4px);
        }

        /* Shimmer Loading Animation */
        @keyframes shimmerPulse {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .shimmer-pulse {
            background: linear-gradient(90deg, #ede7de 25%, #f6f2eb 50%, #ede7de 75%);
            background-size: 200% 100%;
            animation: shimmerPulse 1.6s infinite ease-in-out;
        }

        /* Hide raw vue app before load */
        [v-cloak] {
            display: none !important;
        }

        /* Hide shimmer once Vue is loaded */
        .vue-loaded #shimmer-placeholder {
            display: none !important;
        }

        /* Subtle Fade In for Loaded Vue App */
        @keyframes fadeInContent {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        main#app:not([v-cloak]) {
            animation: fadeInContent 0.22s ease-out forwards;
        }
    </style>
</head>
<body class="h-full h-[100dvh] sm:h-auto sm:min-h-screen flex flex-col justify-between sm:justify-center sm:items-center p-0 sm:p-6 md:p-10 selection:bg-stone-200 selection:text-stone-900 overflow-hidden sm:overflow-auto">

    <!-- ============================================================== -->
    <!-- SHIMMER SKELETON (Instant shape matching the memo stationery)  -->
    <!-- ============================================================== -->
    <div id="shimmer-placeholder" class="w-full max-w-full sm:max-w-[430px] md:max-w-[460px] mx-auto h-full h-[100dvh] sm:h-auto sm:my-auto flex flex-col justify-between px-6 py-6 sm:p-0 overflow-hidden sm:overflow-visible">
        <article class="stationery-card sm:rounded-3xl p-0 sm:p-8 md:p-9 relative flex flex-col justify-between h-full sm:h-auto flex-1 sm:flex-initial overflow-hidden sm:overflow-visible">
            
            <!-- Top Header Shimmer -->
            <header class="flex items-center justify-between pb-3.5 mb-4 sm:mb-7 stationery-line shrink-0">
                <div class="h-3 w-24 rounded-md shimmer-pulse"></div>
                <div class="h-3 w-12 rounded-md shimmer-pulse"></div>
            </header>

            <!-- Body Text Shimmer -->
            <div class="flex-1 flex flex-col justify-center overflow-hidden">
                <div class="space-y-4 my-auto">
                    <div class="h-7 w-16 rounded-md shimmer-pulse mb-3"></div>
                    <div class="space-y-2.5">
                        <div class="h-3.5 w-full rounded-md shimmer-pulse"></div>
                        <div class="h-3.5 w-[92%] rounded-md shimmer-pulse"></div>
                        <div class="h-3.5 w-[78%] rounded-md shimmer-pulse"></div>
                    </div>
                    <div class="space-y-2.5 pt-2">
                        <div class="h-3.5 w-[95%] rounded-md shimmer-pulse"></div>
                        <div class="h-3.5 w-[85%] rounded-md shimmer-pulse"></div>
                    </div>
                    <div class="pt-3">
                        <div class="h-11 w-full rounded-xl shimmer-pulse"></div>
                    </div>
                </div>
            </div>

        </article>
    </div>


    <!-- ============================================================== -->
    <!-- REAL VUE APPLICATION (Mounts seamlessly with exact initialStep) -->
    <!-- ============================================================== -->
    <main id="app" v-cloak class="w-full max-w-full sm:max-w-[430px] md:max-w-[460px] mx-auto h-full h-[100dvh] sm:h-auto sm:my-auto flex flex-col justify-between px-6 py-6 sm:p-0 transition-all duration-300 overflow-hidden sm:overflow-visible">

        <!-- ============================================== -->
        <!-- STATE: NOTE DESTROYED / CLOSED                 -->
        <!-- ============================================== -->
        <div v-if="isDestroyed" class="stationery-card sm:rounded-3xl p-6 sm:p-10 text-center my-auto flex-1 sm:flex-initial flex flex-col justify-center items-center">
            <div class="text-[11px] uppercase tracking-widest text-stone-600 font-medium mb-4">
                Private Note
            </div>
            <h1 class="font-serif text-2xl sm:text-3xl font-medium text-stone-800 mb-3 leading-snug">
                This note is now closed.
            </h1>
            <p class="text-xs sm:text-sm text-stone-500 font-light leading-relaxed max-w-[280px] mx-auto">
                Thank you for taking a moment to read it.
            </p>
        </div>


        <!-- ============================================== -->
        <!-- STATE: ACTIVE PERSONAL MEMO                    -->
        <!-- ============================================== -->
        <article v-else class="stationery-card sm:rounded-3xl p-0 sm:p-8 md:p-9 relative flex flex-col justify-between h-full sm:h-auto flex-1 sm:flex-initial overflow-hidden sm:overflow-visible">

            <!-- Top Memo Header -->
            <header class="flex items-center justify-between pb-3.5 mb-4 sm:mb-7 stationery-line text-[11px] text-stone-600 tracking-wider shrink-0">
                <span class="font-medium uppercase">Private Note</span>
                <span class="text-stone-600">@{{ currentDate }}</span>
            </header>

            <!-- Step Content Container with Smooth Animation -->
            <div class="flex-1 flex flex-col justify-between overflow-hidden">
                <transition name="step-fade" mode="out-in">

                    <!-- ------------------------------------------ -->
                    <!-- PAGE 1: THE INTRO NOTE                     -->
                    <!-- ------------------------------------------ -->
                    <div v-if="currentStep === 1" key="step1" class="flex flex-col justify-center h-full flex-1">
                        <!-- Body Text Centered in Viewport -->
                        <div class="space-y-5 text-[14px] sm:text-[14.5px] leading-relaxed text-stone-700 font-light my-auto">
                            <p class="font-serif text-2xl sm:text-3xl font-semibold text-stone-900 tracking-tight mb-2">
                                Hey,
                            </p>

                            <p>
                                I know this is a bit out of nowhere, but I've noticed you around the room and just wanted to introduce myself.
                            </p>

                            <p>
                                You always look focused, and I didn't want to awkwardly interrupt you while you were busy, so I thought leaving this was simpler.
                            </p>

                            <!-- Continue Button directly beneath the text -->
                            <div class="pt-2">
                                <button 
                                    @click="goToStep(2)"
                                    :disabled="isNavigating || isSubmitting"
                                    class="w-full btn-dark py-3.5 px-6 rounded-xl text-xs sm:text-sm font-medium flex items-center justify-center gap-2 active:scale-[0.98] shadow-xs disabled:opacity-60 transition-all">
                                    <svg v-if="isNavigating" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span>@{{ isNavigating ? 'Please wait...' : 'Continue' }}</span>
                                    <svg v-if="!isNavigating" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- ------------------------------------------ -->
                    <!-- PAGE 2: THE GENTLE ASK / VIBE CHECK        -->
                    <!-- ------------------------------------------ -->
                    <div v-else-if="currentStep === 2" key="step2" class="flex flex-col justify-center h-full flex-1">
                        <!-- Body Text & Action directly underneath -->
                        <div class="space-y-4 text-[14px] sm:text-[14.5px] leading-relaxed text-stone-700 font-light my-auto">
                            <p>
                                If you're open to getting coffee or just chatting sometime, I'd love to say hi properly.
                            </p>

                            <p class="text-xs text-stone-600 pt-1">
                                Before that though, just to make sure I'm not overstepping:
                            </p>

                            <!-- Question & Choices Flowing Seamlessly -->
                            <div class="space-y-3 pt-1">
                                <transition name="step-fade" mode="out-in">
                                    <!-- Initial Choices State -->
                                    <div v-if="!showTakenConfirm" key="vibe-question" class="space-y-3">
                                        <p class="font-serif text-xl sm:text-2xl font-semibold text-stone-900 text-center sm:text-left">
                                            Are you seeing anyone?
                                        </p>
                                        <div class="grid grid-cols-2 gap-3 pt-1">
                                            <button 
                                                @click="selectChoice('single')"
                                                :disabled="isSubmitting || isNavigating"
                                                class="btn-choice py-3.5 px-3 rounded-xl text-xs sm:text-sm font-medium active:scale-[0.98] flex items-center justify-center gap-2 disabled:opacity-60 transition-all">
                                                <svg v-if="isSubmitting && activeChoice === 'single'" class="w-3.5 h-3.5 animate-spin text-stone-700" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                                </svg>
                                                <span>@{{ isSubmitting && activeChoice === 'single' ? 'One moment...' : 'Single :)' }}</span>
                                            </button>
                                            <button 
                                                @click="promptTakenConfirm"
                                                :disabled="isSubmitting || isNavigating"
                                                class="btn-choice py-3.5 px-3 rounded-xl text-xs sm:text-sm font-medium active:scale-[0.98] flex items-center justify-center gap-2 disabled:opacity-60 transition-all">
                                                <span>Seeing someone</span>
                                            </button>
                                        </div>

                                        <p class="text-[11px] text-stone-600 italic text-center sm:text-left pt-1">
                                            (No worries either way, whichever is true for you!)
                                        </p>
                                    </div>

                                    <!-- Soft Confirmation State -->
                                    <div v-else key="taken-confirm" class="space-y-3">
                                        <p class="font-serif text-xl sm:text-2xl font-semibold text-stone-900 text-center sm:text-left">
                                            Just to be sure before this note closes:
                                        </p>
                                        <p class="text-xs text-stone-600 text-center sm:text-left leading-relaxed">
                                            I'll close the note quietly so I don't bother you.
                                        </p>
                                        <div class="grid grid-cols-2 gap-3 pt-1">
                                            <button 
                                                type="button"
                                                @click="cancelTakenConfirm"
                                                :disabled="isSubmitting || isNavigating"
                                                class="btn-choice py-3.5 px-3 rounded-xl text-xs sm:text-sm font-medium active:scale-[0.98] flex items-center justify-center gap-2 disabled:opacity-60 transition-all">
                                                <span>Wait, go back</span>
                                            </button>
                                            <button 
                                                type="button"
                                                @click="confirmTaken"
                                                :disabled="isSubmitting || isNavigating"
                                                class="btn-dark py-3.5 px-3 rounded-xl text-xs sm:text-sm font-medium active:scale-[0.98] flex items-center justify-center gap-2 disabled:opacity-60 transition-all">
                                                <svg v-if="isSubmitting && activeChoice === 'taken'" class="w-3.5 h-3.5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                                </svg>
                                                <span>@{{ isSubmitting && activeChoice === 'taken' ? 'Closing...' : "Yes, that's right" }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </transition>
                            </div>

                            <!-- Back Button directly beneath choices -->
                            <div class="pt-2 flex items-center justify-center sm:justify-start">
                                <button 
                                    @click="goToStep(1)"
                                    :disabled="isNavigating || isSubmitting"
                                    class="text-xs text-stone-600 hover:text-stone-700 py-1.5 flex items-center gap-1.5 transition-colors disabled:opacity-50">
                                    <svg v-if="!isNavigating" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    <svg v-else class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span>@{{ isNavigating ? 'Loading...' : 'Read previous' }}</span>
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- ------------------------------------------ -->
                    <!-- PAGE 2B: GRACEFUL EXIT (IF TAKEN)          -->
                    <!-- ------------------------------------------ -->
                    <div v-else-if="currentStep === 'exit_taken'" key="exit_taken" class="text-center py-6 sm:py-10 space-y-3 my-auto flex-1 flex flex-col justify-center items-center">
                        <p class="font-serif text-2xl sm:text-3xl font-medium text-stone-800 leading-snug">
                            Totally get it!
                        </p>
                        <p class="text-xs sm:text-sm text-stone-500 font-light leading-relaxed max-w-[280px] mx-auto">
                            No worries at all. Hope you have a great rest of your week!
                        </p>
                        <div class="pt-4">
                            <span class="inline-block px-3 py-1 rounded-full text-[11px] text-stone-600 bg-stone-100 font-mono">
                                Closing quietly...
                            </span>
                        </div>
                    </div>


                    <!-- ------------------------------------------ -->
                    <!-- PAGE 3: CONTACT FORM (IF SINGLE)           -->
                    <!-- ------------------------------------------ -->
                    <div v-else-if="currentStep === 3" key="step3" class="flex flex-col justify-between h-full flex-1">
                        <div class="space-y-4 my-auto">
                            <div class="flex items-center justify-between pb-1">
                                <div>
                                    <p class="font-serif text-xl sm:text-2xl font-medium text-stone-900">
                                        Nice to meet you.
                                    </p>
                                    <p class="text-xs text-stone-500 font-light mt-0.5">
                                        Where's best to say hi?
                                    </p>
                                </div>
                                <button 
                                    @click="goToStep(2)"
                                    class="text-xs text-stone-600 hover:text-stone-700 py-1 underline underline-offset-2 transition-colors">
                                    back
                                </button>
                            </div>

                            <!-- Channel selector -->
                            <div class="grid grid-cols-2 gap-1.5 p-1 bg-stone-200/60 rounded-xl text-xs font-medium">
                                <button 
                                    type="button" 
                                    @click="contactType = 'instagram'"
                                    :class="contactType === 'instagram' ? 'bg-white text-stone-900 shadow-xs font-semibold' : 'text-stone-500 hover:text-stone-800'"
                                    class="py-2.5 rounded-lg transition-all text-center">
                                    Instagram
                                </button>
                                <button 
                                    type="button" 
                                    @click="contactType = 'whatsapp'"
                                    :class="contactType === 'whatsapp' ? 'bg-white text-stone-900 shadow-xs font-semibold' : 'text-stone-500 hover:text-stone-800'"
                                    class="py-2.5 rounded-lg transition-all text-center">
                                    WhatsApp
                                </button>
                            </div>

                            <!-- Form Inputs -->
                            <form @submit.prevent="submitContact" class="space-y-3 pt-1">
                                <div>
                                    <label class="block text-[11px] font-medium text-stone-500 mb-1">
                                        @{{ contactType === 'instagram' ? 'Your Instagram Username' : 'Your WhatsApp Number' }}
                                    </label>
                                    <input 
                                        v-model="contactValue" 
                                        :type="contactType === 'whatsapp' ? 'tel' : 'text'"
                                        :inputmode="contactType === 'whatsapp' ? 'tel' : 'text'"
                                        :autocomplete="contactType === 'whatsapp' ? 'tel' : 'username'"
                                        autocapitalize="none"
                                        autocorrect="off"
                                        spellcheck="false"
                                        required
                                        :placeholder="contactType === 'instagram' ? '@your_username' : '0812xxxx...'" 
                                        class="w-full px-4 py-3 rounded-xl bg-white border border-stone-200 text-stone-800 text-xs sm:text-sm placeholder:text-stone-400 focus:border-stone-400 focus:ring-2 focus:ring-stone-200 focus:outline-none transition-all shadow-xs">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-medium text-stone-500 mb-1">
                                        Short note <span class="text-stone-600 font-normal">(optional)</span>
                                    </label>
                                    <input 
                                        v-model="recipientNote" 
                                        type="text" 
                                        placeholder="Anything you'd like to add" 
                                        class="w-full px-4 py-3 rounded-xl bg-white border border-stone-200 text-stone-800 text-xs sm:text-sm placeholder:text-stone-400 focus:border-stone-400 focus:ring-2 focus:ring-stone-200 focus:outline-none transition-all shadow-xs">
                                </div>

                                <div class="pt-2">
                                    <button 
                                        type="submit" 
                                        :disabled="isSubmitting || !contactValue.trim()"
                                        class="w-full py-3.5 px-4 rounded-xl btn-dark disabled:opacity-40 text-xs sm:text-sm font-medium active:scale-[0.99] transition-all flex items-center justify-center gap-2 shadow-xs">
                                        <span v-if="!isSubmitting">Send</span>
                                        <span v-else>Sending...</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- ------------------------------------------ -->
                    <!-- PAGE 4: SUBMITTED SUCCESS                  -->
                    <!-- ------------------------------------------ -->
                    <div v-else-if="currentStep === 'submitted'" key="submitted" class="text-center py-6 sm:py-10 space-y-3 my-auto flex-1 flex flex-col justify-center items-center">
                        <p class="font-serif text-2xl sm:text-3xl font-medium text-stone-800 leading-snug">
                            Got it.
                        </p>
                        <p class="text-xs sm:text-sm text-stone-500 font-light leading-relaxed max-w-[280px] mx-auto">
                            I'll say hi soon! Have a good day ahead.
                        </p>
                        <div class="pt-4">
                            <span class="inline-block px-3 py-1 rounded-full text-[11px] text-stone-600 bg-stone-100 font-mono">
                                Closing note...
                            </span>
                        </div>
                    </div>

                </transition>
            </div>

        </article>

    </main>

    <!-- Inject Note Data from Laravel Controller -->
    <script>
        window.__NOTE_DATA__ = @json($noteData);
    </script>

    <!-- Vue 3 ESM Logic -->
    <script type="module">
        import { createApp, ref, onMounted } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';

        createApp({
            setup() {
                const noteData = ref(window.__NOTE_DATA__ || {});
                const isDestroyed = ref(noteData.value.isDestroyed || false);

                // Compute exact step SYNCHRONOUSLY before initial render to prevent step-1 flashing
                let initialStep = noteData.value.currentStep || 1;
                try {
                    const localStep = localStorage.getItem('secret_step_' + noteData.value.token);
                    if (localStep && parseInt(localStep) > initialStep) {
                        initialStep = parseInt(localStep);
                    }
                } catch (e) {}

                const currentStep = ref(initialStep);
                const contactType = ref('instagram');
                const contactValue = ref('');
                const recipientNote = ref('');
                const isSubmitting = ref(false);
                const isNavigating = ref(false);
                const activeChoice = ref('');
                const showTakenConfirm = ref(false);

                const currentDate = new Date().toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric'
                });

                const getCsrfToken = () => {
                    const el = document.querySelector('meta[name="csrf-token"]');
                    return el ? el.getAttribute('content') : '';
                };

                const saveStep = async (step) => {
                    try {
                        localStorage.setItem('secret_step_' + noteData.value.token, step);
                    } catch(e) {}

                    try {
                        const controller = new AbortController();
                        const timeoutId = setTimeout(() => controller.abort(), 2500);

                        await fetch(`/api/notes/${noteData.value.token}/status`, {
                            method: 'POST',
                            signal: controller.signal,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ current_step: typeof step === 'number' ? step : 2 })
                        });
                        clearTimeout(timeoutId);
                    } catch (e) {
                        console.error('Step sync error:', e);
                    }
                };

                const goToStep = async (step) => {
                    if (isNavigating.value || isSubmitting.value) return;
                    isNavigating.value = true;
                    showTakenConfirm.value = false;

                    try {
                        await saveStep(step);
                        currentStep.value = step;
                    } catch (e) {
                        currentStep.value = step;
                    } finally {
                        isNavigating.value = false;
                    }
                };

                const promptTakenConfirm = () => {
                    if (isSubmitting.value || isNavigating.value) return;
                    showTakenConfirm.value = true;
                };

                const cancelTakenConfirm = () => {
                    if (isSubmitting.value || isNavigating.value) return;
                    showTakenConfirm.value = false;
                };

                const selectChoice = async (val) => {
                    if (isSubmitting.value || isNavigating.value) return;

                    if (val === 'taken') {
                        showTakenConfirm.value = true;
                        return;
                    }

                    isSubmitting.value = true;
                    activeChoice.value = 'single';

                    try {
                        const controller = new AbortController();
                        const timeoutId = setTimeout(() => controller.abort(), 3500);

                        await fetch(`/api/notes/${noteData.value.token}/status`, {
                            method: 'POST',
                            signal: controller.signal,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: 'single',
                                relationship_choice: 'single',
                                current_step: 3
                            })
                        });
                        clearTimeout(timeoutId);

                        currentStep.value = 3;
                        saveStep(3);
                    } catch (err) {
                        currentStep.value = 3;
                        saveStep(3);
                    } finally {
                        isSubmitting.value = false;
                        activeChoice.value = '';
                    }
                };

                const confirmTaken = async () => {
                    if (isSubmitting.value || isNavigating.value) return;
                    isSubmitting.value = true;
                    activeChoice.value = 'taken';

                    try {
                        const controller = new AbortController();
                        const timeoutId = setTimeout(() => controller.abort(), 3500);

                        await fetch(`/api/notes/${noteData.value.token}/status`, {
                            method: 'POST',
                            signal: controller.signal,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: 'taken',
                                relationship_choice: 'taken',
                                current_step: 2
                            })
                        });
                        clearTimeout(timeoutId);

                        currentStep.value = 'exit_taken';
                        setTimeout(() => {
                            triggerDestroyServer();
                        }, 4000);
                    } catch (err) {
                        currentStep.value = 'exit_taken';
                        setTimeout(() => {
                            triggerDestroyServer();
                        }, 4000);
                    } finally {
                        isSubmitting.value = false;
                        activeChoice.value = '';
                    }
                };

                const submitContact = async () => {
                    if (isSubmitting.value || isNavigating.value || !contactValue.value.trim()) return;
                    isSubmitting.value = true;

                    try {
                        const controller = new AbortController();
                        const timeoutId = setTimeout(() => controller.abort(), 5000);

                        await fetch(`/api/notes/${noteData.value.token}/status`, {
                            method: 'POST',
                            signal: controller.signal,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: 'submitted',
                                contact_type: contactType.value,
                                contact_value: contactValue.value.trim(),
                                note_from_recipient: recipientNote.value.trim(),
                                current_step: 3
                            })
                        });
                        clearTimeout(timeoutId);

                        if (window.confetti) {
                            window.confetti({
                                particleCount: 30,
                                spread: 45,
                                origin: { y: 0.65 },
                                colors: ['#c49a88', '#dec4b5', '#e8dad0']
                            });
                        }

                        currentStep.value = 'submitted';

                        setTimeout(() => {
                            triggerDestroyServer();
                        }, 4000);
                    } catch (err) {
                        currentStep.value = 'submitted';
                        setTimeout(() => {
                            triggerDestroyServer();
                        }, 4000);
                    } finally {
                        isSubmitting.value = false;
                    }
                };

                const triggerDestroyServer = async () => {
                    try {
                        await fetch(`/api/notes/${noteData.value.token}/destroy`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            }
                        });
                    } catch (e) {
                        console.error('Destroy request error:', e);
                    } finally {
                        isDestroyed.value = true;
                    }
                };

                onMounted(() => {
                    // Mark body as loaded and immediately remove the shimmer skeleton
                    document.body.classList.add('vue-loaded');
                    const placeholder = document.getElementById('shimmer-placeholder');
                    if (placeholder) placeholder.remove();
                });

                return {
                    noteData,
                    isDestroyed,
                    currentStep,
                    contactType,
                    contactValue,
                    recipientNote,
                    isSubmitting,
                    isNavigating,
                    activeChoice,
                    showTakenConfirm,
                    currentDate,
                    goToStep,
                    selectChoice,
                    promptTakenConfirm,
                    cancelTakenConfirm,
                    confirmTaken,
                    submitContact
                };
            }
        }).mount('#app');
    </script>
</body>
</html>

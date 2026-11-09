<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>A Note For You</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Cormorant Garamond & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Cormorant Garamond"', 'serif'],
                    },
                    colors: {
                        blush: {
                            50: '#fff8f7',
                            100: '#feedeb',
                            200: '#fddbd7',
                            300: '#f9bdb5',
                            400: '#f29588',
                            500: '#e56c5c',
                            600: '#ce4a39',
                        },
                        cream: {
                            50: '#fdfbf7',
                            100: '#f9f5ed',
                            200: '#f3ebe0',
                            300: '#e8dbcb',
                        }
                    },
                    animation: {
                        'float-soft': 'floatSoft 4s ease-in-out infinite',
                        'glow-pulse': 'glowPulse 3.5s ease-in-out infinite',
                    },
                    keyframes: {
                        floatSoft: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-6px)' },
                        },
                        glowPulse: {
                            '0%, 100%': { opacity: '0.45', transform: 'scale(1)' },
                            '50%': { opacity: '0.75', transform: 'scale(1.08)' },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Canvas Confetti CDN -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.4/dist/confetti.browser.min.js"></script>

    <style>
        [v-cloak] { display: none; }
        body {
            background-color: #faf7f4;
            color: #2e2825;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        
        .card-luxury {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(235, 222, 211, 0.8);
            box-shadow: 
                0 4px 6px -1px rgba(180, 140, 120, 0.05),
                0 25px 45px -12px rgba(195, 155, 135, 0.22);
        }

        .inner-paper {
            background: linear-gradient(180deg, #fffdfb 0%, #fbf8f4 100%);
            border: 1px solid #f1eae2;
        }

        .btn-soft-primary {
            background: linear-gradient(135deg, #e78879 0%, #d96f60 100%);
            box-shadow: 0 10px 25px -5px rgba(220, 115, 100, 0.35);
        }
        .btn-soft-primary:hover {
            background: linear-gradient(135deg, #ec9284 0%, #df796a 100%);
            box-shadow: 0 12px 28px -5px rgba(220, 115, 100, 0.45);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10 relative overflow-x-hidden selection:bg-rose-200 selection:text-rose-900">

    <!-- Ambient Warm Pastel Orbs -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-rose-200/50 rounded-full blur-3xl animate-glow-pulse"></div>
        <div class="absolute -bottom-28 -right-20 w-[420px] h-[420px] bg-amber-100/70 rounded-full blur-3xl animate-glow-pulse" style="animation-delay: 1.5s"></div>
        <div class="absolute top-1/3 right-1/4 w-72 h-72 bg-peach-100/40 rounded-full blur-3xl"></div>
    </div>

    <!-- Main Container: Responsive across Mobile, Tablet, and Laptop -->
    <div id="app" v-cloak class="w-full max-w-[390px] sm:max-w-[420px] md:max-w-[450px] mx-auto relative z-10 transition-all duration-300">

        <!-- ============================================== -->
        <!-- SCREEN: NOTE CLOSED / EXPIRED                  -->
        <!-- ============================================== -->
        <div v-if="isDestroyed" class="card-luxury rounded-[32px] p-8 sm:p-9 text-center transition-all duration-500">
            <div class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-stone-100/80 border border-stone-200/60 flex items-center justify-center text-stone-400">
                <svg class="w-8 h-8 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>

            <div class="inline-block px-3.5 py-1 rounded-full text-[11px] font-medium tracking-widest uppercase bg-stone-100 text-stone-500 mb-3 border border-stone-200/60">
                Note Closed
            </div>

            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-stone-800 mb-2.5">
                This note is no longer available.
            </h1>

            <p class="text-stone-500 text-xs sm:text-sm leading-relaxed font-light">
                This was a private one-time note meant just for you, and it has now closed for good.
            </p>
        </div>


        <!-- ============================================== -->
        <!-- SCREEN 0: THE SEALED NOTE                      -->
        <!-- ============================================== -->
        <div v-else-if="currentStep === 0" class="card-luxury rounded-[32px] p-7 sm:p-9 text-center transition-all duration-500">
            <!-- Top delicate pill -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-[11px] font-medium tracking-wider uppercase bg-rose-50 text-rose-700/90 border border-rose-200/70 mb-7">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-ping"></span>
                Dedicated For You
            </div>

            <!-- Envelope Icon (Clean Minimal Stroke) -->
            <div class="mb-6 relative">
                <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-[28px] bg-gradient-to-tr from-rose-100 via-cream-100 to-amber-100 border border-rose-200/60 flex items-center justify-center animate-float-soft shadow-inner">
                    <svg class="w-10 h-10 sm:w-11 sm:h-11 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <!-- Title & Subtitle -->
            <h1 class="text-3xl sm:text-4xl font-serif font-semibold text-stone-800 tracking-tight mb-3">
                A quiet hello.
            </h1>

            <p class="text-stone-600 text-xs sm:text-sm leading-relaxed mb-7 font-light px-2">
                I wrote this little note specifically for you. It can only be opened once, so please take your time reading it.
            </p>

            <!-- Unlock Button -->
            <button 
                @click="openNote" 
                class="w-full py-4 px-6 rounded-2xl btn-soft-primary text-white font-medium text-xs sm:text-sm tracking-wide active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <span>Open Note</span>
                <svg class="w-4 h-4 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>


        <!-- ============================================== -->
        <!-- SCREEN 1: THE OBSERVATION                      -->
        <!-- ============================================== -->
        <div v-else-if="currentStep === 1" class="card-luxury rounded-[32px] p-7 sm:p-9 transition-all duration-500">
            <!-- Soft progress dots -->
            <div class="flex items-center justify-between mb-6 text-xs text-stone-400 font-medium">
                <span class="tracking-wider uppercase text-[10px] text-rose-600/80 font-semibold">Step 01 / 03</span>
                <div class="flex gap-1.5">
                    <span class="w-6 h-1.5 rounded-full bg-rose-400"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-stone-200"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-stone-200"></span>
                </div>
            </div>

            <h1 class="text-2xl sm:text-3xl font-serif font-semibold text-stone-800 tracking-tight mb-4">
                I couldn't help but notice you.
            </h1>

            <!-- Paper letter inset -->
            <div class="inner-paper space-y-3.5 text-stone-600 text-xs sm:text-sm leading-relaxed font-light mb-7 p-5 rounded-2xl">
                <p>
                    We’ve been in the same room, and honestly, you caught my attention from the moment I saw you.
                </p>
                <p>
                    I wanted to come over and say hi in person, but you looked busy and I didn't want to interrupt you in front of everyone.
                </p>
                <p class="text-rose-700 font-normal italic">
                    So I decided to leave you this little note instead.
                </p>
            </div>

            <button 
                @click="nextStep(2)" 
                class="w-full py-3.5 px-6 rounded-2xl bg-stone-900 hover:bg-stone-800 text-white font-medium text-xs sm:text-sm tracking-wide active:scale-[0.98] transition-all flex items-center justify-center gap-2 shadow-sm">
                <span>Next</span>
                <svg class="w-4 h-4 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>


        <!-- ============================================== -->
        <!-- SCREEN 2: GENTLE VIBE CHECK                    -->
        <!-- ============================================== -->
        <div v-else-if="currentStep === 2" class="card-luxury rounded-[32px] p-7 sm:p-9 transition-all duration-500">
            <!-- Soft progress dots -->
            <div class="flex items-center justify-between mb-6 text-xs text-stone-400 font-medium">
                <span class="tracking-wider uppercase text-[10px] text-rose-600/80 font-semibold">Step 02 / 03</span>
                <div class="flex gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-stone-200"></span>
                    <span class="w-6 h-1.5 rounded-full bg-rose-400"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-stone-200"></span>
                </div>
            </div>

            <h1 class="text-2xl sm:text-3xl font-serif font-semibold text-stone-800 tracking-tight mb-1.5">
                Just one quick question...
            </h1>
            <p class="text-stone-500 text-xs sm:text-sm mb-6 font-light">
                Before anything else, I just wanted to ask:
            </p>

            <!-- Clean Choices with Minimal SVG Icons -->
            <div class="space-y-3 mb-6">
                <!-- Option Single -->
                <button 
                    @click="chooseStatus('single')"
                    :disabled="isSubmitting"
                    class="w-full p-4 sm:p-4.5 rounded-2xl bg-white hover:bg-rose-50/50 border border-stone-200/80 hover:border-rose-300 text-left transition-all group flex items-start gap-3.5 shadow-sm active:scale-[0.99]">
                    <div class="w-8 h-8 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-rose-100 transition-colors">
                        <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-xs sm:text-sm text-stone-800 group-hover:text-rose-700 transition-colors">
                            I'm single and happy to chat
                        </div>
                        <div class="text-[11px] sm:text-xs text-stone-400 font-light mt-0.5">
                            Nice to meet you!
                        </div>
                    </div>
                </button>

                <!-- Option Taken -->
                <button 
                    @click="chooseStatus('taken')"
                    :disabled="isSubmitting"
                    class="w-full p-4 sm:p-4.5 rounded-2xl bg-stone-50/60 hover:bg-stone-100/70 border border-stone-200/60 text-left transition-all group flex items-start gap-3.5 active:scale-[0.99]">
                    <div class="w-8 h-8 rounded-xl bg-stone-100 border border-stone-200 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-xs sm:text-sm text-stone-600">
                            I'm already seeing someone
                        </div>
                        <div class="text-[11px] sm:text-xs text-stone-400 font-light mt-0.5">
                            Appreciate the note though!
                        </div>
                    </div>
                </button>
            </div>

            <p class="text-[11px] text-stone-400 text-center font-light">
                Zero pressure—whichever is true for you!
            </p>
        </div>


        <!-- ============================================== -->
        <!-- SCREEN 2B: GRACEFUL EXIT (IF TAKEN)            -->
        <!-- ============================================== -->
        <div v-else-if="currentStep === 'exit_taken'" class="card-luxury rounded-[32px] p-7 sm:p-9 text-center transition-all duration-500">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-rose-50 border border-rose-200/60 flex items-center justify-center text-rose-400">
                <svg class="w-8 h-8 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>

            <h1 class="text-2xl sm:text-3xl font-serif font-semibold text-stone-800 mb-2.5">
                Totally understood!
            </h1>

            <p class="text-stone-600 text-xs sm:text-sm leading-relaxed mb-6 font-light px-2">
                Whoever has your heart is really lucky! No worries at all—I just wanted to share a genuine compliment. Hope you have a wonderful day ahead!
            </p>

            <div class="inline-block px-3.5 py-1 rounded-full text-xs text-stone-400 bg-stone-100 border border-stone-200/60 font-mono">
                Closing in @{{ countdown }}s...
            </div>
        </div>


        <!-- ============================================== -->
        <!-- SCREEN 3: THE CONTACT FORM (IF SINGLE)         -->
        <!-- ============================================== -->
        <div v-else-if="currentStep === 3" class="card-luxury rounded-[32px] p-7 sm:p-9 transition-all duration-500">
            <!-- Soft progress dots -->
            <div class="flex items-center justify-between mb-6 text-xs text-stone-400 font-medium">
                <span class="tracking-wider uppercase text-[10px] text-rose-600/80 font-semibold">Step 03 / 03</span>
                <div class="flex gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-stone-200"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-stone-200"></span>
                    <span class="w-6 h-1.5 rounded-full bg-rose-400"></span>
                </div>
            </div>

            <h1 class="text-2xl sm:text-3xl font-serif font-semibold text-stone-800 tracking-tight mb-1.5">
                I’d love to get to know you.
            </h1>
            <p class="text-stone-500 text-xs sm:text-sm mb-6 font-light leading-relaxed">
                Maybe we could grab a coffee or chat sometime. If you’re open to it, where can I say hi?
            </p>

            <form @submit.prevent="submitContact" class="space-y-4">
                <!-- Channel selector toggle with clean SVGs -->
                <div class="grid grid-cols-2 gap-2 p-1 bg-stone-100/90 rounded-2xl border border-stone-200/60 text-xs font-medium">
                    <button 
                        type="button" 
                        @click="contactType = 'instagram'"
                        :class="contactType === 'instagram' ? 'bg-white text-stone-800 shadow-sm font-semibold' : 'text-stone-500 hover:text-stone-700'"
                        class="py-2.5 rounded-xl transition-all flex items-center justify-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="1.8"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" stroke-width="1.8"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-width="2.2" stroke-linecap="round"></line>
                        </svg>
                        <span>Instagram</span>
                    </button>
                    <button 
                        type="button" 
                        @click="contactType = 'whatsapp'"
                        :class="contactType === 'whatsapp' ? 'bg-white text-stone-800 shadow-sm font-semibold' : 'text-stone-500 hover:text-stone-700'"
                        class="py-2.5 rounded-xl transition-all flex items-center justify-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span>WhatsApp</span>
                    </button>
                </div>

                <!-- Contact Input Field -->
                <div>
                    <label class="block text-[11px] sm:text-xs font-medium text-stone-600 mb-1.5">
                        @{{ contactType === 'instagram' ? 'Your Instagram Username' : 'Your WhatsApp Number' }}
                    </label>
                    <input 
                        v-model="contactValue" 
                        type="text" 
                        required
                        :placeholder="contactType === 'instagram' ? '@your_username' : '0812xxxx...'" 
                        class="w-full px-4 py-3 rounded-2xl bg-white border border-stone-200 focus:border-rose-400 focus:ring-4 focus:ring-rose-100 focus:outline-none text-stone-800 text-xs sm:text-sm placeholder:text-stone-400 transition-all shadow-sm">
                </div>

                <!-- Note (Optional) -->
                <div>
                    <label class="block text-[11px] sm:text-xs font-medium text-stone-600 mb-1.5">
                        Leave a message <span class="text-stone-400 font-normal">(optional)</span>
                    </label>
                    <input 
                        v-model="recipientNote" 
                        type="text" 
                        placeholder="e.g. Try guessing who I am" 
                        class="w-full px-4 py-3 rounded-2xl bg-white border border-stone-200 focus:border-rose-400 focus:ring-4 focus:ring-rose-100 focus:outline-none text-stone-800 text-xs sm:text-sm placeholder:text-stone-400 transition-all shadow-sm">
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    :disabled="isSubmitting || !contactValue.trim()"
                    class="w-full py-4 px-6 rounded-2xl btn-soft-primary disabled:opacity-50 text-white font-semibold text-xs sm:text-sm tracking-wide active:scale-[0.98] transition-all flex items-center justify-center gap-2 mt-2">
                    <span v-if="!isSubmitting">Send Message</span>
                    <span v-else>Sending...</span>
                    <svg v-if="!isSubmitting" class="w-4 h-4 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
        </div>


        <!-- ============================================== -->
        <!-- SCREEN 4: SUCCESS & CLOSING                    -->
        <!-- ============================================== -->
        <div v-else-if="currentStep === 'burning'" class="card-luxury rounded-[32px] p-8 sm:p-9 text-center transition-all duration-700">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-rose-50 border border-rose-200/80 flex items-center justify-center text-rose-500">
                <svg class="w-8 h-8 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-2xl sm:text-3xl font-serif font-semibold text-stone-800 mb-2">
                Thank you!
            </h1>

            <p class="text-stone-600 text-xs sm:text-sm leading-relaxed mb-6 font-light px-2">
                I received it. I’ll say hi soon! This note will close now so it stays just between us.
            </p>

            <div class="inline-block px-3.5 py-1 rounded-full text-xs text-rose-600 bg-rose-50/70 border border-rose-200/60 font-mono">
                Closing in @{{ countdown }}...
            </div>
        </div>

    </div>

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
                const currentStep = ref(noteData.value.currentStep || 0);
                const contactType = ref('instagram');
                const contactValue = ref('');
                const recipientNote = ref('');
                const isSubmitting = ref(false);
                const countdown = ref(5);

                const getCsrfToken = () => {
                    const el = document.querySelector('meta[name="csrf-token"]');
                    return el ? el.getAttribute('content') : '';
                };

                const saveStep = (step) => {
                    try {
                        localStorage.setItem('secret_step_' + noteData.value.token, step);
                    } catch(e) {}

                    fetch(`/api/secret/${noteData.value.token}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ current_step: step })
                    }).catch(e => console.error('Step sync error:', e));
                };

                const openNote = () => {
                    currentStep.value = 1;
                    saveStep(1);
                };

                const nextStep = (step) => {
                    currentStep.value = step;
                    saveStep(step);
                };

                const chooseStatus = async (status) => {
                    isSubmitting.value = true;
                    try {
                        await fetch(`/api/secret/${noteData.value.token}/status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: status,
                                relationship_choice: status,
                                current_step: status === 'taken' ? 2 : 3
                            })
                        });

                        if (status === 'taken') {
                            currentStep.value = 'exit_taken';
                            startAutoDestroyTimer(5);
                        } else {
                            currentStep.value = 3;
                            try { localStorage.setItem('secret_step_' + noteData.value.token, 3); } catch(e){}
                        }
                    } catch (err) {
                        if (status === 'taken') {
                            currentStep.value = 'exit_taken';
                            startAutoDestroyTimer(5);
                        } else {
                            currentStep.value = 3;
                        }
                    } finally {
                        isSubmitting.value = false;
                    }
                };

                const submitContact = async () => {
                    if (!contactValue.value.trim()) return;
                    isSubmitting.value = true;

                    try {
                        await fetch(`/api/secret/${noteData.value.token}/status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: 'submitted',
                                contact_type: contactType.value,
                                contact_value: contactValue.value.trim(),
                                note_from_recipient: recipientNote.value.trim()
                            })
                        });

                        if (window.confetti) {
                            window.confetti({
                                particleCount: 50,
                                spread: 55,
                                origin: { y: 0.6 },
                                colors: ['#f29588', '#fddbd7', '#f4a261', '#ffd166', '#ffffff']
                            });
                        }

                        currentStep.value = 'burning';
                        startAutoDestroyTimer(4);
                    } catch (err) {
                        currentStep.value = 'burning';
                        startAutoDestroyTimer(4);
                    } finally {
                        isSubmitting.value = false;
                    }
                };

                const startAutoDestroyTimer = (seconds) => {
                    countdown.value = seconds;
                    const timer = setInterval(() => {
                        countdown.value -= 1;
                        if (countdown.value <= 0) {
                            clearInterval(timer);
                            triggerDestroyServer();
                        }
                    }, 1000);
                };

                const triggerDestroyServer = async () => {
                    try {
                        await fetch(`/api/secret/${noteData.value.token}/destroy`, {
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
                    if (isDestroyed.value) {
                        currentStep.value = 'destroyed';
                        return;
                    }

                    let localStep = null;
                    try {
                        localStep = localStorage.getItem('secret_step_' + noteData.value.token);
                    } catch (e) {}

                    const stepToRestore = noteData.value.currentStep || (localStep ? parseInt(localStep) : 0);
                    if (stepToRestore && stepToRestore > 0) {
                        currentStep.value = stepToRestore;
                    }
                });

                return {
                    noteData,
                    isDestroyed,
                    currentStep,
                    contactType,
                    contactValue,
                    recipientNote,
                    isSubmitting,
                    countdown,
                    openNote,
                    nextStep,
                    chooseStatus,
                    submitContact
                };
            }
        }).mount('#app');
    </script>
</body>
</html>

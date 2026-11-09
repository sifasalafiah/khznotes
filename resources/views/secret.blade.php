<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>A Note For You</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    animation: {
                        'pulse-glow': 'pulseGlow 2.5s infinite ease-in-out',
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        pulseGlow: {
                            '0%, 100%': { opacity: '0.4', transform: 'scale(1)' },
                            '50%': { opacity: '0.8', transform: 'scale(1.05)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' },
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
            background-color: #0b0f19;
            color: #f1f5f9;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .glass-card {
            background: rgba(17, 24, 39, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden selection:bg-amber-500 selection:text-white">

    <!-- Ambient background glows -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-rose-600/15 rounded-full blur-3xl animate-pulse-glow"></div>
        <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-amber-500/15 rounded-full blur-3xl animate-pulse-glow" style="animation-delay: 1.2s"></div>
    </div>

    <!-- App Container -->
    <div id="app" v-cloak class="w-full max-w-sm mx-auto relative z-10">

        <!-- ============================================== -->
        <!-- SCREEN: ALREADY CLOSED / EXPIRED               -->
        <!-- ============================================== -->
        <div v-if="isDestroyed" class="glass-card rounded-3xl p-8 text-center transition-all duration-500">
            <div class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-zinc-900 border border-zinc-800 flex items-center justify-center text-2xl text-zinc-500">
                ✉️
            </div>

            <div class="inline-block px-3 py-1 rounded-full text-[11px] font-semibold uppercase tracking-widest bg-zinc-800 text-zinc-400 mb-3 border border-zinc-700/60">
                Note Closed
            </div>

            <h1 class="text-xl font-bold font-serif text-white mb-2">
                This note is no longer available.
            </h1>

            <p class="text-zinc-400 text-xs leading-relaxed">
                This was a private one-time note meant just for you, and it has now closed for good.
            </p>
        </div>


        <!-- ============================================== -->
        <!-- SCREEN 0: THE SEALED NOTE (FIRST GLANCE)       -->
        <!-- ============================================== -->
        <div v-else-if="currentStep === 0" class="glass-card rounded-3xl p-7 text-center transition-all duration-500">
            <!-- Top tag -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-medium tracking-wider uppercase bg-amber-500/10 text-amber-300 border border-amber-500/20 mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                Just For You
            </div>

            <!-- Envelope Icon -->
            <div class="mb-6 relative">
                <div class="w-20 h-20 mx-auto rounded-3xl bg-gradient-to-tr from-amber-500/20 to-rose-500/20 border border-amber-500/30 flex items-center justify-center animate-float shadow-lg shadow-amber-500/10">
                    <svg class="w-10 h-10 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-bold font-serif text-white tracking-tight mb-2.5">
                A quiet hello.
            </h1>

            <p class="text-zinc-300 text-xs leading-relaxed mb-6 font-light">
                I wrote this little note specifically for you. It can only be opened once, so take your time reading it.
            </p>

            <!-- Unlock Button -->
            <button 
                @click="openNote" 
                class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-zinc-950 font-bold text-xs tracking-wide shadow-lg shadow-orange-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <span>Open Note ✉️</span>
            </button>
        </div>


        <!-- ============================================== -->
        <!-- SCREEN 1: THE OBSERVATION                      -->
        <!-- ============================================== -->
        <div v-else-if="currentStep === 1" class="glass-card rounded-3xl p-7 transition-all duration-500">
            <!-- Progress indicator -->
            <div class="flex items-center justify-between mb-5 text-[11px] text-zinc-500 font-medium">
                <span>01 of 03</span>
                <div class="flex gap-1.5">
                    <span class="w-5 h-1.5 rounded-full bg-amber-400"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-700"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-700"></span>
                </div>
            </div>

            <h1 class="text-2xl font-bold font-serif text-white tracking-tight mb-4">
                I couldn't help but notice you.
            </h1>

            <div class="space-y-3 text-zinc-300 text-xs leading-relaxed font-light mb-7 bg-zinc-900/50 p-4 rounded-2xl border border-zinc-800">
                <p>
                    We’ve been in the same room, and honestly, you caught my attention from the moment I saw you.
                </p>
                <p>
                    I wanted to come over and say hi in person, but you looked busy and I didn't want to interrupt you in front of everyone.
                </p>
                <p class="text-amber-200/90 font-normal">
                    So I decided to leave you this little note instead.
                </p>
            </div>

            <button 
                @click="nextStep(2)" 
                class="w-full py-3.5 px-6 rounded-2xl bg-zinc-100 hover:bg-white text-zinc-950 font-bold text-xs tracking-wide active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <span>Next →</span>
            </button>
        </div>


        <!-- ============================================== -->
        <!-- SCREEN 2: GENTLE VIBE CHECK                    -->
        <!-- ============================================== -->
        <div v-else-if="currentStep === 2" class="glass-card rounded-3xl p-7 transition-all duration-500">
            <!-- Progress indicator -->
            <div class="flex items-center justify-between mb-5 text-[11px] text-zinc-500 font-medium">
                <span>02 of 03</span>
                <div class="flex gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-700"></span>
                    <span class="w-5 h-1.5 rounded-full bg-amber-400"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-700"></span>
                </div>
            </div>

            <h1 class="text-2xl font-bold font-serif text-white tracking-tight mb-2">
                Just one quick question...
            </h1>
            <p class="text-zinc-400 text-xs mb-5 font-light">
                Before anything else, I just wanted to ask:
            </p>

            <!-- Choices -->
            <div class="space-y-3 mb-6">
                <!-- Option Single -->
                <button 
                    @click="chooseStatus('single')"
                    :disabled="isSubmitting"
                    class="w-full p-4 rounded-2xl bg-zinc-900/80 hover:bg-zinc-800 border border-zinc-800 hover:border-amber-400/50 text-left transition-all group flex items-start gap-3.5">
                    <span class="text-xl mt-0.5 group-hover:scale-110 transition-transform">✨</span>
                    <div>
                        <div class="font-bold text-xs text-white group-hover:text-amber-300 transition-colors">
                            I'm single and happy to chat
                        </div>
                        <div class="text-[11px] text-zinc-400 font-light mt-0.5">
                            Nice to meet you!
                        </div>
                    </div>
                </button>

                <!-- Option Taken -->
                <button 
                    @click="chooseStatus('taken')"
                    :disabled="isSubmitting"
                    class="w-full p-4 rounded-2xl bg-zinc-900/40 hover:bg-zinc-850 border border-zinc-800/80 text-left transition-all group flex items-start gap-3.5">
                    <span class="text-xl mt-0.5 group-hover:scale-110 transition-transform">🌿</span>
                    <div>
                        <div class="font-bold text-xs text-zinc-300">
                            I'm already seeing someone
                        </div>
                        <div class="text-[11px] text-zinc-500 font-light mt-0.5">
                            Appreciate the note though!
                        </div>
                    </div>
                </button>
            </div>

            <p class="text-[11px] text-zinc-500 text-center font-light">
                Zero pressure—whichever is true for you!
            </p>
        </div>


        <!-- ============================================== -->
        <!-- SCREEN 2B: GRACEFUL EXIT (IF TAKEN)            -->
        <!-- ============================================== -->
        <div v-else-if="currentStep === 'exit_taken'" class="glass-card rounded-3xl p-7 text-center transition-all duration-500">
            <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-zinc-900 border border-zinc-800 flex items-center justify-center text-2xl">
                🌸
            </div>

            <h1 class="text-2xl font-bold font-serif text-white mb-2">
                Totally understood!
            </h1>

            <p class="text-zinc-300 text-xs leading-relaxed mb-6 font-light">
                Whoever has your heart is really lucky! No worries at all—I just wanted to share a genuine compliment. Hope you have a wonderful day ahead!
            </p>

            <div class="text-xs text-amber-400/90 font-mono">
                Closing note in @{{ countdown }}s...
            </div>
        </div>


        <!-- ============================================== -->
        <!-- SCREEN 3: THE CONTACT (IF SINGLE)              -->
        <!-- ============================================== -->
        <div v-else-if="currentStep === 3" class="glass-card rounded-3xl p-7 transition-all duration-500">
            <!-- Progress indicator -->
            <div class="flex items-center justify-between mb-5 text-[11px] text-zinc-500 font-medium">
                <span>03 of 03</span>
                <div class="flex gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-700"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-700"></span>
                    <span class="w-5 h-1.5 rounded-full bg-amber-400"></span>
                </div>
            </div>

            <h1 class="text-2xl font-bold font-serif text-white tracking-tight mb-1.5">
                I’d love to get to know you.
            </h1>
            <p class="text-zinc-300 text-xs mb-5 font-light leading-relaxed">
                Maybe we could grab a coffee or chat sometime. If you’re open to it, where can I say hi?
            </p>

            <form @submit.prevent="submitContact" class="space-y-4">
                <!-- Channel selector -->
                <div class="grid grid-cols-2 gap-2 p-1 bg-zinc-900 rounded-xl border border-zinc-800 text-xs font-semibold">
                    <button 
                        type="button" 
                        @click="contactType = 'instagram'"
                        :class="contactType === 'instagram' ? 'bg-zinc-800 text-white shadow' : 'text-zinc-400'"
                        class="py-2 rounded-lg transition-all flex items-center justify-center gap-1.5">
                        <span>📸 Instagram</span>
                    </button>
                    <button 
                        type="button" 
                        @click="contactType = 'whatsapp'"
                        :class="contactType === 'whatsapp' ? 'bg-zinc-800 text-white shadow' : 'text-zinc-400'"
                        class="py-2 rounded-lg transition-all flex items-center justify-center gap-1.5">
                        <span>💬 WhatsApp</span>
                    </button>
                </div>

                <!-- Input -->
                <div>
                    <label class="block text-[11px] font-medium text-zinc-400 mb-1">
                        @{{ contactType === 'instagram' ? 'Your Instagram Username' : 'Your WhatsApp Number' }}
                    </label>
                    <input 
                        v-model="contactValue" 
                        type="text" 
                        required
                        :placeholder="contactType === 'instagram' ? '@your_username' : '0812xxxx...'" 
                        class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-zinc-800 focus:border-amber-400 focus:outline-none text-white text-xs placeholder:text-zinc-600 transition-all">
                </div>

                <!-- Note (Optional) -->
                <div>
                    <label class="block text-[11px] font-medium text-zinc-400 mb-1">
                        Leave a message <span class="text-zinc-600">(optional)</span>
                    </label>
                    <input 
                        v-model="recipientNote" 
                        type="text" 
                        placeholder="e.g. Try guessing who I am 😉" 
                        class="w-full px-4 py-2.5 rounded-xl bg-zinc-950 border border-zinc-800 focus:border-amber-400 focus:outline-none text-white text-xs placeholder:text-zinc-600 transition-all">
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    :disabled="isSubmitting || !contactValue.trim()"
                    class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 disabled:opacity-50 text-zinc-950 font-bold text-xs tracking-wide shadow-lg shadow-orange-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <span v-if="!isSubmitting">Send Message ✨</span>
                    <span v-else>Sending...</span>
                </button>
            </form>
        </div>


        <!-- ============================================== -->
        <!-- SCREEN 4: SUCCESS & CLOSING                    -->
        <!-- ============================================== -->
        <div v-else-if="currentStep === 'burning'" class="glass-card rounded-3xl p-8 text-center transition-all duration-700">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-3xl animate-pulse">
                ✨
            </div>

            <h1 class="text-2xl font-bold font-serif text-white mb-2">
                Thank you!
            </h1>

            <p class="text-zinc-300 text-xs leading-relaxed mb-6 font-light">
                I received it. I’ll say hi soon! This note will close now so it stays just between us.
            </p>

            <div class="font-mono text-xs text-amber-400">
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
                                particleCount: 70,
                                spread: 60,
                                origin: { y: 0.6 }
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

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
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,400;1,500&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

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
        [v-cloak] { display: none; }
        body {
            background-color: #f7f5f0;
            color: #2b2623;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        /* Clean Stationery Paper Feel */
        .stationery-card {
            background: #ffffff;
            border: 1px solid #ebdcd0;
            box-shadow: 
                0 1px 3px rgba(0, 0, 0, 0.02),
                0 16px 36px -12px rgba(90, 70, 60, 0.08);
        }

        /* Subtle textured line */
        .memo-divider {
            border-bottom: 1px dashed #e8ded4;
        }

        /* Clean, Understated Action Buttons */
        .btn-choice {
            background: #faf8f5;
            border: 1px solid #e4d8cd;
            color: #423934;
            transition: all 0.2s ease;
        }
        .btn-choice:hover {
            background: #f2ece4;
            border-color: #d8c8b9;
            color: #201a17;
        }

        .btn-dark {
            background: #231f1c;
            color: #ffffff;
            transition: all 0.2s ease;
        }
        .btn-dark:hover {
            background: #38322e;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-8 selection:bg-stone-200 selection:text-stone-900">

    <!-- Main Container -->
    <main id="app" v-cloak class="w-full max-w-[380px] sm:max-w-[420px] mx-auto transition-all duration-300">

        <!-- ============================================== -->
        <!-- STATE 1: NOTE DESTROYED / CLOSED               -->
        <!-- ============================================== -->
        <div v-if="isDestroyed" class="stationery-card rounded-2xl p-7 sm:p-8 text-center">
            <div class="text-[11px] uppercase tracking-widest text-stone-600 font-medium mb-3">
                Private Note
            </div>
            <h1 class="font-serif text-2xl sm:text-3xl font-medium text-stone-800 mb-2">
                This note is now closed.
            </h1>
            <p class="text-xs sm:text-sm text-stone-500 font-light leading-relaxed">
                Thank you for taking a moment to read it.
            </p>
        </div>


        <!-- ============================================== -->
        <!-- STATE 2: THE PERSONAL MEMO                     -->
        <!-- ============================================== -->
        <article v-else class="stationery-card rounded-2xl p-6 sm:p-8 relative">

            <!-- Top Memo Header -->
            <header class="flex items-center justify-between pb-4 mb-5 memo-divider text-[11px] text-stone-600 tracking-wider">
                <span class="font-medium uppercase">Personal Note</span>
                <span>@{{ currentDate }}</span>
            </header>

            <!-- Letter Body -->
            <div class="space-y-3.5 text-[13px] sm:text-sm leading-relaxed text-stone-700 font-light">
                <p class="font-serif text-xl sm:text-2xl font-semibold text-stone-900 mb-1">
                    Hey,
                </p>

                <p>
                    I know this is a bit out of nowhere, but I've noticed you around the room and just wanted to introduce myself.
                </p>

                <p>
                    You always look focused, and I didn't want to awkwardly interrupt you while you were busy, so I thought leaving this was simpler.
                </p>

                <p>
                    If you're single and open to getting coffee or just chatting sometime, let me know below.
                </p>

                <p class="text-xs text-stone-600 italic pt-1">
                    (And if you're already seeing someone, no worries at all—just take it as a friendly compliment and we never have to speak of it haha)
                </p>
            </div>

            <!-- Interactivity Section -->
            <section class="mt-6 pt-5 memo-divider">

                <!-- ------------------------------------------ -->
                <!-- CHOICE BUTTONS (Initial State)             -->
                <!-- ------------------------------------------ -->
                <div v-if="choice === null && !isSubmitted">
                    <p class="text-xs text-stone-600 mb-3 text-center">
                        Are you seeing anyone?
                    </p>
                    <div class="grid grid-cols-2 gap-2.5">
                        <button 
                            @click="selectChoice('single')"
                            :disabled="isSubmitting"
                            class="btn-choice py-2.5 px-3 rounded-xl text-xs sm:text-sm font-medium active:scale-[0.98]">
                            Single :)
                        </button>
                        <button 
                            @click="selectChoice('taken')"
                            :disabled="isSubmitting"
                            class="btn-choice py-2.5 px-3 rounded-xl text-xs sm:text-sm font-medium active:scale-[0.98]">
                            Seeing someone
                        </button>
                    </div>
                </div>

                <!-- ------------------------------------------ -->
                <!-- RESPONSE: TAKEN (Graceful, Low-Key Exit)   -->
                <!-- ------------------------------------------ -->
                <div v-else-if="choice === 'taken'" class="text-center py-2 space-y-2">
                    <p class="font-serif text-lg sm:text-xl font-medium text-stone-800">
                        Totally get it!
                    </p>
                    <p class="text-xs sm:text-sm text-stone-500 font-light leading-relaxed">
                        No worries at all—hope you have a great rest of your week.
                    </p>
                    <p class="text-[11px] text-stone-600 font-mono pt-2">
                        This note will close quietly.
                    </p>
                </div>

                <!-- ------------------------------------------ -->
                <!-- RESPONSE: SINGLE -> CONTACT FORM UNFOLDS   -->
                <!-- ------------------------------------------ -->
                <div v-else-if="choice === 'single' && !isSubmitted" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-stone-600">
                            Nice to meet you. Where can I say hi?
                        </p>
                        <button 
                            @click="choice = null" 
                            class="text-[11px] text-stone-600 hover:text-stone-700 underline underline-offset-2">
                            back
                        </button>
                    </div>

                    <!-- Contact Channel Selector -->
                    <div class="inline-flex p-1 bg-stone-100 rounded-xl text-xs font-medium w-full">
                        <button 
                            type="button" 
                            @click="contactType = 'instagram'"
                            :class="contactType === 'instagram' ? 'bg-white text-stone-900 shadow-xs' : 'text-stone-500 hover:text-stone-800'"
                            class="flex-1 py-1.5 rounded-lg transition-all text-center">
                            Instagram
                        </button>
                        <button 
                            type="button" 
                            @click="contactType = 'whatsapp'"
                            :class="contactType === 'whatsapp' ? 'bg-white text-stone-900 shadow-xs' : 'text-stone-500 hover:text-stone-800'"
                            class="flex-1 py-1.5 rounded-lg transition-all text-center">
                            WhatsApp
                        </button>
                    </div>

                    <form @submit.prevent="submitContact" class="space-y-3">
                        <div>
                            <input 
                                v-model="contactValue" 
                                type="text" 
                                required
                                :placeholder="contactType === 'instagram' ? '@your_instagram' : '0812...'" 
                                class="w-full px-3.5 py-2.5 rounded-xl bg-stone-50 border border-stone-200 text-stone-800 text-xs sm:text-sm placeholder:text-stone-400 focus:bg-white focus:border-stone-400 focus:outline-none transition-all">
                        </div>

                        <div>
                            <input 
                                v-model="recipientNote" 
                                type="text" 
                                placeholder="Anything you'd like to add? (optional)" 
                                class="w-full px-3.5 py-2.5 rounded-xl bg-stone-50 border border-stone-200 text-stone-800 text-xs sm:text-sm placeholder:text-stone-400 focus:bg-white focus:border-stone-400 focus:outline-none transition-all">
                        </div>

                        <button 
                            type="submit" 
                            :disabled="isSubmitting || !contactValue.trim()"
                            class="w-full py-2.5 px-4 rounded-xl btn-dark disabled:opacity-40 text-xs sm:text-sm font-medium active:scale-[0.99] transition-all">
                            <span v-if="!isSubmitting">Send</span>
                            <span v-else>Sending...</span>
                        </button>
                    </form>
                </div>

                <!-- ------------------------------------------ -->
                <!-- RESPONSE: SUBMITTED SUCCESS                -->
                <!-- ------------------------------------------ -->
                <div v-else-if="isSubmitted" class="text-center py-2 space-y-2">
                    <p class="font-serif text-lg sm:text-xl font-medium text-stone-800">
                        Got it.
                    </p>
                    <p class="text-xs sm:text-sm text-stone-500 font-light leading-relaxed">
                        I'll say hi soon! Have a good day ahead.
                    </p>
                    <p class="text-[11px] text-stone-600 font-mono pt-2">
                        Closing note...
                    </p>
                </div>

            </section>

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
                const choice = ref(null);
                const contactType = ref('instagram');
                const contactValue = ref('');
                const recipientNote = ref('');
                const isSubmitting = ref(false);
                const isSubmitted = ref(false);

                const currentDate = new Date().toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric'
                });

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

                const selectChoice = async (val) => {
                    choice.value = val;
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
                                status: val,
                                relationship_choice: val,
                                current_step: val === 'single' ? 2 : 1
                            })
                        });

                        if (val === 'taken') {
                            setTimeout(() => {
                                triggerDestroyServer();
                            }, 4000);
                        } else {
                            saveStep(2);
                        }
                    } catch (err) {
                        if (val === 'taken') {
                            setTimeout(() => {
                                triggerDestroyServer();
                            }, 4000);
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
                                note_from_recipient: recipientNote.value.trim(),
                                current_step: 3
                            })
                        });

                        if (window.confetti) {
                            window.confetti({
                                particleCount: 30,
                                spread: 45,
                                origin: { y: 0.65 },
                                colors: ['#c49a88', '#dec4b5', '#e8dad0']
                            });
                        }

                        isSubmitted.value = true;

                        setTimeout(() => {
                            triggerDestroyServer();
                        }, 4000);
                    } catch (err) {
                        isSubmitted.value = true;
                        setTimeout(() => {
                            triggerDestroyServer();
                        }, 4000);
                    } finally {
                        isSubmitting.value = false;
                    }
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
                    if (isDestroyed.value) return;

                    // Resume step if previously answered single
                    let localStep = null;
                    try {
                        localStep = localStorage.getItem('secret_step_' + noteData.value.token);
                    } catch (e) {}

                    const step = noteData.value.currentStep || (localStep ? parseInt(localStep) : 0);
                    if (step >= 2) {
                        choice.value = 'single';
                    }
                });

                return {
                    noteData,
                    isDestroyed,
                    choice,
                    contactType,
                    contactValue,
                    recipientNote,
                    isSubmitting,
                    isSubmitted,
                    currentDate,
                    selectChoice,
                    submitContact
                };
            }
        }).mount('#app');
    </script>
</body>
</html>

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

        /* Smooth Subtle Step Transitions */
        .fade-enter-active, .fade-leave-active {
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .fade-enter-from {
            opacity: 0;
            transform: translateY(4px);
        }
        .fade-leave-to {
            opacity: 0;
            transform: translateY(-4px);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-8 selection:bg-stone-200 selection:text-stone-900">

    <!-- Main Container -->
    <main id="app" v-cloak class="w-full max-w-[380px] sm:max-w-[420px] mx-auto transition-all duration-300">

        <!-- ============================================== -->
        <!-- STATE: NOTE DESTROYED / CLOSED                 -->
        <!-- ============================================== -->
        <div v-if="isDestroyed" class="stationery-card rounded-2xl p-7 sm:p-8 text-center">
            <div class="text-[11px] uppercase tracking-widest text-stone-400 font-medium mb-3">
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
        <!-- STATE: ACTIVE PERSONAL MEMO                    -->
        <!-- ============================================== -->
        <article v-else class="stationery-card rounded-2xl p-6 sm:p-8 relative">

            <!-- Top Memo Header -->
            <header class="flex items-center justify-between pb-3.5 mb-5 memo-divider text-[11px] text-stone-600 tracking-wider">
                <span class="font-medium uppercase">Personal Note</span>
                <span>@{{ currentDate }}</span>
            </header>

            <transition name="fade" mode="out-in">

                <!-- ------------------------------------------ -->
                <!-- PAGE 1: THE INTRO NOTE (NO CONFRONTATION)  -->
                <!-- ------------------------------------------ -->
                <div v-if="currentStep === 1" key="step1" class="space-y-4">
                    <div class="space-y-3.5 text-[13px] sm:text-sm leading-relaxed text-stone-700 font-light">
                        <p class="font-serif text-2xl sm:text-3xl font-semibold text-stone-900 mb-1">
                            Hey,
                        </p>

                        <p>
                            I know this is a bit out of nowhere, but I've noticed you around the room and just wanted to introduce myself.
                        </p>

                        <p>
                            You always look focused, and I didn't want to awkwardly interrupt you while you were busy, so I thought leaving this was simpler.
                        </p>
                    </div>

                    <div class="pt-4 mt-6 memo-divider flex justify-end">
                        <button 
                            @click="goToStep(2)"
                            class="btn-dark py-2 px-4 rounded-xl text-xs font-medium flex items-center gap-1.5 active:scale-[0.98]">
                            <span>Continue</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>


                <!-- ------------------------------------------ -->
                <!-- PAGE 2: THE GENTLE ASK / VIBE CHECK        -->
                <!-- ------------------------------------------ -->
                <div v-else-if="currentStep === 2" key="step2" class="space-y-4">
                    <div class="space-y-3 text-[13px] sm:text-sm leading-relaxed text-stone-700 font-light">
                        <p>
                            If you're open to getting coffee or just chatting sometime, I'd love to say hi properly.
                        </p>

                        <p class="text-xs text-stone-600 pt-1">
                            Before that though—just to make sure I'm not overstepping:
                        </p>

                        <div class="p-4 rounded-xl bg-stone-50 border border-stone-200/70 text-center space-y-3 mt-1">
                            <p class="text-xs font-medium text-stone-800">
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

                        <p class="text-[11px] text-stone-600 italic text-center">
                            (No worries either way—whichever is true for you!)
                        </p>
                    </div>

                    <div class="pt-3 mt-5 memo-divider flex justify-start">
                        <button 
                            @click="goToStep(1)"
                            class="text-[11px] text-stone-600 hover:text-stone-700 underline underline-offset-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>previous note</span>
                        </button>
                    </div>
                </div>


                <!-- ------------------------------------------ -->
                <!-- PAGE 2B: GRACEFUL EXIT (IF TAKEN)          -->
                <!-- ------------------------------------------ -->
                <div v-else-if="currentStep === 'exit_taken'" key="exit_taken" class="text-center py-4 space-y-2.5">
                    <p class="font-serif text-xl sm:text-2xl font-medium text-stone-800">
                        Totally get it!
                    </p>
                    <p class="text-xs sm:text-sm text-stone-500 font-light leading-relaxed">
                        No worries at all—hope you have a great rest of your week.
                    </p>
                    <p class="text-[11px] text-stone-600 font-mono pt-3">
                        This note will close quietly.
                    </p>
                </div>


                <!-- ------------------------------------------ -->
                <!-- PAGE 3: CONTACT FORM (IF SINGLE)           -->
                <!-- ------------------------------------------ -->
                <div v-else-if="currentStep === 3" key="step3" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-serif text-xl font-medium text-stone-900">
                                Nice to meet you.
                            </p>
                            <p class="text-xs text-stone-500 font-light mt-0.5">
                                Where's best to say hi?
                            </p>
                        </div>
                        <button 
                            @click="goToStep(2)"
                            class="text-[11px] text-stone-600 hover:text-stone-700 underline underline-offset-2">
                            back
                        </button>
                    </div>

                    <!-- Channel selector -->
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
                <!-- PAGE 4: SUBMITTED SUCCESS                  -->
                <!-- ------------------------------------------ -->
                <div v-else-if="currentStep === 'submitted'" key="submitted" class="text-center py-4 space-y-2.5">
                    <p class="font-serif text-xl sm:text-2xl font-medium text-stone-800">
                        Got it.
                    </p>
                    <p class="text-xs sm:text-sm text-stone-500 font-light leading-relaxed">
                        I'll say hi soon! Have a good day ahead.
                    </p>
                    <p class="text-[11px] text-stone-600 font-mono pt-3">
                        Closing note...
                    </p>
                </div>

            </transition>

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
                const currentStep = ref(1); // Default Page 1: The Intro Note
                const contactType = ref('instagram');
                const contactValue = ref('');
                const recipientNote = ref('');
                const isSubmitting = ref(false);

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
                        body: JSON.stringify({ current_step: typeof step === 'number' ? step : 2 })
                    }).catch(e => console.error('Step sync error:', e));
                };

                const goToStep = (step) => {
                    currentStep.value = step;
                    saveStep(step);
                };

                const selectChoice = async (val) => {
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
                                current_step: val === 'single' ? 3 : 2
                            })
                        });

                        if (val === 'taken') {
                            currentStep.value = 'exit_taken';
                            setTimeout(() => {
                                triggerDestroyServer();
                            }, 4000);
                        } else {
                            currentStep.value = 3;
                            saveStep(3);
                        }
                    } catch (err) {
                        if (val === 'taken') {
                            currentStep.value = 'exit_taken';
                            setTimeout(() => {
                                triggerDestroyServer();
                            }, 4000);
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

                    // Resume step if user previously advanced
                    let localStep = null;
                    try {
                        localStep = localStorage.getItem('secret_step_' + noteData.value.token);
                    } catch (e) {}

                    const savedStep = noteData.value.currentStep || (localStep ? parseInt(localStep) : 1);
                    if (savedStep && savedStep > 1) {
                        currentStep.value = savedStep;
                    } else {
                        currentStep.value = 1;
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
                    currentDate,
                    goToStep,
                    selectChoice,
                    submitContact
                };
            }
        }).mount('#app');
    </script>
</body>
</html>

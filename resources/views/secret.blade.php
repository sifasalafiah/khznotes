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
        [v-cloak] { display: none; }
        body {
            background-color: #f6f4ef;
            color: #2b2623;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        /* Clean Luxury Stationery Card */
        .stationery-card {
            background: #ffffff;
            border: 1px solid #ebdcd0;
            box-shadow: 
                0 2px 4px rgba(0, 0, 0, 0.02),
                0 20px 40px -15px rgba(80, 60, 50, 0.07);
        }

        /* Subtle separator line */
        .stationery-line {
            border-bottom: 1px dashed #e8ded4;
        }

        /* Understated Action Buttons */
        .btn-choice {
            background: #faf8f5;
            border: 1px solid #e2d6ca;
            color: #3b322e;
            transition: all 0.2s ease;
        }
        .btn-choice:hover {
            background: #f1ebd8;
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
            transition: opacity 0.22s ease, transform 0.22s ease;
        }
        .step-fade-enter-from {
            opacity: 0;
            transform: translateY(6px);
        }
        .step-fade-leave-to {
            opacity: 0;
            transform: translateY(-6px);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10 selection:bg-stone-200 selection:text-stone-900">

    <!-- Main Container: Responsive from small mobile (360px) to tablet and laptop -->
    <main id="app" v-cloak class="w-full max-w-[400px] sm:max-w-[430px] md:max-w-[460px] mx-auto transition-all duration-300 my-auto">

        <!-- ============================================== -->
        <!-- STATE: NOTE DESTROYED / CLOSED                 -->
        <!-- ============================================== -->
        <div v-if="isDestroyed" class="stationery-card rounded-3xl p-8 sm:p-10 text-center">
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
        <article v-else class="stationery-card rounded-3xl p-6 sm:p-8 md:p-9 relative flex flex-col justify-between">

            <!-- Top Memo Header: Clean & Balanced -->
            <header class="flex items-center justify-between pb-4 mb-6 sm:mb-7 stationery-line text-[11px] text-stone-600 tracking-wider">
                <span class="font-medium uppercase">Personal Note</span>
                <span class="text-stone-600">@{{ currentDate }}</span>
            </header>

            <!-- Step Content Container with Smooth Animation -->
            <transition name="step-fade" mode="out-in">

                <!-- ------------------------------------------ -->
                <!-- PAGE 1: THE INTRO NOTE                     -->
                <!-- ------------------------------------------ -->
                <div v-if="currentStep === 1" key="step1" class="flex flex-col justify-between">
                    <!-- Body Text -->
                    <div class="space-y-4 text-[13.5px] sm:text-[14.5px] leading-relaxed text-stone-700 font-light">
                        <p class="font-serif text-2xl sm:text-3xl font-semibold text-stone-900 tracking-tight mb-2">
                            Hey,
                        </p>

                        <p>
                            I know this is a bit out of nowhere, but I've noticed you around the room and just wanted to introduce myself.
                        </p>

                        <p>
                            You always look focused, and I didn't want to awkwardly interrupt you while you were busy, so I thought leaving this was simpler.
                        </p>
                    </div>

                    <!-- Footer Action Area: Plenty of breathing room, zero cramped lines -->
                    <div class="pt-8 sm:pt-10 mt-6 sm:mt-8 border-t border-stone-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <span class="text-[11px] text-stone-600 font-light hidden sm:inline">
                            A small hello
                        </span>
                        <button 
                            @click="goToStep(2)"
                            class="w-full sm:w-auto btn-dark py-3 sm:py-2.5 px-6 rounded-xl text-xs sm:text-sm font-medium flex items-center justify-center gap-2 active:scale-[0.98] shadow-xs">
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
                <div v-else-if="currentStep === 2" key="step2" class="flex flex-col justify-between">
                    <!-- Body Text -->
                    <div class="space-y-4 text-[13.5px] sm:text-[14.5px] leading-relaxed text-stone-700 font-light">
                        <p>
                            If you're open to getting coffee or just chatting sometime, I'd love to say hi properly.
                        </p>

                        <p class="text-xs text-stone-600 pt-1">
                            Before that though—just to make sure I'm not overstepping:
                        </p>

                        <!-- Choice Card with Generous Touch Targets -->
                        <div class="p-5 rounded-2xl bg-stone-50/80 border border-stone-200/70 text-center space-y-3.5 mt-2 shadow-2xs">
                            <p class="text-xs sm:text-[13px] font-medium text-stone-800">
                                Are you seeing anyone?
                            </p>
                            <div class="grid grid-cols-2 gap-3">
                                <button 
                                    @click="selectChoice('single')"
                                    :disabled="isSubmitting"
                                    class="btn-choice py-3 px-3 rounded-xl text-xs sm:text-sm font-medium active:scale-[0.98]">
                                    Single :)
                                </button>
                                <button 
                                    @click="selectChoice('taken')"
                                    :disabled="isSubmitting"
                                    class="btn-choice py-3 px-3 rounded-xl text-xs sm:text-sm font-medium active:scale-[0.98]">
                                    Seeing someone
                                </button>
                            </div>
                        </div>

                        <p class="text-[11px] text-stone-600 italic text-center pt-1">
                            (No worries either way—whichever is true for you!)
                        </p>
                    </div>

                    <!-- Footer Action Area -->
                    <div class="pt-6 sm:pt-8 mt-6 sm:mt-8 border-t border-stone-100 flex items-center justify-between">
                        <button 
                            @click="goToStep(1)"
                            class="text-xs text-stone-600 hover:text-stone-700 py-1.5 flex items-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>Read previous</span>
                        </button>
                    </div>
                </div>


                <!-- ------------------------------------------ -->
                <!-- PAGE 2B: GRACEFUL EXIT (IF TAKEN)          -->
                <!-- ------------------------------------------ -->
                <div v-else-if="currentStep === 'exit_taken'" key="exit_taken" class="text-center py-8 sm:py-10 space-y-3">
                    <p class="font-serif text-2xl sm:text-3xl font-medium text-stone-800 leading-snug">
                        Totally get it!
                    </p>
                    <p class="text-xs sm:text-sm text-stone-500 font-light leading-relaxed max-w-[280px] mx-auto">
                        No worries at all—hope you have a great rest of your week.
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
                <div v-else-if="currentStep === 3" key="step3" class="space-y-5">
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
                    <div class="grid grid-cols-2 gap-1.5 p-1 bg-stone-100 rounded-xl text-xs font-medium">
                        <button 
                            type="button" 
                            @click="contactType = 'instagram'"
                            :class="contactType === 'instagram' ? 'bg-white text-stone-900 shadow-xs' : 'text-stone-500 hover:text-stone-800'"
                            class="py-2 rounded-lg transition-all text-center">
                            Instagram
                        </button>
                        <button 
                            type="button" 
                            @click="contactType = 'whatsapp'"
                            :class="contactType === 'whatsapp' ? 'bg-white text-stone-900 shadow-xs' : 'text-stone-500 hover:text-stone-800'"
                            class="py-2 rounded-lg transition-all text-center">
                            WhatsApp
                        </button>
                    </div>

                    <!-- Form Inputs with comfortable touch targets -->
                    <form @submit.prevent="submitContact" class="space-y-3.5 pt-1">
                        <div>
                            <label class="block text-[11px] font-medium text-stone-500 mb-1">
                                @{{ contactType === 'instagram' ? 'Your Instagram Username' : 'Your WhatsApp Number' }}
                            </label>
                            <input 
                                v-model="contactValue" 
                                type="text" 
                                required
                                :placeholder="contactType === 'instagram' ? '@your_username' : '0812xxxx...'" 
                                class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-stone-800 text-xs sm:text-sm placeholder:text-stone-400 focus:bg-white focus:border-stone-400 focus:ring-2 focus:ring-stone-200 focus:outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-[11px] font-medium text-stone-500 mb-1">
                                Short note <span class="text-stone-600 font-normal">(optional)</span>
                            </label>
                            <input 
                                v-model="recipientNote" 
                                type="text" 
                                placeholder="Anything you'd like to add" 
                                class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-stone-800 text-xs sm:text-sm placeholder:text-stone-400 focus:bg-white focus:border-stone-400 focus:ring-2 focus:ring-stone-200 focus:outline-none transition-all">
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


                <!-- ------------------------------------------ -->
                <!-- PAGE 4: SUBMITTED SUCCESS                  -->
                <!-- ------------------------------------------ -->
                <div v-else-if="currentStep === 'submitted'" key="submitted" class="text-center py-8 sm:py-10 space-y-3">
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
                const currentStep = ref(1); // Default Page 1
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

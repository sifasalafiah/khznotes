<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Note Studio & Live Monitor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    }
                }
            }
        }
    </script>

    <!-- QRCode JS Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.4/build/qrcode.min.js"></script>

    <style>
        [v-cloak] { display: none; }
        body {
            background-color: #0b0f19;
            color: #f1f5f9;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .card-preview-gold {
            background: radial-gradient(circle at 10% 20%, #1e2638 0%, #0d121f 90%);
            border: 1px solid rgba(245, 158, 11, 0.25);
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.7);
        }
        @media print {
            body * { visibility: hidden; }
            #printable-card, #printable-card * { visibility: visible; }
            #printable-card {
                position: absolute;
                left: 0;
                top: 0;
                width: 85mm;
                height: 55mm;
                border: 1px solid #000 !important;
                background: #fff !important;
                color: #000 !important;
                padding: 10mm !important;
            }
        }
    </style>
</head>
<body class="min-h-screen p-4 md:p-8">

    <div id="app" v-cloak class="max-w-6xl mx-auto space-y-8">

        <!-- Top Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-zinc-800 pb-6">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-semibold uppercase tracking-widest text-emerald-400">MySQL Real-Time Active</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
                    <span>Secret Note Studio</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-300 border border-amber-500/20 font-mono font-normal">v1.0</span>
                </h1>
                <p class="text-xs text-zinc-400 mt-1">
                    Generate confidential single-use notes, preview printable QR cards, and monitor live responses.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button 
                    @click="fetchNotes" 
                    class="px-3.5 py-2 rounded-xl bg-zinc-800/80 hover:bg-zinc-700 text-xs text-zinc-300 font-medium transition-colors flex items-center gap-2 border border-zinc-700/60">
                    <svg class="w-3.5 h-3.5" :class="{ 'animate-spin': isRefreshing }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Refresh Data</span>
                </button>
            </div>
        </header>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- LEFT COLUMN: Generator & Card Preview (5 cols) -->
            <div class="lg:col-span-5 space-y-6">

                <!-- Generator Card -->
                <div class="p-6 rounded-2xl bg-zinc-900/70 border border-zinc-800">
                    <h2 class="text-base font-bold text-white mb-1 flex items-center gap-2">
                        <span>✨ Generate New Secret Note</span>
                    </h2>
                    <p class="text-xs text-zinc-400 mb-5">
                        Customize the context. The note will burn immediately after she views or submits it.
                    </p>

                    <form @submit.prevent="createNote" class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-zinc-300 mb-1">Room / Event Context</label>
                            <input 
                                v-model="form.room_context" 
                                type="text" 
                                placeholder="e.g. Room clue or leave empty" 
                                class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-white focus:border-amber-400 focus:outline-none transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-zinc-300 mb-1">Recipient Nickname / Hint (Optional)</label>
                            <input 
                                v-model="form.recipient_name" 
                                type="text" 
                                placeholder="e.g. To the girl across the table" 
                                class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-white focus:border-amber-400 focus:outline-none transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-zinc-300 mb-1">Your Name / Nickname (Optional)</label>
                            <input 
                                v-model="form.creator_name" 
                                type="text" 
                                placeholder="e.g. Someone nearby / your name" 
                                class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-white focus:border-amber-400 focus:outline-none transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-zinc-300 mb-1">Your Clue / Hint (Optional)</label>
                            <input 
                                v-model="form.creator_hint" 
                                type="text" 
                                placeholder="e.g. A guy in black shirt sitting 2 seats away" 
                                class="w-full px-3.5 py-2 rounded-xl bg-zinc-950 border border-zinc-800 text-xs text-white focus:border-amber-400 focus:outline-none transition-colors">
                        </div>

                        <button 
                            type="submit" 
                            :disabled="isCreating"
                            class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-zinc-950 font-bold text-xs tracking-wide shadow-md shadow-orange-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                            <span v-if="!isCreating">Create Note & Generate QR Code</span>
                            <span v-else>Generating...</span>
                        </button>
                    </form>
                </div>


                <!-- Physical Card Preview -->
                <div v-if="selectedNote" class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-400">
                            Printable Physical Card Preview
                        </h3>
                        <button 
                            @click="printCard" 
                            class="px-2.5 py-1 text-[11px] rounded-lg bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-medium transition-colors flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>Print Card</span>
                        </button>
                    </div>

                    <!-- Custom Domain / Wi-Fi IP setting for QR Code -->
                    <div class="p-3 rounded-xl bg-zinc-950 border border-zinc-800 text-xs space-y-1">
                        <label class="block text-[11px] font-medium text-zinc-400">
                            Host URL encoded in QR Code:
                        </label>
                        <input 
                            v-model="customBaseUrl" 
                            @input="updateQrCode"
                            type="text" 
                            placeholder="e.g. http://10.200.99.149:8000 or your tunnel domain"
                            class="w-full px-3 py-1.5 rounded-lg bg-zinc-900 border border-zinc-700/80 text-xs font-mono text-amber-300 focus:outline-none">
                        <p class="text-[10px] text-zinc-500">
                            Tip: To test from phone on Wi-Fi, use your laptop's Wi-Fi IP (e.g. <span class="font-mono text-zinc-400">http://10.200.99.149:8000</span>).
                        </p>
                    </div>

                    <!-- The Mini Business Card Layout -->
                    <div id="printable-card" class="card-preview-gold rounded-2xl p-5 text-zinc-100 flex items-center gap-4 relative overflow-hidden">
                        <!-- Left: QR Code -->
                        <div class="bg-white p-2 rounded-xl shadow-md shrink-0">
                            <canvas id="qr-canvas" class="w-24 h-24 block"></canvas>
                        </div>

                        <!-- Right: Text Content -->
                        <div class="flex-1 min-w-0">
                            <div class="inline-block px-2 py-0.5 rounded text-[9px] font-mono tracking-widest uppercase bg-amber-500/20 text-amber-300 mb-1.5">
                                Confidential
                            </div>
                            <h4 class="font-serif font-bold text-sm text-white leading-tight mb-1">
                                A quiet hello for you.
                            </h4>
                            <p class="text-[10px] text-zinc-300 leading-snug line-clamp-2 mb-2 font-light">
                                Scan once when you have a calm minute. (Burns after reading).
                            </p>
                            <div class="text-[9px] font-mono text-zinc-400">
                                @{{ selectedNote.room_context || 'Private Note' }}
                            </div>
                        </div>
                    </div>

                    <!-- Direct Link Preview -->
                    <div class="p-3 rounded-xl bg-zinc-900/60 border border-zinc-800 text-xs flex items-center justify-between gap-2">
                        <span class="font-mono text-[11px] text-zinc-400 truncate">@{{ getNoteUrl(selectedNote.token) }}</span>
                        <div class="flex items-center gap-1 shrink-0">
                            <button 
                                @click="copyLink(getNoteUrl(selectedNote.token))" 
                                class="px-2 py-1 rounded bg-zinc-800 hover:bg-zinc-700 text-[10px] text-zinc-300 font-medium">
                                Copy
                            </button>
                            <a 
                                :href="getNoteUrl(selectedNote.token)" 
                                target="_blank" 
                                class="px-2 py-1 rounded bg-amber-500/20 hover:bg-amber-500/30 text-[10px] text-amber-300 font-medium">
                                Open ↗
                            </a>
                        </div>
                    </div>
                </div>

            </div>


            <!-- RIGHT COLUMN: Live Responses Table (7 cols) -->
            <div class="lg:col-span-7 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-white flex items-center gap-2">
                            <span>📡 Live MySQL Responses</span>
                            <span class="text-xs font-mono font-normal text-zinc-400">(@{{ notes.length }} notes total)</span>
                        </h2>
                        <p class="text-xs text-zinc-400">Auto-refreshing every 3s to capture views, single/taken status, and contacts.</p>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="notes.length === 0" class="p-12 text-center rounded-2xl bg-zinc-900/50 border border-zinc-800">
                    <p class="text-sm text-zinc-400 mb-3">No secret notes created yet.</p>
                    <p class="text-xs text-zinc-500">Fill in the generator form on the left to create your first note!</p>
                </div>

                <!-- Notes List -->
                <div v-else class="space-y-3">
                    <div 
                        v-for="note in notes" 
                        :key="note.id" 
                        :class="[
                            'p-4 rounded-2xl border transition-all duration-300',
                            selectedNote && selectedNote.id === note.id ? 'bg-zinc-850 border-amber-500/40 shadow-lg shadow-black/40' : 'bg-zinc-900/70 border-zinc-800/80 hover:border-zinc-700'
                        ]">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                            <div class="flex items-center gap-2.5">
                                <span class="font-mono text-xs font-bold text-zinc-300 bg-zinc-800 px-2 py-0.5 rounded">
                                    #@{{ note.token }}
                                </span>
                                <span class="text-xs text-zinc-400 truncate max-w-[180px]">
                                    @{{ note.room_context }}
                                </span>
                            </div>

                            <!-- Status Badge & Step Tracker -->
                            <div class="flex items-center gap-2">
                                <span 
                                    v-if="!note.is_destroyed && note.status !== 'submitted'" 
                                    class="text-[10px] font-mono px-2 py-0.5 rounded bg-zinc-800 text-zinc-400 border border-zinc-700/60">
                                    @{{ note.current_step ? 'Step ' + note.current_step + ' of 3' : 'Sealed' }}
                                </span>
                                <span 
                                    :class="getStatusBadgeClass(note)"
                                    class="text-[10px] font-semibold uppercase tracking-wider px-2.5 py-0.5 rounded-full border">
                                    @{{ formatStatus(note) }}
                                </span>
                            </div>
                        </div>

                        <!-- Highlight: Received Contact (If submitted!) -->
                        <div v-if="note.contact_value" class="mb-3 p-3 rounded-xl bg-gradient-to-r from-emerald-950/40 via-zinc-900 to-zinc-900 border border-emerald-500/40">
                            <div class="text-[10px] font-semibold uppercase tracking-wider text-emerald-400 mb-1 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                <span>🎉 Contact Received!</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="font-mono text-sm font-bold text-white flex items-center gap-2">
                                    <span>@{{ note.contact_type === 'instagram' ? '📸' : '💬' }}</span>
                                    <span>@{{ note.contact_value }}</span>
                                </div>
                                <a 
                                    v-if="note.contact_type === 'instagram'" 
                                    :href="'https://instagram.com/' + note.contact_value.replace('@','')" 
                                    target="_blank"
                                    class="px-2.5 py-1 text-[11px] rounded bg-emerald-500 hover:bg-emerald-400 text-zinc-950 font-bold transition-colors">
                                    Open IG ↗
                                </a>
                                <a 
                                    v-else 
                                    :href="'https://wa.me/' + formatWhatsApp(note.contact_value)" 
                                    target="_blank"
                                    class="px-2.5 py-1 text-[11px] rounded bg-emerald-500 hover:bg-emerald-400 text-zinc-950 font-bold transition-colors">
                                    Chat WA ↗
                                </a>
                            </div>

                            <!-- Optional Note From Her -->
                            <div v-if="note.note_from_recipient" class="mt-2 pt-2 border-t border-zinc-800/80 text-xs text-zinc-300 italic font-light">
                                "@{{ note.note_from_recipient }}"
                            </div>
                        </div>

                        <!-- Meta details & Actions -->
                        <div class="flex flex-wrap items-center justify-between gap-2 text-[11px] text-zinc-500 pt-2 border-t border-zinc-800/60">
                            <div class="space-x-3">
                                <span>Created: @{{ formatTime(note.created_at) }}</span>
                                <span v-if="note.opened_at" class="text-zinc-400 font-medium">• Opened: @{{ formatTime(note.opened_at) }}</span>
                                <span v-if="note.is_destroyed" class="text-orange-400 font-medium">• Burned</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <button 
                                    @click="selectNote(note)" 
                                    class="text-xs text-amber-400 hover:text-amber-300 font-medium">
                                    View QR Card
                                </button>
                                <span class="text-zinc-700">|</span>
                                <a 
                                    :href="getNoteUrl(note.token)" 
                                    target="_blank" 
                                    class="text-xs text-zinc-400 hover:text-white">
                                    Open
                                </a>
                                <span v-if="!note.is_destroyed" class="text-zinc-700">|</span>
                                <button 
                                    v-if="!note.is_destroyed" 
                                    @click="burnNote(note)" 
                                    class="text-xs text-red-400 hover:text-red-300">
                                    Burn Now
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Inject initial notes from Laravel Controller -->
    <script>
        window.__INITIAL_NOTES__ = @json($notes);
    </script>

    <!-- Vue 3 Admin Application -->
    <script type="module">
        import { createApp, ref, onMounted, nextTick } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';

        createApp({
            setup() {
                const notes = ref(window.__INITIAL_NOTES__ || []);
                const selectedNote = ref(notes.value[0] || null);
                const isRefreshing = ref(false);
                const isCreating = ref(false);
                const customBaseUrl = ref(window.location.origin);

                const form = ref({
                    room_context: 'Same Room',
                    recipient_name: '',
                    creator_name: '',
                    creator_hint: ''
                });

                const getCsrfToken = () => {
                    const el = document.querySelector('meta[name="csrf-token"]');
                    return el ? el.getAttribute('content') : '';
                };

                const getNoteUrl = (token) => {
                    const base = (customBaseUrl.value || window.location.origin).replace(/\/$/, '');
                    return `${base}/secret/${token}`;
                };

                const renderQr = (text) => {
                    nextTick(() => {
                        const canvas = document.getElementById('qr-canvas');
                        if (canvas && window.QRCode) {
                            window.QRCode.toCanvas(canvas, text, {
                                width: 96,
                                margin: 1,
                                color: {
                                    dark: '#000000',
                                    light: '#ffffff'
                                }
                            });
                        }
                    });
                };

                const updateQrCode = () => {
                    if (selectedNote.value) {
                        renderQr(getNoteUrl(selectedNote.value.token));
                    }
                };

                const selectNote = (note) => {
                    selectedNote.value = note;
                    renderQr(getNoteUrl(note.token));
                };

                const fetchNotes = async () => {
                    isRefreshing.value = true;
                    try {
                        const res = await fetch('/api/notes/live');
                        if (res.ok) {
                            const data = await res.json();
                            notes.value = data;
                            if (selectedNote.value) {
                                const found = data.find(n => n.id === selectedNote.value.id);
                                if (found) selectedNote.value = found;
                            }
                        }
                    } catch (e) {
                        console.error('Failed to refresh notes:', e);
                    } finally {
                        isRefreshing.value = false;
                    }
                };

                const createNote = async () => {
                    isCreating.value = true;
                    try {
                        const res = await fetch('/api/notes', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(form.value)
                        });

                        if (res.ok) {
                            const data = await res.json();
                            notes.value.unshift(data.note);
                            selectNote(data.note);
                            // reset hints
                            form.value.recipient_name = '';
                            form.value.creator_hint = '';
                        }
                    } catch (e) {
                        console.error('Failed to create note:', e);
                    } finally {
                        isCreating.value = false;
                    }
                };

                const burnNote = async (note) => {
                    if (!confirm('Are you sure you want to permanently burn this note?')) return;
                    try {
                        const res = await fetch(`/api/secret/${note.token}/destroy`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            }
                        });
                        if (res.ok) {
                            fetchNotes();
                        }
                    } catch (e) {
                        console.error('Failed to burn note:', e);
                    }
                };

                const copyLink = (url) => {
                    navigator.clipboard.writeText(url);
                    alert('Copied secret note link to clipboard!');
                };

                const printCard = () => {
                    window.print();
                };

                const formatStatus = (note) => {
                    if (note.is_destroyed) return 'Burned / Expired';
                    if (note.status === 'submitted') return 'Contact Submitted';
                    if (note.status === 'single') return 'Single';
                    if (note.status === 'taken') return 'Taken';
                    if (note.status === 'opened') return 'Opened';
                    return 'Not Opened Yet';
                };

                const getStatusBadgeClass = (note) => {
                    if (note.is_destroyed) return 'bg-zinc-800 text-zinc-500 border-zinc-700';
                    if (note.status === 'submitted') return 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30';
                    if (note.status === 'single') return 'bg-purple-500/20 text-purple-300 border-purple-500/30';
                    if (note.status === 'taken') return 'bg-amber-500/20 text-amber-300 border-amber-500/30';
                    if (note.status === 'opened') return 'bg-blue-500/20 text-blue-300 border-blue-500/30';
                    return 'bg-zinc-800 text-zinc-400 border-zinc-700';
                };

                const formatTime = (ts) => {
                    if (!ts) return '';
                    const d = new Date(ts);
                    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                };

                const formatWhatsApp = (phone) => {
                    let cleaned = phone.replace(/[^0-9]/g, '');
                    if (cleaned.startsWith('0')) {
                        cleaned = '62' + cleaned.substring(1);
                    }
                    return cleaned;
                };

                onMounted(() => {
                    if (selectedNote.value) {
                        renderQr(getNoteUrl(selectedNote.value.token));
                    }
                    // Auto-refresh poll every 3 seconds to catch live events
                    setInterval(fetchNotes, 3000);
                });

                return {
                    notes,
                    selectedNote,
                    form,
                    customBaseUrl,
                    isRefreshing,
                    isCreating,
                    getNoteUrl,
                    updateQrCode,
                    selectNote,
                    fetchNotes,
                    createNote,
                    burnNote,
                    copyLink,
                    printCard,
                    formatStatus,
                    getStatusBadgeClass,
                    formatTime,
                    formatWhatsApp
                };
            }
        }).mount('#app');
    </script>
</body>
</html>

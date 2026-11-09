<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Note Studio & Live Monitor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Cormorant Garamond & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
                            400: '#f29588',
                            500: '#e56c5c',
                        }
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
            background-color: #fcfbf9;
            color: #2e2825;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        /* Luxury Linen Stationery Card */
        .card-stationery-blush {
            background: linear-gradient(135deg, #ffffff 0%, #fffbf8 100%);
            border: 1px solid #ebdcd0;
            box-shadow: 0 15px 35px -8px rgba(195, 155, 135, 0.2);
        }

        @media print {
            body * { visibility: hidden; }
            #printable-card, #printable-card * { visibility: visible; }
            #printable-card {
                position: absolute;
                left: 0;
                top: 0;
                width: 85mm;
                height: 54mm;
                border: 1px solid #d4c2b4 !important;
                background: #fffdfa !important;
                color: #2e2825 !important;
                padding: 6mm !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body class="min-h-screen p-4 sm:p-6 md:p-8">

    <div id="app" v-cloak class="max-w-6xl mx-auto space-y-7">

        <!-- Top Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-stone-200/80 pb-5">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[11px] font-semibold uppercase tracking-widest text-emerald-700">MySQL Live Synced</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-serif font-bold text-stone-800 tracking-tight flex items-center gap-3">
                    <span>Secret Note Studio</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-600 border border-rose-200/60 font-sans font-medium">Soft & Minimal</span>
                </h1>
                <p class="text-xs text-stone-500 mt-1">
                    Create confidential one-time notes, print boutique stationery QR cards, and monitor live responses.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button 
                    @click="fetchNotes" 
                    class="px-3.5 py-2 rounded-xl bg-white hover:bg-stone-50 text-xs text-stone-600 font-medium transition-colors flex items-center gap-2 border border-stone-200 shadow-sm">
                    <svg class="w-3.5 h-3.5" :class="{ 'animate-spin': isRefreshing }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Refresh</span>
                </button>
            </div>
        </header>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-7">

            <!-- LEFT COLUMN: Generator & Physical Card Preview (5 cols) -->
            <div class="lg:col-span-5 space-y-6">

                <!-- Generator Box -->
                <div class="p-6 rounded-3xl bg-white border border-stone-200/80 shadow-sm">
                    <h2 class="text-sm font-bold text-stone-800 mb-1 flex items-center gap-2">
                        <svg class="w-4 h-4 text-stone-600 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Create a New Note</span>
                    </h2>
                    <p class="text-xs text-stone-500 mb-5 font-light">
                        Generates a unique link and QR code. It will resume automatically if she pauses, and closes permanently after completion.
                    </p>

                    <form @submit.prevent="createNote" class="space-y-4">
                        <div>
                            <label class="block text-[11px] font-semibold text-stone-600 mb-1">Your Name / Nickname (Optional)</label>
                            <input 
                                v-model="form.creator_name" 
                                type="text" 
                                placeholder="e.g. Someone nearby / leave empty" 
                                class="w-full px-3.5 py-2.5 rounded-xl bg-stone-50/70 border border-stone-200 text-xs text-stone-800 focus:border-rose-400 focus:bg-white focus:outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-stone-600 mb-1">Recipient Hint (Optional)</label>
                            <input 
                                v-model="form.recipient_name" 
                                type="text" 
                                placeholder="e.g. To you across the table" 
                                class="w-full px-3.5 py-2.5 rounded-xl bg-stone-50/70 border border-stone-200 text-xs text-stone-800 focus:border-rose-400 focus:bg-white focus:outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-stone-600 mb-1">Your Clue / Hint (Optional)</label>
                            <input 
                                v-model="form.creator_hint" 
                                type="text" 
                                placeholder="e.g. Sitting 2 tables away" 
                                class="w-full px-3.5 py-2.5 rounded-xl bg-stone-50/70 border border-stone-200 text-xs text-stone-800 focus:border-rose-400 focus:bg-white focus:outline-none transition-all">
                        </div>

                        <button 
                            type="submit" 
                            :disabled="isCreating"
                            class="w-full py-3.5 px-4 rounded-xl bg-rose-500 hover:bg-rose-600 text-white font-medium text-xs tracking-wide shadow-md shadow-rose-500/25 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                            <span v-if="!isCreating">Generate Note & QR Card</span>
                            <span v-else>Generating...</span>
                        </button>
                    </form>
                </div>


                <!-- Physical Printable Card Preview -->
                <div v-if="selectedNote" class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-stone-600">
                            Printable Boutique Card Preview
                        </h3>
                        <button 
                            @click="printCard" 
                            class="px-2.5 py-1 text-[11px] rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-700 font-medium transition-colors flex items-center gap-1.5 border border-stone-200">
                            <svg class="w-3.5 h-3.5 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>Print Card</span>
                        </button>
                    </div>

                    <!-- Custom Domain / Wi-Fi IP setting for QR Code -->
                    <div class="p-3.5 rounded-2xl bg-white border border-stone-200 text-xs space-y-1 shadow-sm">
                        <label class="block text-[11px] font-semibold text-stone-600">
                            Host URL encoded in QR Code:
                        </label>
                        <input 
                            v-model="customBaseUrl" 
                            @input="updateQrCode"
                            type="text" 
                            placeholder="e.g. http://10.200.99.149:8000 or public domain"
                            class="w-full px-3 py-1.5 rounded-lg bg-stone-50 border border-stone-200 text-xs font-mono text-stone-800 focus:outline-none focus:border-rose-400">
                        <p class="text-[10px] text-stone-400">
                            For phone scanning on same Wi-Fi, enter laptop IP (e.g. <span class="font-mono text-stone-600">http://10.200.99.149:8000</span>).
                        </p>
                    </div>

                    <!-- The Luxury Stationery Card Layout -->
                    <div id="printable-card" class="card-stationery-blush rounded-[24px] p-5 text-stone-800 flex items-center gap-4 relative overflow-hidden">
                        <!-- Left: QR Code -->
                        <div class="bg-white p-2 rounded-xl shadow-sm border border-stone-100 shrink-0">
                            <canvas id="qr-canvas" class="w-24 h-24 block"></canvas>
                        </div>

                        <!-- Right: Text Content -->
                        <div class="flex-1 min-w-0">
                            <div class="inline-block px-2 py-0.5 rounded text-[9px] font-mono tracking-widest uppercase bg-rose-50 text-rose-600 mb-1 border border-rose-100 font-semibold">
                                Just For You
                            </div>
                            <h4 class="font-serif font-bold text-base text-stone-800 leading-tight mb-1">
                                A quiet hello for you.
                            </h4>
                            <p class="text-[11px] text-stone-500 leading-snug line-clamp-2 mb-2 font-light">
                                Scan once when you have a calm minute. (Single-use note).
                            </p>
                            <div class="text-[9px] font-mono text-stone-400">
                                @{{ selectedNote.recipient_name || 'Private Note' }}
                            </div>
                        </div>
                    </div>

                    <!-- Direct Link Preview -->
                    <div class="p-3 rounded-2xl bg-white border border-stone-200 text-xs flex items-center justify-between gap-2 shadow-sm">
                        <span class="font-mono text-[11px] text-stone-500 truncate">@{{ getNoteUrl(selectedNote.token) }}</span>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button 
                                @click="copyLink(getNoteUrl(selectedNote.token))" 
                                class="px-2.5 py-1 rounded-lg bg-stone-100 hover:bg-stone-200 text-[11px] text-stone-700 font-medium transition-colors">
                                Copy
                            </button>
                            <a 
                                :href="getNoteUrl(selectedNote.token)" 
                                target="_blank" 
                                class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-[11px] text-rose-600 font-semibold transition-colors">
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
                        <h2 class="text-sm font-bold text-stone-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-stone-600 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            <span>Live MySQL Responses</span>
                            <span class="text-xs font-mono font-normal text-stone-400">(@{{ notes.length }} notes total)</span>
                        </h2>
                        <p class="text-xs text-stone-500 font-light">Auto-refreshing every 3s to capture views, single/taken status, and contacts.</p>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="notes.length === 0" class="p-12 text-center rounded-3xl bg-white border border-stone-200 shadow-sm">
                    <p class="text-sm text-stone-500 mb-2">No secret notes created yet.</p>
                    <p class="text-xs text-stone-400 font-light">Fill in the generator form on the left to create your first note!</p>
                </div>

                <!-- Notes List -->
                <div v-else class="space-y-3">
                    <div 
                        v-for="note in notes" 
                        :key="note.id" 
                        :class="[
                            'p-4 sm:p-5 rounded-3xl border transition-all duration-300',
                            selectedNote && selectedNote.id === note.id ? 'bg-white border-rose-300 shadow-md ring-2 ring-rose-100' : 'bg-white border-stone-200/80 hover:border-stone-300 shadow-sm'
                        ]">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                            <div class="flex items-center gap-2.5">
                                <span class="font-mono text-xs font-bold text-stone-700 bg-stone-100 px-2 py-0.5 rounded-lg border border-stone-200/60">
                                    #@{{ note.token }}
                                </span>
                                <span class="text-xs text-stone-500 truncate max-w-[180px]">
                                    @{{ note.recipient_name || 'Someone Special' }}
                                </span>
                            </div>

                            <!-- Status Badge & Step Tracker -->
                            <div class="flex items-center gap-2">
                                <span 
                                    v-if="!note.is_destroyed && note.status !== 'submitted'" 
                                    class="text-[10px] font-mono px-2 py-0.5 rounded-md bg-stone-100 text-stone-500 border border-stone-200">
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
                        <div v-if="note.contact_value" class="mb-3 p-3.5 rounded-2xl bg-gradient-to-r from-emerald-50 via-white to-white border border-emerald-300 shadow-sm">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 mb-1 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                <span>Contact Received</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="font-mono text-sm font-bold text-stone-800 flex items-center gap-2">
                                    <svg v-if="note.contact_type === 'instagram'" class="w-4 h-4 text-stone-600 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                    </svg>
                                    <svg v-else class="w-4 h-4 text-stone-600 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a.75.75 0 01-.84-.84c.162-.962.36-1.92.593-2.868C3.762 15.65 3 13.916 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                    </svg>
                                    <span>@{{ note.contact_value }}</span>
                                </div>
                                <a 
                                    v-if="note.contact_type === 'instagram'" 
                                    :href="'https://instagram.com/' + note.contact_value.replace('@','')" 
                                    target="_blank"
                                    class="px-3 py-1 text-xs rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-bold transition-colors shadow-sm">
                                    Open IG ↗
                                </a>
                                <a 
                                    v-else 
                                    :href="'https://wa.me/' + formatWhatsApp(note.contact_value)" 
                                    target="_blank"
                                    class="px-3 py-1 text-xs rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-bold transition-colors shadow-sm">
                                    Chat WA ↗
                                </a>
                            </div>

                            <!-- Note From Her -->
                            <div v-if="note.note_from_recipient" class="mt-2 pt-2 border-t border-emerald-100 text-xs text-stone-600 italic font-light">
                                "@{{ note.note_from_recipient }}"
                            </div>
                        </div>

                        <!-- Meta details & Actions -->
                        <div class="flex flex-wrap items-center justify-between gap-2 text-[11px] text-stone-400 pt-2 border-t border-stone-100">
                            <div class="space-x-3">
                                <span>Created: @{{ formatTime(note.created_at) }}</span>
                                <span v-if="note.opened_at" class="text-stone-600 font-medium">• Opened: @{{ formatTime(note.opened_at) }}</span>
                                <span v-if="note.is_destroyed" class="text-rose-500 font-medium">• Closed</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <button 
                                    @click="selectNote(note)" 
                                    class="text-xs text-rose-600 hover:text-rose-700 font-semibold">
                                    View QR Card
                                </button>
                                <span class="text-stone-300">|</span>
                                <a 
                                    :href="getNoteUrl(note.token)" 
                                    target="_blank" 
                                    class="text-xs text-stone-600 hover:text-stone-900">
                                    Open
                                </a>
                                <span v-if="!note.is_destroyed" class="text-stone-300">|</span>
                                <button 
                                    v-if="!note.is_destroyed" 
                                    @click="burnNote(note)" 
                                    class="text-xs text-red-500 hover:text-red-700">
                                    Close Note
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
                                    dark: '#2e2825',
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
                            form.value.recipient_name = '';
                            form.value.creator_name = '';
                            form.value.creator_hint = '';
                        }
                    } catch (e) {
                        console.error('Failed to create note:', e);
                    } finally {
                        isCreating.value = false;
                    }
                };

                const burnNote = async (note) => {
                    if (!confirm('Are you sure you want to permanently close this note?')) return;
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
                        console.error('Failed to close note:', e);
                    }
                };

                const copyLink = (url) => {
                    navigator.clipboard.writeText(url);
                    alert('Copied link to clipboard!');
                };

                const printCard = () => {
                    window.print();
                };

                const formatStatus = (note) => {
                    if (note.is_destroyed) return 'Closed';
                    if (note.status === 'submitted') return 'Contact Received';
                    if (note.status === 'single') return 'Single';
                    if (note.status === 'taken') return 'Taken';
                    if (note.status === 'opened') return 'Opened';
                    return 'Unopened';
                };

                const getStatusBadgeClass = (note) => {
                    if (note.is_destroyed) return 'bg-stone-100 text-stone-400 border-stone-200';
                    if (note.status === 'submitted') return 'bg-emerald-50 text-emerald-700 border-emerald-200 font-bold';
                    if (note.status === 'single') return 'bg-rose-50 text-rose-700 border-rose-200 font-bold';
                    if (note.status === 'taken') return 'bg-amber-50 text-amber-700 border-amber-200';
                    if (note.status === 'opened') return 'bg-blue-50 text-blue-700 border-blue-200';
                    return 'bg-stone-50 text-stone-500 border-stone-200';
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

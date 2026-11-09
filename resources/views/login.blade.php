<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Studio Access</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #f7f5f0;
            color: #2b2623;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .login-card {
            background: #ffffff;
            border: 1px solid #ebdcd0;
            box-shadow: 
                0 2px 4px rgba(0, 0, 0, 0.02),
                0 20px 40px -15px rgba(80, 60, 50, 0.07);
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
<body class="min-h-screen flex items-center justify-center p-4 selection:bg-stone-200 selection:text-stone-900">

    <div class="w-full max-w-[360px] mx-auto">
        <div class="login-card rounded-3xl p-7 sm:p-8 text-center">
            
            <div class="w-12 h-12 mx-auto mb-4 rounded-2xl bg-stone-100/90 border border-stone-200/60 flex items-center justify-center text-stone-600">
                <svg class="w-5 h-5 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>

            <h1 class="font-serif text-2xl font-semibold text-stone-900 mb-1">
                Studio Access
            </h1>
            <p class="text-xs text-stone-500 font-light mb-6">
                Please enter your passcode to continue.
            </p>

            @if(session('error'))
                <div class="mb-4 p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-xs font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('studio.login.submit') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <input 
                        type="password" 
                        name="passcode" 
                        required 
                        autofocus
                        placeholder="Enter passcode"
                        class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-stone-900 text-xs sm:text-sm text-center tracking-widest placeholder:tracking-normal placeholder:text-stone-400 focus:bg-white focus:border-stone-400 focus:ring-2 focus:ring-stone-200 focus:outline-none transition-all shadow-xs">
                </div>

                <button 
                    type="submit" 
                    class="w-full py-3 px-4 rounded-xl btn-dark text-xs sm:text-sm font-medium active:scale-[0.99] transition-all flex items-center justify-center gap-1.5 shadow-xs">
                    <span>Unlock</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-stone-100 text-[10px] text-stone-400 font-mono">
                Protected Private Workspace
            </div>
        </div>
    </div>

</body>
</html>

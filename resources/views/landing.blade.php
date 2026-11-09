<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Quiet Space</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,400&family=Plus+Jakarta+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Cormorant Garamond"', 'serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #f6f4ef;
            color: #2b2623;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .landing-card {
            background: #ffffff;
            border: 1px solid #ebdcd0;
            box-shadow: 
                0 2px 4px rgba(0, 0, 0, 0.02),
                0 20px 40px -15px rgba(80, 60, 50, 0.07);
        }
        .stationery-line {
            border-bottom: 1px dashed #e8ded4;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 selection:bg-stone-200 selection:text-stone-900">

    <main class="w-full max-w-[380px] sm:max-w-[420px] mx-auto text-center">
        
        <div class="landing-card rounded-3xl p-8 sm:p-10 relative overflow-hidden">
            
            <!-- Top Subtle Tag -->
            <div class="text-[10px] uppercase tracking-widest text-stone-400 font-medium pb-4 mb-6 stationery-line flex items-center justify-between">
                <span>Personal Space</span>
                <span id="live-time" class="font-mono text-stone-400"></span>
            </div>

            <!-- Main Heading in Cormorant Garamond -->
            <h1 class="font-serif text-3xl sm:text-4xl font-semibold text-stone-900 mb-3 tracking-tight">
                A quiet space.
            </h1>

            <!-- Gentle, Natural Subtitle -->
            <p class="text-xs sm:text-sm text-stone-600 font-light leading-relaxed mb-6 px-2">
                There is nothing public hosted here right now. If you were given a private link or QR card, please use that direct address to view it.
            </p>

            <!-- Bottom Note -->
            <div class="pt-5 border-t border-stone-100 flex items-center justify-between text-[11px] text-stone-400 font-light">
                <span class="italic">Have a peaceful day.</span>
                
                <!-- Discreet Access to Studio -->
                <a href="/studio" title="Manage" class="text-stone-300 hover:text-stone-500 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </a>
            </div>

        </div>

    </main>

    <script>
        // Update live time display
        const updateTime = () => {
            const el = document.getElementById('live-time');
            if (el) {
                const now = new Date();
                el.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }
        };
        updateTime();
        setInterval(updateTime, 10000);
    </script>

</body>
</html>


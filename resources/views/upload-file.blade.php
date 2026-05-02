<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PromptOS — AI Prompt Optimization</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['Instrument Serif', 'Georgia', 'serif'],
                        sans: ['DM Sans', 'sans-serif'],
                        mono: ['DM Mono', 'monospace'],
                    },
                    colors: {
                        stone: {
                            950: '#0c0a09',
                        },
                        amber: {
                            350: '#fbbf7a',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.4s ease forwards',
                        'slide-up': 'slideUp 0.35s ease forwards',
                        'pulse-slow': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'spin-slow': 'spin 1.4s linear infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    },
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }

        .gradient-text {
            background: linear-gradient(135deg, #d97706, #f59e0b, #b45309);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-grid {
            background-image:
                linear-gradient(rgba(217,119,6,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(217,119,6,0.06) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .toggle-input:checked + .toggle-track {
            background-color: #d97706;
        }
        .toggle-input:checked + .toggle-track .toggle-thumb {
            transform: translateX(1.25rem);
        }

        .skeleton {
            background: linear-gradient(90deg, #1c1917 25%, #292524 50%, #1c1917 75%);
            background-size: 200% 100%;
            animation: shimmer 1.6s infinite;
        }
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .provider-badge {
            font-feature-settings: 'tnum';
        }

        /* Custom scrollbar for code blocks */
        .code-scroll::-webkit-scrollbar { height: 4px; }
        .code-scroll::-webkit-scrollbar-track { background: #1c1917; }
        .code-scroll::-webkit-scrollbar-thumb { background: #44403c; border-radius: 2px; }

        .select-arrow {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23a8a29e' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            appearance: none;
        }

        .step-connector::after {
            content: '';
            position: absolute;
            top: 20px;
            left: calc(100% + 8px);
            width: calc(100% - 16px);
            height: 1px;
            background: linear-gradient(90deg, #44403c, transparent);
        }

        @media (max-width: 768px) {
            .step-connector::after { display: none; }
        }
    </style>
</head>
<body class="bg-stone-950 text-stone-100 font-sans antialiased">

    {{-- ===== NAVBAR ===== --}}
    <header class="fixed top-0 left-0 right-0 z-50 border-b border-stone-800/60 bg-stone-950/80 backdrop-blur-md">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
            <a href="#" class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-md bg-amber-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-stone-950" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                </div>
                <span class="font-semibold text-sm tracking-tight text-stone-100">PromptOS</span>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm text-stone-400">
                <a href="/" class="hover:text-stone-100 transition-colors">Features</a>
                <a href="/upload-file" class="hover:text-stone-100 transition-colors">Upload File</a>
                <a href="/" class="hover:text-stone-100 transition-colors">History</a>
            </nav>

            <a href="#optimizer" class="text-sm bg-amber-500 hover:bg-amber-400 text-stone-950 font-semibold px-4 py-1.5 rounded-md transition-colors">
                Get started
            </a>
        </div>
    </header>

    <main>
        {{-- ===== HERO ===== --}}

        <div class="max-w-xl mx-auto mt-32 p-6 bg-stone-900 border border-stone-800 rounded-xl">

    <h2 class="text-lg font-semibold mb-4">Upload File to S3</h2>

    <form method="POST" action="{{ route('upload.file') }}" enctype="multipart/form-data">
        @csrf

        <input type="file" name="file"
            class="block w-full text-sm text-stone-300 mb-4
            file:mr-4 file:py-2 file:px-4
            file:rounded file:border-0
            file:text-sm file:font-semibold
            file:bg-amber-500 file:text-stone-900
            hover:file:bg-amber-400" required>

        <button type="submit"
            class="bg-amber-500 hover:bg-amber-400 text-stone-950 px-4 py-2 rounded">
            Upload
        </button>
    </form>

    @if(session('url'))
        <div class="mt-4 text-green-400">
            File uploaded:
            <a href="{{ session('url') }}" target="_blank" class="underline">View File</a>
        </div>
    @endif

</div>
      
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="border-t border-stone-800/60 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded bg-amber-500 flex items-center justify-center">
                    <svg class="w-3 h-3 text-stone-950" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold text-stone-400">PromptOS</span>
            </div>
            <p class="text-xs text-stone-700">Built with Laravel · Powered by OpenAI, Gemini & Groq</p>
        </div>
    </footer>

</body>
</html>
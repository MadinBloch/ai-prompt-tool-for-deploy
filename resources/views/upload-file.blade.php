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

   {{-- ===== UPDATED UPLOAD SECTION ===== --}}
<div class="max-w-2xl mx-auto mt-32 px-6">
    <div class="bg-stone-900/50 border border-stone-800 rounded-2xl p-8 backdrop-blur-sm relative overflow-hidden">
        
        <!-- Decorative Grid Background (matching hero) -->
        <div class="absolute inset-0 hero-grid opacity-20 pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex flex-col items-center text-center mb-8">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                </div>
                <h2 class="text-2xl font-serif text-stone-100 italic">Upload Source Assets</h2>
                <p class="text-stone-400 text-sm mt-1">Upload files to S3 for prompt context and optimization.</p>
            </div>

            <form method="POST" action="{{ route('upload.file') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div class="group relative border-2 border-dashed border-stone-700 hover:border-amber-500/50 transition-colors rounded-xl p-10 flex flex-col items-center justify-center bg-stone-950/40">
                    <input type="file" name="file" id="file-upload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required onchange="updateFileName(this)">
                    
                    <div id="upload-placeholder" class="text-center">
                        <p class="text-sm font-medium text-stone-300">Click to upload or drag and drop</p>
                        <p class="text-xs text-stone-500 mt-1 uppercase tracking-widest">PDF, TXT, JSON, OR CSV (MAX. 10MB)</p>
                    </div>

                    <!-- Selected File State (Hidden by default) -->
                    <div id="file-selected" class="hidden flex items-center gap-3 bg-stone-800/50 px-4 py-2 rounded-lg border border-stone-700">
                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span id="file-name" class="text-sm font-mono text-stone-200">No file selected</span>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-amber-500 hover:bg-amber-400 text-stone-950 font-semibold py-3 rounded-lg transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <span>Finalize Upload</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </form>

            @if(session('url'))
                <div class="mt-6 animate-slide-up bg-emerald-500/10 border border-emerald-500/20 p-4 rounded-lg flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-sm text-emerald-400 font-medium">Successfully uploaded</span>
                    </div>
                    <a href="{{ session('url') }}" target="_blank" class="text-xs font-bold text-emerald-400 underline underline-offset-4 hover:text-emerald-300 transition-colors uppercase tracking-widest">
                        View Asset
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ===== SCRIPTS FOR UI INTERACTION ===== --}}
<script>
    function updateFileName(input) {
        const placeholder = document.getElementById('upload-placeholder');
        const selectedState = document.getElementById('file-selected');
        const nameDisplay = document.getElementById('file-name');

        if (input.files && input.files.length > 0) {
            placeholder.classList.add('hidden');
            selectedState.classList.remove('hidden');
            nameDisplay.textContent = input.files[0].name;
        }
    }
</script>

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
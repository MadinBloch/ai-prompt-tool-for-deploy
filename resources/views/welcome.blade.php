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
                <a href="#features" class="hover:text-stone-100 transition-colors">Features</a>
                <a href="/upload-file" class="hover:text-stone-100 transition-colors">Upload File</a>
                <a href="/" class="hover:text-stone-100 transition-colors">History</a>
                <a href="{{ route('prompt-optimizer.upload-file') }}" class="hover:text-stone-100 transition-colors">Upload File</a>
            </nav>

            <a href="#optimizer" class="text-sm bg-amber-500 hover:bg-amber-400 text-stone-950 font-semibold px-4 py-1.5 rounded-md transition-colors">
                Get started
            </a>
        </div>
    </header>

    <main>
        {{-- ===== HERO ===== --}}
        <section class="relative pt-32 pb-20 sm:pt-40 sm:pb-28 hero-grid overflow-hidden">
            {{-- Ambient glow --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 text-center">
                <div class="inline-flex items-center gap-2 text-xs font-medium text-amber-400 bg-amber-500/10 border border-amber-500/20 rounded-full px-3.5 py-1.5 mb-8">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse-slow"></span>
                    Multi-provider · Caching · Queue support
                </div>

                <h1 class="font-serif text-5xl sm:text-6xl lg:text-7xl text-stone-50 leading-tight mb-6">
                    Prompts that work<br>
                    <span class="italic gradient-text">harder for you.</span>
                </h1>

                <p class="text-stone-400 text-lg sm:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
                    Multi-agent optimization with intelligent caching, provider failover across OpenAI, Gemini, and Groq, and async queue support — so your prompts are always production-ready.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="#optimizer" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-400 active:bg-amber-600 text-stone-950 font-semibold px-6 py-3 rounded-lg transition-colors text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                        Optimize a Prompt
                    </a>
                    <a href="#how-it-works" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-stone-300 hover:text-stone-100 border border-stone-700 hover:border-stone-500 px-6 py-3 rounded-lg transition-colors text-sm">
                        See how it works
                    </a>
                </div>
            </div>
        </section>

        {{-- ===== FEATURES ===== --}}
        <section id="features" class="py-20 sm:py-28 border-t border-stone-800/60">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="text-center mb-14">
                    <p class="text-xs font-semibold uppercase tracking-widest text-amber-500 mb-3">Capabilities</p>
                    <h2 class="font-serif text-3xl sm:text-4xl text-stone-100">Everything a prompt needs.</h2>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @php
                        $features = [
                            ['icon' => 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z', 'title' => 'Multi-agent Optimization', 'desc' => 'Specialized agents handle context enrichment, clarity, token reduction, and structure in parallel.'],
                            ['icon' => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125', 'title' => 'Intelligent Caching', 'desc' => 'Identical prompts skip re-processing, returning cached results instantly with zero latency.'],
                            ['icon' => 'M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5', 'title' => 'Provider Failover', 'desc' => 'Automatic failover across OpenAI, Gemini, and Groq ensures your requests always complete.'],
                            ['icon' => 'M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z', 'title' => 'Queue Support', 'desc' => 'Fire-and-forget async mode processes heavy optimizations in the background via Laravel queues.'],
                        ];
                    @endphp

                    @foreach($features as $feature)
                        <div class="bg-stone-900/60 border border-stone-800 rounded-xl p-5 hover:border-stone-700 transition-colors group">
                            <div class="w-9 h-9 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mb-4 group-hover:bg-amber-500/15 transition-colors">
                                <svg class="w-4.5 h-4.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-stone-100 text-sm mb-1.5">{{ $feature['title'] }}</h3>
                            <p class="text-stone-500 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===== HOW IT WORKS ===== --}}
        <section id="how-it-works" class="py-20 sm:py-28 border-t border-stone-800/60 bg-stone-900/30">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="text-center mb-14">
                    <p class="text-xs font-semibold uppercase tracking-widest text-amber-500 mb-3">Process</p>
                    <h2 class="font-serif text-3xl sm:text-4xl text-stone-100">Three steps to a better prompt.</h2>
                </div>

                <div class="grid md:grid-cols-3 gap-6 md:gap-8">
                    @php
                        $steps = [
                            ['num' => '01', 'title' => 'Paste your prompt', 'desc' => 'Drop in any raw prompt — no formatting needed. Choose your provider or let the system auto-select.'],
                            ['num' => '02', 'title' => 'Agents analyze & rewrite', 'desc' => 'Multiple specialized agents run in parallel: enriching context, reducing tokens, and sharpening structure.'],
                            ['num' => '03', 'title' => 'Copy & deploy', 'desc' => 'Get a production-ready, optimized prompt with a full diff of every improvement made.'],
                        ];
                    @endphp

                    @foreach($steps as $i => $step)
                        <div class="relative flex flex-col">
                            @if($i < 2)
                                <div class="hidden md:block absolute top-5 left-full w-full h-px bg-gradient-to-r from-stone-700 to-transparent z-10 -ml-2"></div>
                            @endif
                            <div class="flex items-center gap-3 mb-4">
                                <span class="font-mono text-xs font-medium text-amber-500 bg-amber-500/10 border border-amber-500/20 rounded px-2 py-1">{{ $step['num'] }}</span>
                                <div class="h-px flex-1 bg-stone-800"></div>
                            </div>
                            <h3 class="font-semibold text-stone-100 text-base mb-2">{{ $step['title'] }}</h3>
                            <p class="text-stone-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===== OPTIMIZER APP ===== --}}
        <section id="optimizer" class="py-20 sm:py-28 border-t border-stone-800/60">
            <div class="max-w-3xl mx-auto px-4 sm:px-6">
                <div class="text-center mb-10">
                    <p class="text-xs font-semibold uppercase tracking-widest text-amber-500 mb-3">Optimizer</p>
                    <h2 class="font-serif text-3xl sm:text-4xl text-stone-100">Optimize your prompt.</h2>
                    <p class="text-stone-500 text-sm mt-3">Paste any prompt below and choose your settings.</p>
                </div>

                {{-- Error alert --}}
                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 bg-red-950/60 border border-red-800/60 rounded-xl px-4 py-3.5 animate-fade-in">
                        <svg class="w-4 h-4 text-red-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <p class="text-sm text-red-300">{{ $errors->first() }}</p>
                    </div>
                @endif

                {{-- Queue success alert --}}
                @if (session('optimization_status'))
                    <div class="mb-6 flex items-start gap-3 bg-stone-800/80 border border-stone-700 rounded-xl px-4 py-3.5 animate-fade-in">
                        <svg class="w-4 h-4 text-amber-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-stone-200">Queued for processing</p>
                            <p class="text-xs text-stone-500 mt-0.5 font-mono">Job ID: {{ session('optimization_status.job_id') ?? 'pending' }}</p>
                        </div>
                    </div>
                @endif

                {{-- FORM CARD --}}
                <form method="POST" action="{{ route('prompt-optimizer.optimize') }}" id="optimizerForm" class="bg-stone-900/70 border border-stone-800 rounded-2xl overflow-hidden" onsubmit="handleSubmit(event)">
                    @csrf

                    {{-- Textarea --}}
                    <div class="p-5 sm:p-6">
                        <label class="block text-xs font-semibold uppercase tracking-widest text-stone-500 mb-3">Your Prompt</label>
                        <textarea
                            name="prompt"
                            rows="8"
                            placeholder="Write a function that..."
                            class="w-full bg-stone-950/70 border border-stone-700/60 rounded-xl px-4 py-3.5 text-stone-100 text-sm font-mono placeholder-stone-600 resize-none focus:outline-none focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/10 transition-all leading-relaxed"
                        >{{ old('prompt') }}</textarea>
                    </div>

                    <div class="border-t border-stone-800 px-5 sm:px-6 py-4 flex flex-col sm:flex-row items-start sm:items-center gap-5">

                        {{-- Provider select --}}
                        <div class="flex-1 min-w-0">
                            <label class="block text-xs font-semibold uppercase tracking-widest text-stone-500 mb-2">Provider</label>
                            <select name="provider" class="select-arrow w-full bg-stone-950/70 border border-stone-700/60 rounded-lg px-3 py-2.5 text-sm text-stone-200 focus:outline-none focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/10 transition-all cursor-pointer pr-9">
                                <option value="">Auto failover</option>
                                <option value="openai" @selected(old('provider') === 'openai')>OpenAI</option>
                                <option value="gemini" @selected(old('provider') === 'gemini')>Gemini</option>
                                <option value="groq" @selected(old('provider') === 'groq')>Groq</option>
                            </select>
                        </div>

                        {{-- Toggles --}}
                        <div class="flex flex-col gap-3 flex-shrink-0">
                            {{-- Toggle: Enhance context --}}
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex-shrink-0">
                                    <input type="checkbox" name="enhance_context" value="1" class="sr-only toggle-input peer" @checked(old('enhance_context', true))>
                                    <div class="toggle-track w-9 h-5 rounded-full bg-stone-700 peer-checked:bg-amber-500 transition-colors flex items-center">
                                        <div class="toggle-thumb w-3.5 h-3.5 rounded-full bg-white shadow ml-0.5 transition-transform peer-checked:translate-x-4"></div>
                                    </div>
                                </div>
                                <span class="text-sm text-stone-400 group-hover:text-stone-300 transition-colors select-none">Enhance context</span>
                            </label>

                            {{-- Toggle: Async queue --}}
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex-shrink-0">
                                    <input type="checkbox" name="async" value="1" class="sr-only toggle-input peer" @checked(old('async'))>
                                    <div class="toggle-track w-9 h-5 rounded-full bg-stone-700 peer-checked:bg-amber-500 transition-colors flex items-center">
                                        <div class="toggle-thumb w-3.5 h-3.5 rounded-full bg-white shadow ml-0.5 transition-transform peer-checked:translate-x-4"></div>
                                    </div>
                                </div>
                                <span class="text-sm text-stone-400 group-hover:text-stone-300 transition-colors select-none">Queue request</span>
                            </label>
                        </div>
                    </div>

                    {{-- Submit button --}}
                    <div class="border-t border-stone-800 px-5 sm:px-6 py-4 flex items-center justify-between gap-4 bg-stone-950/40">
                        <p class="text-xs text-stone-600 hidden sm:block">Results appear below after processing.</p>
                        <button type="submit" id="submitBtn"
                            class="flex-shrink-0 inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 active:bg-amber-600 disabled:opacity-50 disabled:cursor-not-allowed text-stone-950 font-semibold px-5 py-2.5 rounded-lg transition-colors text-sm ml-auto">
                            <svg id="btnIcon" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                            </svg>
                            <span id="btnText">Optimize Prompt</span>
                        </button>
                    </div>
                </form>

                {{-- ===== RESULT ===== --}}
                @if (session('optimization_result'))
                    @php $result = session('optimization_result'); @endphp
                    <div class="mt-6 animate-slide-up">

                        {{-- Stats row --}}
                        <div class="grid grid-cols-3 gap-3 mb-4">
                            <div class="bg-stone-900/70 border border-stone-800 rounded-xl p-4 text-center">
                                <p class="text-xs text-stone-500 mb-1 uppercase tracking-widest font-semibold">Provider</p>
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-full px-2.5 py-1 provider-badge">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                    {{ ucfirst($result['provider'] ?? 'auto') }}
                                </span>
                            </div>
                            <div class="bg-stone-900/70 border border-stone-800 rounded-xl p-4 text-center">
                                <p class="text-xs text-stone-500 mb-1 uppercase tracking-widest font-semibold">Model</p>
                                <span class="text-sm font-mono font-medium text-stone-200 truncate block">{{ $result['model'] ?? '—' }}</span>
                            </div>
                            <div class="bg-stone-900/70 border border-amber-800/40 rounded-xl p-4 text-center bg-amber-950/20">
                                <p class="text-xs text-stone-500 mb-1 uppercase tracking-widest font-semibold">Token ↓</p>
                                <span class="text-lg font-semibold text-amber-400 font-mono">
                                    {{ ($result['estimated_tokens_before'] - $result['estimated_tokens_after']) ?? '—' }}
                                </span>
                            </div>
                        </div>

                        @if(!empty($result['context_summary']))
                            <div class="bg-stone-900/50 border border-stone-800 rounded-xl px-4 py-3.5 mb-4 flex items-start gap-3">
                                <svg class="w-4 h-4 text-amber-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                                <p class="text-sm text-stone-300 leading-relaxed">{{ $result['context_summary'] }}</p>
                            </div>
                        @endif

                        {{-- Optimized prompt --}}
                        <div class="bg-stone-900/70 border border-stone-800 rounded-xl overflow-hidden mb-4">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-stone-800 bg-stone-950/40">
                                <span class="text-xs font-semibold uppercase tracking-widest text-stone-500">Optimized Prompt</span>
                                <button onclick="copyPrompt()" class="inline-flex items-center gap-1.5 text-xs text-stone-400 hover:text-amber-400 transition-colors font-medium" id="copyBtn">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                                    </svg>
                                    <span id="copyLabel">Copy</span>
                                </button>
                            </div>
                            <pre id="optimizedPromptText" class="code-scroll overflow-x-auto text-sm font-mono text-stone-300 p-4 leading-relaxed whitespace-pre-wrap">{{ $result['optimized_prompt'] }}</pre>
                        </div>

                        {{-- Improvements --}}
                        @if (!empty($result['improvements']))
                            <div class="bg-stone-900/70 border border-stone-800 rounded-xl overflow-hidden">
                                <div class="px-4 py-3 border-b border-stone-800 bg-stone-950/40">
                                    <span class="text-xs font-semibold uppercase tracking-widest text-stone-500">Improvements</span>
                                </div>
                                <ul class="p-4 space-y-2">
                                    @foreach ((array) $result['improvements'] as $improvement)
                                        <li class="flex items-start gap-2.5 text-sm text-stone-300">
                                            <svg class="w-4 h-4 text-amber-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                            <span>{{ is_string($improvement) ? $improvement : json_encode($improvement) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </section>

        {{-- ===== HISTORY ===== --}}
        <section id="history" class="py-20 sm:py-28 border-t border-stone-800/60 bg-stone-900/20">
            <div class="max-w-3xl mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-amber-500 mb-1">Log</p>
                        <h2 class="font-serif text-2xl text-stone-100">Recent history</h2>
                    </div>
                </div>

                @forelse ($latestOptimizations as $optimization)
                    <article class="flex items-start gap-4 py-4 border-b border-stone-800/60 last:border-0 group">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-stone-800 border border-stone-700 flex items-center justify-center group-hover:border-stone-600 transition-colors">
                            @php
                                $statusColor = match($optimization->status) {
                                    'completed' => 'text-green-400',
                                    'failed'    => 'text-red-400',
                                    'queued'    => 'text-amber-400',
                                    default     => 'text-stone-400',
                                };
                            @endphp
                            <span class="w-2 h-2 rounded-full {{ $statusColor === 'text-green-400' ? 'bg-green-400' : ($statusColor === 'text-red-400' ? 'bg-red-400' : 'bg-amber-400') }}"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-mono text-stone-600">#{{ $optimization->id }}</span>
                                <span class="text-xs px-1.5 py-0.5 rounded font-medium
                                    {{ $optimization->status === 'completed' ? 'bg-green-950/60 text-green-400' : '' }}
                                    {{ $optimization->status === 'failed' ? 'bg-red-950/60 text-red-400' : '' }}
                                    {{ !in_array($optimization->status, ['completed','failed']) ? 'bg-stone-800 text-stone-400' : '' }}
                                ">{{ $optimization->status }}</span>
                                @if ($optimization->provider_used)
                                    <span class="text-xs text-stone-600">via {{ $optimization->provider_used }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-stone-400 truncate">{{ \Illuminate\Support\Str::limit($optimization->source_prompt, 120) }}</p>
                        </div>
                    </article>
                @empty
                    <div class="py-12 text-center">
                        <div class="w-10 h-10 rounded-xl bg-stone-800 border border-stone-700 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-5 h-5 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <p class="text-stone-600 text-sm">No optimizations yet. Submit a prompt to get started.</p>
                    </div>
                @endforelse
            </div>
        </section>
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

    <script>
        // ---- Toggle fix (pure JS since we can't rely on Tailwind peer across siblings) ----
        document.querySelectorAll('.toggle-input').forEach(input => {
            const track = input.nextElementSibling;
            const thumb = track.querySelector('.toggle-thumb');

            const update = () => {
                if (input.checked) {
                    track.classList.add('bg-amber-500');
                    track.classList.remove('bg-stone-700');
                    thumb.style.transform = 'translateX(1rem)';
                } else {
                    track.classList.remove('bg-amber-500');
                    track.classList.add('bg-stone-700');
                    thumb.style.transform = 'translateX(0)';
                }
            };

            update();
            input.addEventListener('change', update);
        });

        // ---- Form submit: loading state ----
        function handleSubmit(e) {
            const btn = document.getElementById('submitBtn');
            const icon = document.getElementById('btnIcon');
            const text = document.getElementById('btnText');

            btn.disabled = true;

            icon.innerHTML = `
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5" stroke-dasharray="30 70" class="animate-spin-slow" style="transform-origin:center"/>
            `;
            icon.setAttribute('viewBox', '0 0 24 24');

            // Use a spinner SVG
            icon.innerHTML = '';
            const spinner = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            spinner.setAttribute('class', 'w-4 h-4 animate-spin');
            spinner.setAttribute('viewBox', '0 0 24 24');
            spinner.setAttribute('fill', 'none');
            spinner.innerHTML = `
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5" stroke-opacity="0.25"/>
                <path d="M22 12a10 10 0 01-10 10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
            `;

            btn.innerHTML = '';
            btn.appendChild(spinner);
            const label = document.createElement('span');
            label.textContent = 'Optimizing…';
            btn.appendChild(label);
        }

        // ---- Copy to clipboard ----
        function copyPrompt() {
            const text = document.getElementById('optimizedPromptText')?.innerText;
            if (!text) return;

            navigator.clipboard.writeText(text).then(() => {
                const label = document.getElementById('copyLabel');
                if (label) {
                    label.textContent = 'Copied!';
                    setTimeout(() => label.textContent = 'Copy', 2000);
                }
            });
        }

        // ---- Smooth scroll offset for fixed header ----
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    const offset = 72;
                    window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - offset, behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>
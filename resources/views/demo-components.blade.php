<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tailwind CSS UI Component Library & Accordion Reference</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Styles / Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-800 antialiased selection:bg-indigo-500 selection:text-white" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    <!-- Top Sticky Header -->
    <header class="sticky top-0 z-40 w-full border-b border-slate-200 bg-white/90 backdrop-blur-md shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-sm font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-base font-bold text-slate-900">Tailwind CSS Component Kit</span>
                        <span class="block text-[11px] font-medium text-slate-500">Pure Utility Accordion Reference</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="document.querySelectorAll('details').forEach(d => d.open = true)" class="px-2.5 py-1 text-xs font-semibold rounded-md bg-slate-100 text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                        Expand All
                    </button>
                    <button type="button" onclick="document.querySelectorAll('details').forEach(d => d.open = false)" class="px-2.5 py-1 text-xs font-semibold rounded-md bg-slate-100 text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                        Collapse All
                    </button>
                    <span class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Zero Custom CSS
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Layout Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Intro Hero -->
        <div class="mb-8 bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Core UI Components & Pattern Library</h1>
            <p class="mt-2 text-sm text-slate-600 max-w-3xl leading-relaxed">
                A clean reference guide of generic, reusable building blocks for developers to copy-paste. Click on each accordion section below to expand and view components.
            </p>
        </div>

        <!-- ACCORDIONS LIST -->
        <div class="space-y-4">

            <!-- ACCORDION 1: TYPOGRAPHY -->
            <details class="group bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden" open>
                <summary class="flex items-center justify-between p-5 cursor-pointer select-none bg-white hover:bg-slate-50 transition border-b border-transparent group-open:border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">1</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Typography & Hierarchy</h3>
                            <p class="text-xs text-slate-500">Headings (H1–H4), body text, inline codes, muted text, and link states.</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-6 space-y-4 bg-slate-50/50">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">H1 Display Heading (text-3xl font-extrabold)</h1>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">H2 Page Title (text-2xl font-bold)</h2>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-slate-800">H3 Section Header (text-xl font-semibold)</h3>
                    </div>
                    <div>
                        <h4 class="text-base font-semibold text-slate-800">H4 Subtitle / Card Title (text-base font-semibold)</h4>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed max-w-4xl">
                        Standard body text paragraph (<code class="text-xs bg-slate-200/80 text-slate-800 px-1 py-0.5 rounded font-mono">text-sm text-slate-600</code>). Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                        <a href="#" class="font-medium text-indigo-600 underline hover:text-indigo-700">Inline link style</a>.
                    </p>
                    <p class="text-xs text-slate-400">
                        Caption / muted helper text (<code class="text-[11px] bg-slate-200/80 px-1 py-0.5 rounded font-mono">text-xs text-slate-400</code>)
                    </p>
                </div>
            </details>

            <!-- ACCORDION 2: BUTTONS -->
            <details class="group bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden" open>
                <summary class="flex items-center justify-between p-5 cursor-pointer select-none bg-white hover:bg-slate-50 transition border-b border-transparent group-open:border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">2</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Buttons & Action Controls</h3>
                            <p class="text-xs text-slate-500">Color themes, sizes (xs to lg), icons, loading spinner, and button groups.</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-6 space-y-6 bg-slate-50/50">
                    <!-- Standard Variants -->
                    <div>
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Color Variants</h4>
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" class="px-4 py-2 text-xs font-semibold rounded-lg bg-indigo-600 text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition cursor-pointer">
                                Primary Indigo
                            </button>
                            <button type="button" class="px-4 py-2 text-xs font-semibold rounded-lg bg-slate-900 text-white shadow-sm hover:bg-slate-800 focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 transition cursor-pointer">
                                Dark Slate
                            </button>
                            <button type="button" class="px-4 py-2 text-xs font-semibold rounded-lg bg-emerald-600 text-white shadow-sm hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition cursor-pointer">
                                Success Green
                            </button>
                            <button type="button" class="px-4 py-2 text-xs font-semibold rounded-lg bg-amber-500 text-white shadow-sm hover:bg-amber-600 focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition cursor-pointer">
                                Warning Amber
                            </button>
                            <button type="button" class="px-4 py-2 text-xs font-semibold rounded-lg bg-rose-600 text-white shadow-sm hover:bg-rose-700 focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 transition cursor-pointer">
                                Danger Red
                            </button>
                            <button type="button" class="px-4 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-300 text-slate-700 shadow-sm hover:bg-slate-50 focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition cursor-pointer">
                                Secondary Outline
                            </button>
                            <button type="button" class="px-4 py-2 text-xs font-semibold rounded-lg text-slate-600 hover:bg-slate-200/60 transition cursor-pointer">
                                Ghost Button
                            </button>
                        </div>
                    </div>

                    <!-- Sizes -->
                    <div class="pt-4 border-t border-slate-200">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Sizes</h4>
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" class="px-2.5 py-1 text-[11px] font-semibold rounded-md bg-indigo-600 text-white hover:bg-indigo-700 cursor-pointer">Small (xs)</button>
                            <button type="button" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 cursor-pointer">Medium (default)</button>
                            <button type="button" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 cursor-pointer">Large (base)</button>
                            <button type="button" class="px-6 py-3 text-base font-semibold rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 cursor-pointer">Extra Large (lg)</button>
                        </div>
                    </div>

                    <!-- With Icons, States & Groups -->
                    <div class="pt-4 border-t border-slate-200">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Icons, Loading, Disabled & Groups</h4>
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg bg-indigo-600 text-white shadow-sm hover:bg-indigo-700 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Add Item
                            </button>

                            <button type="button" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 cursor-pointer">
                                Next Page
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>

                            <button type="button" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg bg-indigo-500 text-white opacity-80 cursor-wait">
                                <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                Loading...
                            </button>

                            <button type="button" disabled class="px-4 py-2 text-xs font-semibold rounded-lg bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed">
                                Disabled State
                            </button>

                            <div class="inline-flex rounded-lg shadow-sm border border-slate-300">
                                <button type="button" class="px-3 py-2 text-xs font-semibold rounded-l-lg bg-white text-slate-700 hover:bg-slate-50 border-r border-slate-300 cursor-pointer">Years</button>
                                <button type="button" class="px-3 py-2 text-xs font-semibold bg-indigo-50 text-indigo-700 border-r border-slate-300 cursor-pointer">Months</button>
                                <button type="button" class="px-3 py-2 text-xs font-semibold rounded-r-lg bg-white text-slate-700 hover:bg-slate-50 cursor-pointer">Days</button>
                            </div>
                        </div>
                    </div>
                </div>
            </details>

            <!-- ACCORDION 3: BADGES & TAGS -->
            <details class="group bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden" open>
                <summary class="flex items-center justify-between p-5 cursor-pointer select-none bg-white hover:bg-slate-50 transition border-b border-transparent group-open:border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">3</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Badges, Tags & Indicators</h3>
                            <p class="text-xs text-slate-500">Pills, dot indicators, status chips, and dismissible tags.</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-6 space-y-4 bg-slate-50/50">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">Slate / Neutral</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">Indigo</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">Blue Info</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Success</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">Warning</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">Danger / Error</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">Purple</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-200">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active / Online
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending Review
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Inactive / Locked
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-200">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-slate-200/80 text-slate-700">
                            Tag Item
                            <button type="button" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                            Removable Filter
                            <button type="button" class="text-indigo-400 hover:text-indigo-600 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </span>
                    </div>
                </div>
            </details>

            <!-- ACCORDION 4: FORM INPUTS -->
            <details class="group bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden" open>
                <summary class="flex items-center justify-between p-5 cursor-pointer select-none bg-white hover:bg-slate-50 transition border-b border-transparent group-open:border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">4</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Form Inputs & Controls</h3>
                            <p class="text-xs text-slate-500">Text inputs, selects, currency inputs, validation error states, switches, and checkboxes.</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-6 bg-slate-50/50">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Standard Input -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700">Text Input Label</label>
                            <input type="text" placeholder="Enter full name" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <p class="text-[11px] text-slate-400">Helper description text goes here.</p>
                        </div>

                        <!-- Input with Prefix Icon -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700">Email Address (Icon Prefix)</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                                </div>
                                <input type="email" placeholder="user@domain.com" class="w-full rounded-lg border border-slate-300 bg-white pl-9 pr-3 py-2 text-xs text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Currency / Number Input -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700">Currency Amount</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-xs font-bold text-slate-400">USD / MYR</span>
                                </div>
                                <input type="number" step="0.01" value="1250.00" class="w-full rounded-lg border border-slate-300 bg-white pl-20 pr-3 py-2 text-xs font-mono text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Select Dropdown -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700">Select Option</label>
                            <select class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option>Option One - Default Selection</option>
                                <option>Option Two</option>
                                <option>Option Three</option>
                            </select>
                        </div>

                        <!-- Validation Error Input -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-rose-700">Invalid Input State</label>
                            <input type="text" value="invalid_data" class="w-full rounded-lg border border-rose-300 bg-rose-50/40 px-3 py-2 text-xs text-rose-900 shadow-sm focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                            <p class="text-[11px] text-rose-600 font-medium">This field is required or formatted incorrectly.</p>
                        </div>

                        <!-- Disabled Input -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-400">Disabled Input Field</label>
                            <input type="text" disabled value="Read only fixed content" class="w-full rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 text-xs text-slate-400 cursor-not-allowed">
                        </div>

                        <!-- Checkboxes -->
                        <div class="space-y-2 p-3 bg-white rounded-lg border border-slate-200 shadow-xs">
                            <span class="block text-xs font-semibold text-slate-700">Checkboxes</span>
                            <div class="space-y-1.5">
                                <label class="inline-flex items-center text-xs text-slate-700 cursor-pointer">
                                    <input type="checkbox" checked class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2">Selected option</span>
                                </label>
                                <label class="inline-flex items-center text-xs text-slate-700 cursor-pointer">
                                    <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2">Unselected option</span>
                                </label>
                            </div>
                        </div>

                        <!-- Radio Group -->
                        <div class="space-y-2 p-3 bg-white rounded-lg border border-slate-200 shadow-xs">
                            <span class="block text-xs font-semibold text-slate-700">Radio Group</span>
                            <div class="space-y-1.5">
                                <label class="inline-flex items-center text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="demo_radio" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2">Radio choice A</span>
                                </label>
                                <label class="inline-flex items-center text-xs text-slate-700 cursor-pointer">
                                    <input type="radio" name="demo_radio" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2">Radio choice B</span>
                                </label>
                            </div>
                        </div>

                        <!-- Switch Toggle Demo -->
                        <div class="space-y-3 p-3 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-xs col-span-1 sm:col-span-2 lg:col-span-3">
                            <span class="block text-xs font-semibold text-slate-700 dark:text-slate-200">Global &lt;x-toggle&gt; Switches (Pure Tailwind CSS Peer Classes)</span>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                                <x-toggle id="demo_toggle_1" label="Standard Toggle" description="Default medium size" checked="true" color="indigo" />
                                <x-toggle id="demo_toggle_2" label="Success Emerald" description="Small compact size" size="sm" checked="true" color="emerald" />
                                <x-toggle id="demo_toggle_3" label="Rose Alert" description="Large prominent toggle" size="lg" checked="false" color="rose" />
                            </div>
                        </div>

                        <!-- Textarea -->
                        <div class="col-span-1 sm:col-span-2 lg:col-span-3 space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700">Textarea / Multiline Input</label>
                            <textarea rows="3" placeholder="Enter comments or multiline notes..." class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                </div>
            </details>

            <!-- ACCORDION 5: CARDS & STATS -->
            <details class="group bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <summary class="flex items-center justify-between p-5 cursor-pointer select-none bg-white hover:bg-slate-50 transition border-b border-transparent group-open:border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">5</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Cards & Metric KPI Stats</h3>
                            <p class="text-xs text-slate-500">Stat widgets with icons & trend labels, and structural content cards.</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-6 space-y-6 bg-slate-50/50">
                    <!-- Metric Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                            <div class="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase">
                                <span>Total Users</span>
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                            </div>
                            <div class="mt-2 text-2xl font-bold text-slate-900">8,420</div>
                            <div class="mt-1 text-xs text-emerald-600 font-medium flex items-center gap-1">
                                <span>↑ 12%</span>
                                <span class="text-slate-400 font-normal">from last month</span>
                            </div>
                        </div>

                        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                            <div class="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase">
                                <span>Active Projects</span>
                                <div class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center text-sky-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                            </div>
                            <div class="mt-2 text-2xl font-bold text-slate-900">42</div>
                            <div class="mt-1 text-xs text-slate-500 font-medium">8 completed this week</div>
                        </div>

                        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                            <div class="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase">
                                <span>Pending Tasks</span>
                                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <div class="mt-2 text-2xl font-bold text-slate-900">14</div>
                            <div class="mt-1 text-xs text-amber-600 font-medium">Requires approval</div>
                        </div>

                        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                            <div class="flex items-center justify-between text-slate-500 text-xs font-semibold uppercase">
                                <span>System Uptime</span>
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <div class="mt-2 text-2xl font-bold text-slate-900">99.98%</div>
                            <div class="mt-1 text-xs text-emerald-600 font-medium">All services operational</div>
                        </div>
                    </div>

                    <!-- Content Card with Header & Footer -->
                    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-white">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Content Card Container</h3>
                                <p class="text-xs text-slate-500">Card subtitle description.</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">Header Tag</span>
                        </div>
                        <div class="p-6 text-xs text-slate-600 space-y-2 leading-relaxed bg-white">
                            <p>This is the standard body container area for general views, nested components, charts, and lists.</p>
                        </div>
                        <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs">
                            <span class="text-slate-500">Footer status note</span>
                            <div class="flex gap-2">
                                <button type="button" class="px-3 py-1.5 rounded-md border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 font-medium cursor-pointer">Cancel</button>
                                <button type="button" class="px-3 py-1.5 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 font-medium cursor-pointer">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </details>

            <!-- ACCORDION 6: DATA TABLES & PAGINATION -->
            <details class="group bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <summary class="flex items-center justify-between p-5 cursor-pointer select-none bg-white hover:bg-slate-50 transition border-b border-transparent group-open:border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">6</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Data Tables & Pagination</h3>
                            <p class="text-xs text-slate-500">Responsive tables with column headers, row hovers, action links, and pagination.</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-6 bg-slate-50/50 space-y-4">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider">
                                    <tr>
                                        <th scope="col" class="py-3 px-4">User Name</th>
                                        <th scope="col" class="py-3 px-4">Role</th>
                                        <th scope="col" class="py-3 px-4">Status</th>
                                        <th scope="col" class="py-3 px-4">Joined Date</th>
                                        <th scope="col" class="py-3 px-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="py-3.5 px-4">
                                            <div class="font-bold text-slate-900">Alex Morgan</div>
                                            <div class="text-[11px] text-slate-400 font-mono">alex.morgan@domain.com</div>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-700">Administrator</td>
                                        <td class="py-3.5 px-4">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-500">Aug 15, 2026</td>
                                        <td class="py-3.5 px-4 text-right space-x-2">
                                            <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <a href="#" class="font-semibold text-rose-600 hover:text-rose-900">Delete</a>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="py-3.5 px-4">
                                            <div class="font-bold text-slate-900">Sarah Jenkins</div>
                                            <div class="text-[11px] text-slate-400 font-mono">sarah.j@domain.com</div>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-700">Staff Member</td>
                                        <td class="py-3.5 px-4">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-500">Aug 14, 2026</td>
                                        <td class="py-3.5 px-4 text-right space-x-2">
                                            <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <a href="#" class="font-semibold text-rose-600 hover:text-rose-900">Delete</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Footer -->
                        <div class="px-4 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500">
                            <span>Showing 1 to 2 of 24 entries</span>
                            <div class="flex items-center gap-1">
                                <button class="px-2.5 py-1 rounded border border-slate-300 bg-white hover:bg-slate-50 cursor-pointer">Prev</button>
                                <button class="px-2.5 py-1 rounded border border-indigo-600 bg-indigo-600 text-white font-semibold cursor-pointer">1</button>
                                <button class="px-2.5 py-1 rounded border border-slate-300 bg-white hover:bg-slate-50 cursor-pointer">2</button>
                                <button class="px-2.5 py-1 rounded border border-slate-300 bg-white hover:bg-slate-50 cursor-pointer">3</button>
                                <button class="px-2.5 py-1 rounded border border-slate-300 bg-white hover:bg-slate-50 cursor-pointer">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </details>

            <!-- ACCORDION 7: ALERTS & BANNERS -->
            <details class="group bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <summary class="flex items-center justify-between p-5 cursor-pointer select-none bg-white hover:bg-slate-50 transition border-b border-transparent group-open:border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">7</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Alerts, Toasts & Banners</h3>
                            <p class="text-xs text-slate-500">Feedback states for info, success, warning, and error notifications.</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-6 bg-slate-50/50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 gap-3">
                            <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div class="space-y-1">
                                <h4 class="text-xs font-bold uppercase tracking-wider">Information Message</h4>
                                <p class="text-xs text-blue-700 leading-relaxed">Here is some helpful context or system guidance.</p>
                            </div>
                        </div>

                        <div class="flex p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 gap-3">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <div class="space-y-1">
                                <h4 class="text-xs font-bold uppercase tracking-wider">Operation Completed</h4>
                                <p class="text-xs text-emerald-700 leading-relaxed">The record has been successfully saved to the database.</p>
                            </div>
                        </div>

                        <div class="flex p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 gap-3">
                            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div class="space-y-1">
                                <h4 class="text-xs font-bold uppercase tracking-wider">Warning Notice</h4>
                                <p class="text-xs text-amber-700 leading-relaxed">Please review your input before continuing.</p>
                            </div>
                        </div>

                        <div class="flex p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 gap-3">
                            <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div class="space-y-1">
                                <h4 class="text-xs font-bold uppercase tracking-wider">Error Occurred</h4>
                                <p class="text-xs text-rose-700 leading-relaxed">Failed to process request. Please try again later.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </details>

            <!-- ACCORDION 8: TABS & BREADCRUMBS -->
            <details class="group bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <summary class="flex items-center justify-between p-5 cursor-pointer select-none bg-white hover:bg-slate-50 transition border-b border-transparent group-open:border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">8</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Navigation, Tabs & Breadcrumbs</h3>
                            <p class="text-xs text-slate-500">Underline tabs, pill tabs, and hierarchical trail breadcrumbs.</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-6 space-y-6 bg-slate-50/50">
                    <div>
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Breadcrumbs</h4>
                        <nav class="flex items-center space-x-2 text-xs text-slate-500">
                            <a href="#" class="hover:text-slate-800">Home</a>
                            <span>/</span>
                            <a href="#" class="hover:text-slate-800">Settings</a>
                            <span>/</span>
                            <span class="font-semibold text-slate-800">Configuration</span>
                        </nav>
                    </div>

                    <div class="pt-4 border-t border-slate-200">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Underline Tabs</h4>
                        <div class="border-b border-slate-200">
                            <nav class="-mb-px flex space-x-6">
                                <a href="#" class="border-indigo-600 text-indigo-600 whitespace-nowrap py-3 px-1 border-b-2 font-semibold text-xs">Overview</a>
                                <a href="#" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-xs">Members</a>
                                <a href="#" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-xs">Security</a>
                            </nav>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Pill Tabs</h4>
                        <div class="flex space-x-2">
                            <button type="button" class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-semibold cursor-pointer">Active Pill</button>
                            <button type="button" class="px-3 py-1.5 rounded-lg text-slate-600 hover:bg-slate-200/60 text-xs font-medium cursor-pointer">Inactive Tab</button>
                            <button type="button" class="px-3 py-1.5 rounded-lg text-slate-600 hover:bg-slate-200/60 text-xs font-medium cursor-pointer">Reports</button>
                        </div>
                    </div>
                </div>
            </details>

            <!-- ACCORDION 9: MODALS & SLIDEOVERS -->
            <details class="group bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden" open>
                <summary class="flex items-center justify-between p-5 cursor-pointer select-none bg-white hover:bg-slate-50 transition border-b border-transparent group-open:border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">9</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Modals, Dialogs & Slide-over Drawers</h3>
                            <p class="text-xs text-slate-500">Live interactive modal triggers, form dialogs, destructive alerts, centered dialogs, and slide-over side panels.</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-6 bg-slate-50/50 space-y-8">
                    
                    <!-- Live Triggers Bar -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Live Interactive Modal Triggers</h4>
                        <div class="flex flex-wrap gap-3">
                            <button type="button" onclick="document.getElementById('live-form-modal').classList.remove('hidden')" class="px-4 py-2 text-xs font-semibold rounded-lg bg-indigo-600 text-white shadow-sm hover:bg-indigo-700 transition cursor-pointer">
                                🚀 Open Form Dialog Modal
                            </button>
                            <button type="button" onclick="document.getElementById('live-danger-modal').classList.remove('hidden')" class="px-4 py-2 text-xs font-semibold rounded-lg bg-rose-600 text-white shadow-sm hover:bg-rose-700 transition cursor-pointer">
                                ⚠️ Open Destructive Confirmation
                            </button>
                            <button type="button" onclick="document.getElementById('live-slideover').classList.remove('hidden')" class="px-4 py-2 text-xs font-semibold rounded-lg bg-slate-900 text-white shadow-sm hover:bg-slate-800 transition cursor-pointer">
                                📑 Open Slide-over Drawer
                            </button>
                        </div>
                    </div>

                    <!-- Static Visual Layout Previews Grid -->
                    <div>
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Modal Layout Anatomy & Variants</h4>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                            <!-- Variant 1: Destructive Confirmation Alert Dialog -->
                            <div class="bg-white rounded-xl border border-slate-200 shadow-md p-6 space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="text-sm font-bold text-slate-900">Delete Account & Records</h3>
                                        <p class="text-xs text-slate-500 leading-relaxed">Are you sure you want to delete this account? All associated items and records will be permanently removed. This action cannot be undone.</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                                    <button type="button" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 cursor-pointer">Cancel</button>
                                    <button type="button" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-rose-600 text-white hover:bg-rose-700 cursor-pointer">Delete Record</button>
                                </div>
                            </div>

                            <!-- Variant 2: Success / Confirmation Modal -->
                            <div class="bg-white rounded-xl border border-slate-200 shadow-md p-6 space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="text-sm font-bold text-slate-900">Payment Processed Successfully</h3>
                                        <p class="text-xs text-slate-500 leading-relaxed">Your batch submission has been verified and sent to the processing queue. A receipt has been sent to your email.</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                                    <button type="button" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 cursor-pointer">View Receipt</button>
                                    <button type="button" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 cursor-pointer">Done</button>
                                </div>
                            </div>

                            <!-- Variant 3: Interactive Data Form Modal -->
                            <div class="bg-white rounded-xl border border-slate-200 shadow-md overflow-hidden lg:col-span-2">
                                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-900">Edit User Permissions</h3>
                                        <p class="text-xs text-slate-500">Configure access levels and team roles.</p>
                                    </div>
                                    <button type="button" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-slate-700">Display Name</label>
                                        <input type="text" value="Jane Doe" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-slate-700">Assigned Role</label>
                                        <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                            <option>Editor & Manager</option>
                                            <option>Administrator</option>
                                            <option>Read-Only Auditor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2">
                                    <button type="button" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 cursor-pointer">Cancel</button>
                                    <button type="button" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 cursor-pointer">Save Changes</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </details>

            <!-- LIVE MODAL 1: FORM DIALOG (OVERLAY) -->
            <div id="live-form-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="document.getElementById('live-form-modal').classList.add('hidden')"></div>

                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900" id="modal-title">Create New Record</h3>
                                    <p class="text-xs text-slate-500">Enter general information below.</p>
                                </div>
                            </div>
                            <button type="button" onclick="document.getElementById('live-form-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-slate-700">Project / Record Title</label>
                                <input type="text" placeholder="e.g. Q4 Financial Audit" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-slate-700">Description</label>
                                <textarea rows="3" placeholder="Enter brief overview..." class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-200 flex items-center justify-end gap-2">
                            <button type="button" onclick="document.getElementById('live-form-modal').classList.add('hidden')" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 cursor-pointer">Cancel</button>
                            <button type="button" onclick="document.getElementById('live-form-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 cursor-pointer">Save Record</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LIVE MODAL 2: DESTRUCTIVE CONFIRMATION -->
            <div id="live-danger-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="document.getElementById('live-danger-modal').classList.add('hidden')"></div>
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200 p-6 space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-sm font-bold text-slate-900">Are you absolutely sure?</h3>
                                <p class="text-xs text-slate-500 leading-relaxed">This action cannot be undone. This will permanently delete the selected entries and revoke all active sessions.</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                            <button type="button" onclick="document.getElementById('live-danger-modal').classList.add('hidden')" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 cursor-pointer">Cancel</button>
                            <button type="button" onclick="document.getElementById('live-danger-modal').classList.add('hidden')" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-rose-600 text-white hover:bg-rose-700 cursor-pointer">Confirm & Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LIVE MODAL 3: SLIDE-OVER DRAWER -->
            <div id="live-slideover" class="hidden fixed inset-0 z-50 overflow-hidden">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="document.getElementById('live-slideover').classList.add('hidden')"></div>
                <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                    <div class="w-screen max-w-md bg-white shadow-2xl border-l border-slate-200 flex flex-col">
                        <!-- Drawer Header -->
                        <div class="p-6 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Slide-over Side Panel</h3>
                                <p class="text-xs text-slate-500">Quick details and contextual configuration.</p>
                            </div>
                            <button type="button" onclick="document.getElementById('live-slideover').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <!-- Drawer Body -->
                        <div class="flex-1 p-6 space-y-4 overflow-y-auto text-xs text-slate-600 leading-relaxed">
                            <div class="p-4 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-900">
                                <span class="font-bold block mb-1">Slide-over Drawer Anatomy</span>
                                Great for detail inspectors, activity logs, notifications center, or supplementary multi-step forms.
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-700">Configuration Tag</label>
                                <input type="text" value="Production Release v2.4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>
                        <!-- Drawer Footer -->
                        <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2">
                            <button type="button" onclick="document.getElementById('live-slideover').classList.add('hidden')" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 cursor-pointer">Close Drawer</button>
                            <button type="button" onclick="document.getElementById('live-slideover').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold rounded-lg bg-slate-900 text-white hover:bg-slate-800 cursor-pointer">Apply</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACCORDION 10: AVATARS -->
            <details class="group bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <summary class="flex items-center justify-between p-5 cursor-pointer select-none bg-white hover:bg-slate-50 transition border-b border-transparent group-open:border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">10</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Avatars & User Stacks</h3>
                            <p class="text-xs text-slate-500">Avatar sizes (XS to LG), active badge rings, and stacked team lists.</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-6 bg-slate-50/50">
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="w-6 h-6 rounded-full bg-indigo-600 text-white text-[10px] font-bold flex items-center justify-center">XS</div>
                        <div class="w-8 h-8 rounded-full bg-sky-600 text-white text-xs font-bold flex items-center justify-center">SM</div>
                        <div class="w-10 h-10 rounded-full bg-emerald-600 text-white text-sm font-bold flex items-center justify-center">MD</div>
                        <div class="relative">
                            <div class="w-12 h-12 rounded-full bg-purple-600 text-white text-base font-bold flex items-center justify-center">LG</div>
                            <span class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                        </div>

                        <div class="flex -space-x-2 overflow-hidden ml-4">
                            <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-indigo-500 text-white text-xs font-bold flex items-center justify-center">A</div>
                            <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-pink-500 text-white text-xs font-bold flex items-center justify-center">B</div>
                            <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-amber-500 text-white text-xs font-bold flex items-center justify-center">C</div>
                            <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-slate-200 text-slate-600 text-xs font-bold flex items-center justify-center">+4</div>
                        </div>
                    </div>
                </div>
            </details>

            <!-- ACCORDION 11: ANIMATE.CSS SHOWCASE & INTERACTIVE PLAYGROUND -->
            <details class="group bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden" open>
                <summary class="flex items-center justify-between p-5 cursor-pointer select-none bg-white hover:bg-slate-50 transition border-b border-transparent group-open:border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">11</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Animate.css Animation Effects & Playground</h3>
                            <p class="text-xs text-slate-500">Integrated Animate.css library with live animation trigger buttons, attention seekers, entrances, and exits.</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-6 bg-slate-50/50 space-y-6">
                    
                    <!-- Interactive Animation Tester Box -->
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="space-y-1 text-center sm:text-left">
                            <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Live Animation Sandbox</h4>
                            <p class="text-xs text-slate-500">Click any animation button on the right to trigger the effect on this preview target.</p>
                        </div>

                        <!-- Target Box -->
                        <div id="animation-target" class="w-48 h-20 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 text-white font-bold text-xs flex flex-col items-center justify-center shadow-lg shadow-indigo-500/20">
                            <span>Target Element</span>
                            <span id="current-anim-name" class="text-[10px] font-normal text-indigo-200 font-mono mt-0.5">animate__animated</span>
                        </div>
                    </div>

                    <!-- Animation Triggers Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Group 1: Attention Seekers -->
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs space-y-3">
                            <span class="text-xs font-bold text-slate-700 block border-b border-slate-100 pb-1.5">Attention Seekers</span>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" onclick="triggerAnimation('animate__bounce')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Bounce</button>
                                <button type="button" onclick="triggerAnimation('animate__flash')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Flash</button>
                                <button type="button" onclick="triggerAnimation('animate__pulse')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Pulse</button>
                                <button type="button" onclick="triggerAnimation('animate__rubberBand')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">RubberBand</button>
                                <button type="button" onclick="triggerAnimation('animate__shakeX')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Shake X</button>
                                <button type="button" onclick="triggerAnimation('animate__shakeY')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Shake Y</button>
                                <button type="button" onclick="triggerAnimation('animate__headShake')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">HeadShake</button>
                                <button type="button" onclick="triggerAnimation('animate__tada')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Tada 🎉</button>
                                <button type="button" onclick="triggerAnimation('animate__wobble')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Wobble</button>
                                <button type="button" onclick="triggerAnimation('animate__heartBeat')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">HeartBeat 💓</button>
                            </div>
                        </div>

                        <!-- Group 2: Entrances & Fades -->
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs space-y-3">
                            <span class="text-xs font-bold text-slate-700 block border-b border-slate-100 pb-1.5">Entrances & Fades</span>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" onclick="triggerAnimation('animate__fadeIn')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Fade In</button>
                                <button type="button" onclick="triggerAnimation('animate__fadeInDown')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Fade In Down</button>
                                <button type="button" onclick="triggerAnimation('animate__fadeInUp')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Fade In Up</button>
                                <button type="button" onclick="triggerAnimation('animate__fadeInLeft')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Fade In Left</button>
                                <button type="button" onclick="triggerAnimation('animate__fadeInRight')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Fade In Right</button>
                                <button type="button" onclick="triggerAnimation('animate__bounceIn')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Bounce In</button>
                                <button type="button" onclick="triggerAnimation('animate__zoomIn')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Zoom In</button>
                                <button type="button" onclick="triggerAnimation('animate__jackInTheBox')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">JackInTheBox</button>
                            </div>
                        </div>

                        <!-- Group 3: Flips & Rotates -->
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs space-y-3">
                            <span class="text-xs font-bold text-slate-700 block border-b border-slate-100 pb-1.5">Flips & Rotations</span>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" onclick="triggerAnimation('animate__flip')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Flip</button>
                                <button type="button" onclick="triggerAnimation('animate__flipInX')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Flip In X</button>
                                <button type="button" onclick="triggerAnimation('animate__flipInY')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Flip In Y</button>
                                <button type="button" onclick="triggerAnimation('animate__rotateIn')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Rotate In</button>
                                <button type="button" onclick="triggerAnimation('animate__lightSpeedInRight')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">LightSpeed In</button>
                                <button type="button" onclick="triggerAnimation('animate__rollIn')" class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 transition cursor-pointer">Roll In</button>
                            </div>
                        </div>
                    </div>
                </div>
            </details>

            <!-- ACCORDION 12: BOXICONS ICON SET REFERENCE -->
            <details class="group bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden" open>
                <summary class="flex items-center justify-between p-5 cursor-pointer select-none bg-white hover:bg-slate-50 transition border-b border-transparent group-open:border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">12</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Boxicons Icon Set & Utility Sizing</h3>
                            <p class="text-xs text-slate-500">Regular (<code class="font-mono">bx</code>), Solid (<code class="font-mono">bxs</code>), and Logo (<code class="font-mono">bxl</code>) icon collections with Tailwind font size & color utilities.</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-6 bg-slate-50/50 space-y-6">
                    
                    <!-- Icon Sizes & Color Demonstrator -->
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs space-y-3">
                        <span class="text-xs font-bold text-slate-700 block">Sizing & Color Pairing with Tailwind CSS</span>
                        <div class="flex flex-wrap items-center gap-6">
                            <div class="flex items-center gap-2">
                                <i class='bx bx-user text-xs text-slate-500'></i>
                                <span class="text-xs text-slate-500">text-xs</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class='bx bx-bell text-sm text-indigo-600'></i>
                                <span class="text-xs text-slate-500">text-sm text-indigo-600</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class='bx bx-check-circle text-base text-emerald-600'></i>
                                <span class="text-xs text-slate-500">text-base text-emerald-600</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class='bx bxs-zap text-xl text-amber-500'></i>
                                <span class="text-xs text-slate-500">text-xl text-amber-500</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class='bx bx-shield-quarter text-2xl text-rose-600'></i>
                                <span class="text-xs text-slate-500">text-2xl text-rose-600</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class='bx bxs-wallet text-3xl text-purple-600'></i>
                                <span class="text-xs text-slate-500">text-3xl text-purple-600</span>
                            </div>
                        </div>
                    </div>

                    <!-- Icon Grid Collection -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                        <!-- Icons -->
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bx-home text-xl text-slate-700'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bx-home</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bxs-user-detail text-xl text-indigo-600'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bxs-user-detail</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bx-wallet text-xl text-emerald-600'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bx-wallet</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bxs-file-pdf text-xl text-rose-600'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bxs-file-pdf</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bx-spreadsheet text-xl text-emerald-700'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bx-spreadsheet</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bx-building-house text-xl text-slate-700'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bx-building</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bx-cog text-xl text-slate-600'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bx-cog</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bx-lock-alt text-xl text-amber-600'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bx-lock-alt</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bx-search text-xl text-slate-600'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bx-search</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bx-download text-xl text-indigo-600'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bx-download</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bx-bell text-xl text-amber-500'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bx-bell</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bx-trash text-xl text-rose-600'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bx-trash</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bx-calendar text-xl text-sky-600'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bx-calendar</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bx-credit-card text-xl text-indigo-700'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bx-credit-card</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bx-badge-check text-xl text-emerald-600'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bx-badge-check</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-1.5 hover:border-indigo-300 hover:shadow-xs transition">
                            <i class='bx bx-time-five text-xl text-slate-500'></i>
                            <span class="text-[10px] text-slate-500 font-mono">bx-time-five</span>
                        </div>
                    </div>

                    <!-- Button Integration Examples -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs space-y-2">
                        <span class="text-xs font-bold text-slate-700 block">Boxicons inside Tailwind Action Buttons</span>
                        <div class="flex flex-wrap gap-3">
                            <button type="button" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg bg-indigo-600 text-white shadow-sm hover:bg-indigo-700 transition cursor-pointer">
                                <i class='bx bx-plus text-sm'></i> Add New Employee
                            </button>
                            <button type="button" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg bg-emerald-600 text-white shadow-sm hover:bg-emerald-700 transition cursor-pointer">
                                <i class='bx bx-export text-sm'></i> Export Statutory Files
                            </button>
                            <button type="button" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                                <i class='bx bx-printer text-sm text-slate-500'></i> Print Form EA
                            </button>
                        </div>
                    </div>

            <!-- ACCORDION 13: TOGGLE SWITCHES & SLIDERS -->
            <details class="group bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden" open>
                <summary class="flex items-center justify-between p-5 cursor-pointer select-none bg-white hover:bg-slate-50 transition border-b border-transparent group-open:border-slate-200">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">13</span>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">Toggle Switches & Theme Sliders</h3>
                            <p class="text-xs text-slate-500">Pure Tailwind CSS peer-based sliding switches with size variants, colors, active states, and dark/light toggles.</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="p-6 space-y-6 bg-slate-50/50">
                    
                    <!-- 1. Size Scale Grid -->
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">1. Size Variants (sm, md, lg)</span>
                            <span class="text-[11px] text-slate-400 font-mono">&lt;x-toggle size="..." /&gt;</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-center pt-1">
                            <x-toggle id="ui_toggle_sm" label="Small Switch" description="size='sm' (compact tables)" size="sm" checked="true" color="indigo" />
                            <x-toggle id="ui_toggle_md" label="Medium Switch" description="size='md' (default forms)" size="md" checked="true" color="indigo" />
                            <x-toggle id="ui_toggle_lg" label="Large Switch" description="size='lg' (settings hero)" size="lg" checked="true" color="indigo" />
                        </div>
                    </div>

                    <!-- 2. Color Themes Grid -->
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">2. Color Theme Palettes</span>
                            <span class="text-[11px] text-slate-400 font-mono">color="indigo|emerald|sky|purple|amber|rose"</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 pt-1">
                            <x-toggle id="ui_toggle_c1" label="Indigo Brand" description="Statutory standard" checked="true" color="indigo" />
                            <x-toggle id="ui_toggle_c2" label="Emerald Success" description="Active / approved" checked="true" color="emerald" />
                            <x-toggle id="ui_toggle_c3" label="Sky Blue Info" description="Sync bank autopay" checked="true" color="sky" />
                            <x-toggle id="ui_toggle_c4" label="Purple Vibrant" description="Audit log locks" checked="true" color="purple" />
                            <x-toggle id="ui_toggle_c5" label="Amber Warning" description="Pending supervisor check" checked="true" color="amber" />
                            <x-toggle id="ui_toggle_c6" label="Rose Destructive" description="Disable automatic filing" checked="true" color="rose" />
                        </div>
                    </div>

                    <!-- 3. States & Theme Toggle Pill -->
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">3. Special States & Dark / Light Theme Pill</span>
                            <span class="text-[11px] text-slate-400 font-mono">&lt;x-theme-toggle /&gt;</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-center pt-1">
                            <x-toggle id="ui_toggle_off" label="Unchecked State" description="Default false state" checked="false" color="indigo" />
                            <x-toggle id="ui_toggle_dis" label="Disabled State" description="Locked configuration" disabled="true" checked="true" color="indigo" />
                            
                            <!-- Theme Toggle Pill Demonstration -->
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200">
                                <div>
                                    <span class="text-xs font-semibold text-slate-800 block">Dark / Light Slider</span>
                                    <span class="text-[10px] text-slate-400">Animated Sun &amp; Moon pill</span>
                                </div>
                                <x-theme-toggle id="demo-inline-theme-toggle" />
                            </div>
                        </div>
                    </div>

                </div>
            </details>

            <!-- Javascript Helper for Animate.css Sandbox -->
            <script>
                function triggerAnimation(animationName) {
                    const el = document.getElementById('animation-target');
                    const label = document.getElementById('current-anim-name');
                    
                    // Remove existing animate classes
                    el.className = 'w-48 h-20 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 text-white font-bold text-xs flex flex-col items-center justify-center shadow-lg shadow-indigo-500/20';
                    
                    // Trigger reflow
                    void el.offsetWidth;
                    
                    // Apply animate.css class
                    el.className += ' animate__animated ' + animationName;
                    label.textContent = animationName;
                }
            </script>

        </div>
    </main>

    <!-- Sticky Bottom Footer -->
    <footer class="shrink-0 mt-auto border-t border-slate-200 bg-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
            <span>Tailwind CSS UI Component Kit Reference • Accordion Layout</span>
            <span class="font-medium text-slate-600">Zero Custom CSS</span>
        </div>
    </footer>
</body>
</html>

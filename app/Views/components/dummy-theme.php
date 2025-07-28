<!-- <div class="w-full hide-scrollbar flex flex-col gap-4 overflow-auto text-onSurface dark:text-onSurface"> -->
<div class="relative flex flex-col w-full" x-data="{ showThemes: false }">
  <label for="titleFontFamilySelect"
    class="mb-1 flex items-center pl-0.5 text-sm text-onSurface dark:text-onSurfaceDark">
    Theme
  </label>
  <button
    class="hide-scrollbar flex cursor-pointer items-center justify-between border-outline bg-surfaceAlt py-2 pl-2 pr-4 dark:border-outlineDark dark:bg-surfaceDarkAlt rounded-xl border"
    x-on:click="showThemes=!showThemes" x-bind:class="[
            'rounded', 
        ]">
    <span class="text-sm capitalize text-onSurface dark:text-onSurfaceDarkStrong" x-text="
                [
                    theme == 'arctic'
                        ? 'Arctic'
                        : '' || theme == 'high-contrast'
                          ? 'High Contrast'
                          : '' || theme == 'minimal'
                            ? 'Minimal'
                            : '' || theme == 'halloween'
                              ? 'Halloween'
                              : '' || theme == 'halloween2'
                                ? 'Halloween II'
                                : '' || theme == 'punk'
                                  ? 'Neo Brutalism'
                                  : '' || theme == 'modern'
                                    ? 'Modern'
                                    : '' || theme == 'pastel'
                                      ? 'Pastel'
                                      : '' || theme == '90s'
                                        ? '90s'
                                        : '' || theme == 'prototype'
                                          ? 'Prototype'
                                          : '' || theme == 'christmas'
                                            ? 'Christmas'
                                            : '' || theme == 'news'
                                              ? 'News'
                                              : '' || theme == 'industrial'
                                                ? 'Industrial'
                                                : '',
                ]
            ">Minimal</span>

    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
      class="absolute right-4 top-9 h-4 w-4 text-onSurface dark:text-onSurfaceDark" viewBox="0 0 16 16">
      <path fill-rule="evenodd"
        d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z">
      </path>
    </svg>
  </button>
  <div
    class="hide-scrollbar absolute top-16 z-10 flex h-72 w-full flex-col gap-1 overflow-auto border-outline bg-surfaceAlt p-1 text-sm shadow-[0_3px_10px_rgb(0,0,0,0.1)] dark:border-outlineDark dark:bg-surfaceDarkAlt rounded-xl border-none"
    x-show="showThemes" x-transition="" @click.outside="showThemes = false" :class="[
            'rounded', 
        ]" style="display: none;">
    <button aria-label="Arctic" :aria-pressed="theme === 'arctic'"
      class="relative flex w-full cursor-pointer items-center justify-between gap-4 border border-outline bg-surface/50 p-2 transition hover:bg-surfaceAlt/50 dark:border-outlineDark dark:bg-surfaceDark/50 dark:hover:bg-surfaceDarkAlt/50 rounded-xl"
      :class="[
                    'rounded'
                ]" x-on:click="setTheme('arctic')" aria-pressed="false">
      <div class="flex items-center gap-2" aria-hidden="true">

        <div x-show="theme !== 'arctic'"
          class="size-5 rounded-full border border-outline bg-transparent transition-all duration-500 ease-in-out dark:border-outlineDark">
        </div>

        <svg x-show="theme === 'arctic'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
          class="h-5 w-5 text-onSurface transition-all duration-500 ease-in-out dark:text-onSurfaceDark"
          style="display: none;">
          <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="text-left text-xs font-semibold capitalize text-onSurface dark:text-onSurfaceDark">
          Arctic
        </span>
      </div>
      <div class="bg-slate-100 dark:bg-slate-800 font-inter rounded-lg flex h-full items-center gap-1 p-2">
        <div aria-hidden="true"
          class="bg-blue-700 text-slate-100 dark:bg-blue-600 dark:text-slate-100 rounded-lg flex items-center p-1.5">
        </div>
        <div aria-hidden="true"
          class="bg-indigo-700 text-slate-100 dark:bg-indigo-600 dark:text-slate-100 rounded-lg flex items-center p-1.5">
        </div>
      </div>
    </button>
    <button aria-label="High Contrast" :aria-pressed="theme === 'high-contrast'"
      class="relative flex w-full cursor-pointer items-center justify-between gap-4 border border-outline bg-surface/50 p-2 transition hover:bg-surfaceAlt/50 dark:border-outlineDark dark:bg-surfaceDark/50 dark:hover:bg-surfaceDarkAlt/50 rounded-xl"
      :class="[
                    'rounded'
                ]" x-on:click="setTheme('high-contrast')" aria-pressed="false">
      <div class="flex items-center gap-2" aria-hidden="true">

        <div x-show="theme !== 'high-contrast'"
          class="size-5 rounded-full border border-outline bg-transparent transition-all duration-500 ease-in-out dark:border-outlineDark">
        </div>

        <svg x-show="theme === 'high-contrast'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
          fill="currentColor"
          class="h-5 w-5 text-onSurface transition-all duration-500 ease-in-out dark:text-onSurfaceDark"
          style="display: none;">
          <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="text-left text-xs font-semibold capitalize text-onSurface dark:text-onSurfaceDark">
          High Contrast
        </span>
      </div>
      <div class="bg-gray-200 dark:bg-gray-800 font-inter rounded-sm flex h-full items-center gap-1 p-2">
        <div aria-hidden="true"
          class="bg-sky-900 text-white dark:bg-sky-400 dark:text-black rounded-sm flex items-center p-1.5"></div>
        <div aria-hidden="true"
          class="bg-indigo-900 text-white dark:bg-indigo-400 dark:text-black rounded-sm flex items-center p-1.5">
        </div>
      </div>
    </button>
    <button aria-label="Minimal" :aria-pressed="theme === 'minimal'"
      class="relative flex w-full cursor-pointer items-center justify-between gap-4 border border-outline bg-surface/50 p-2 transition hover:bg-surfaceAlt/50 dark:border-outlineDark dark:bg-surfaceDark/50 dark:hover:bg-surfaceDarkAlt/50 rounded-xl"
      :class="[
                    'rounded'
                ]" x-on:click="setTheme('minimal')" aria-pressed="true">
      <div class="flex items-center gap-2" aria-hidden="true">

        <div x-show="theme !== 'minimal'"
          class="size-5 rounded-full border border-outline bg-transparent transition-all duration-500 ease-in-out dark:border-outlineDark"
          style="display: none;"></div>

        <svg x-show="theme === 'minimal'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
          class="h-5 w-5 text-onSurface transition-all duration-500 ease-in-out dark:text-onSurfaceDark">
          <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="text-left text-xs font-semibold capitalize text-onSurface dark:text-onSurfaceDark">
          Minimal
        </span>
      </div>
      <div class="bg-neutral-100 dark:bg-neutral-800 font-montserrat rounded-none flex h-full items-center gap-1 p-2">
        <div aria-hidden="true"
          class="bg-black text-neutral-100 dark:bg-white dark:text-black rounded-none flex items-center p-1.5"></div>
        <div aria-hidden="true"
          class="bg-neutral-800 text-white dark:bg-neutral-300 dark:text-black rounded-none flex items-center p-1.5">
        </div>
      </div>
    </button>
    <button aria-label="Modern" :aria-pressed="theme === 'modern'"
      class="relative flex w-full cursor-pointer items-center justify-between gap-4 border border-outline bg-surface/50 p-2 transition hover:bg-surfaceAlt/50 dark:border-outlineDark dark:bg-surfaceDark/50 dark:hover:bg-surfaceDarkAlt/50 rounded-xl"
      :class="[
                    'rounded'
                ]" x-on:click="setTheme('modern')" aria-pressed="false">
      <div class="flex items-center gap-2" aria-hidden="true">

        <div x-show="theme !== 'modern'"
          class="size-5 rounded-full border border-outline bg-transparent transition-all duration-500 ease-in-out dark:border-outlineDark">
        </div>

        <svg x-show="theme === 'modern'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
          class="h-5 w-5 text-onSurface transition-all duration-500 ease-in-out dark:text-onSurfaceDark"
          style="display: none;">
          <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="text-left text-xs font-semibold capitalize text-onSurface dark:text-onSurfaceDark">
          Modern
        </span>
      </div>
      <div class="bg-neutral-50 dark:bg-neutral-900 font-lato rounded-sm flex h-full items-center gap-1 p-2">
        <div aria-hidden="true"
          class="bg-black text-neutral-100 dark:bg-white dark:text-black rounded-sm flex items-center p-1.5"></div>
        <div aria-hidden="true"
          class="bg-neutral-800 text-white dark:bg-neutral-300 dark:text-black rounded-sm flex items-center p-1.5">
        </div>
      </div>
    </button>
    <button aria-label="Neo Brutalism" :aria-pressed="theme === 'punk'"
      class="relative flex w-full cursor-pointer items-center justify-between gap-4 border border-outline bg-surface/50 p-2 transition hover:bg-surfaceAlt/50 dark:border-outlineDark dark:bg-surfaceDark/50 dark:hover:bg-surfaceDarkAlt/50 rounded-xl"
      :class="[
                    'rounded'
                ]" x-on:click="setTheme('punk')" aria-pressed="false">
      <div class="flex items-center gap-2" aria-hidden="true">

        <div x-show="theme !== 'punk'"
          class="size-5 rounded-full border border-outline bg-transparent transition-all duration-500 ease-in-out dark:border-outlineDark">
        </div>

        <svg x-show="theme === 'punk'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
          class="h-5 w-5 text-onSurface transition-all duration-500 ease-in-out dark:text-onSurfaceDark"
          style="display: none;">
          <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="text-left text-xs font-semibold capitalize text-onSurface dark:text-onSurfaceDark">
          Neo Brutalism
        </span>
      </div>
      <div class="bg-neutral-50 dark:bg-neutral-800 font-space-mono rounded-none flex h-full items-center gap-1 p-2">
        <div aria-hidden="true"
          class="bg-violet-500 text-white dark:bg-violet-400 dark:text-black rounded-none flex items-center p-1.5">
        </div>
        <div aria-hidden="true"
          class="bg-lime-400 text-black dark:bg-lime-300 dark:text-black rounded-none flex items-center p-1.5"></div>
      </div>
    </button>
    <button aria-label="Halloween" :aria-pressed="theme === 'halloween'"
      class="relative flex w-full cursor-pointer items-center justify-between gap-4 border border-outline bg-surface/50 p-2 transition hover:bg-surfaceAlt/50 dark:border-outlineDark dark:bg-surfaceDark/50 dark:hover:bg-surfaceDarkAlt/50 rounded-xl"
      :class="[
                    'rounded'
                ]" x-on:click="setTheme('halloween')" aria-pressed="false">
      <div class="flex items-center gap-2" aria-hidden="true">

        <div x-show="theme !== 'halloween'"
          class="size-5 rounded-full border border-outline bg-transparent transition-all duration-500 ease-in-out dark:border-outlineDark">
        </div>

        <svg x-show="theme === 'halloween'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
          class="h-5 w-5 text-onSurface transition-all duration-500 ease-in-out dark:text-onSurfaceDark"
          style="display: none;">
          <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="text-left text-xs font-semibold capitalize text-onSurface dark:text-onSurfaceDark">
          Halloween
        </span>
      </div>
      <div class="bg-gray-100 dark:bg-gray-900 font-poppins rounded-xl flex h-full items-center gap-1 p-2">
        <div aria-hidden="true"
          class="bg-orange-400 text-slate-100 dark:bg-lime-400 dark:text-black rounded-xl flex items-center p-1.5">
        </div>
        <div aria-hidden="true"
          class="bg-purple-600 text-slate-100 dark:bg-fuchsia-600 dark:text-white rounded-xl flex items-center p-1.5">
        </div>
      </div>
    </button>
    <button aria-label="Halloween II" :aria-pressed="theme === 'halloween2'"
      class="relative flex w-full cursor-pointer items-center justify-between gap-4 border border-outline bg-surface/50 p-2 transition hover:bg-surfaceAlt/50 dark:border-outlineDark dark:bg-surfaceDark/50 dark:hover:bg-surfaceDarkAlt/50 rounded-xl"
      :class="[
                    'rounded'
                ]" x-on:click="setTheme('halloween2')" aria-pressed="false">
      <div class="flex items-center gap-2" aria-hidden="true">

        <div x-show="theme !== 'halloween2'"
          class="size-5 rounded-full border border-outline bg-transparent transition-all duration-500 ease-in-out dark:border-outlineDark">
        </div>

        <svg x-show="theme === 'halloween2'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
          class="h-5 w-5 text-onSurface transition-all duration-500 ease-in-out dark:text-onSurfaceDark"
          style="display: none;">
          <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="text-left text-xs font-semibold capitalize text-onSurface dark:text-onSurfaceDark">
          Halloween II
        </span>
      </div>
      <div class="bg-violet-100 dark:bg-violet-950 font-montserrat rounded-xl flex h-full items-center gap-1 p-2">
        <div aria-hidden="true"
          class="bg-orange-400 text-slate-100 dark:bg-orange-600 dark:text-slate-100 rounded-xl flex items-center p-1.5">
        </div>
        <div aria-hidden="true"
          class="bg-purple-600 text-slate-100 dark:bg-lime-500 dark:text-black rounded-xl flex items-center p-1.5">
        </div>
      </div>
    </button>
    <button aria-label="Pastel" :aria-pressed="theme === 'pastel'"
      class="relative flex w-full cursor-pointer items-center justify-between gap-4 border border-outline bg-surface/50 p-2 transition hover:bg-surfaceAlt/50 dark:border-outlineDark dark:bg-surfaceDark/50 dark:hover:bg-surfaceDarkAlt/50 rounded-xl"
      :class="[
                    'rounded'
                ]" x-on:click="setTheme('pastel')" aria-pressed="false">
      <div class="flex items-center gap-2" aria-hidden="true">

        <div x-show="theme !== 'pastel'"
          class="size-5 rounded-full border border-outline bg-transparent transition-all duration-500 ease-in-out dark:border-outlineDark">
        </div>

        <svg x-show="theme === 'pastel'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
          class="h-5 w-5 text-onSurface transition-all duration-500 ease-in-out dark:text-onSurfaceDark"
          style="display: none;">
          <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="text-left text-xs font-semibold capitalize text-onSurface dark:text-onSurfaceDark">
          Pastel
        </span>
      </div>
      <div class="bg-amber-100 dark:bg-neutral-800 font-playpen-sans rounded-xl flex h-full items-center gap-1 p-2">
        <div aria-hidden="true"
          class="bg-rose-400 text-white dark:bg-rose-400 dark:text-white rounded-xl flex items-center p-1.5"></div>
        <div aria-hidden="true"
          class="bg-orange-200 text-neutral-800 dark:bg-orange-200 dark:text-neutral-800 rounded-xl flex items-center p-1.5">
        </div>
      </div>
    </button>
    <button aria-label="90s" :aria-pressed="theme === '90s'"
      class="relative flex w-full cursor-pointer items-center justify-between gap-4 border border-outline bg-surface/50 p-2 transition hover:bg-surfaceAlt/50 dark:border-outlineDark dark:bg-surfaceDark/50 dark:hover:bg-surfaceDarkAlt/50 rounded-xl"
      :class="[
                    'rounded'
                ]" x-on:click="setTheme('90s')" aria-pressed="false">
      <div class="flex items-center gap-2" aria-hidden="true">

        <div x-show="theme !== '90s'"
          class="size-5 rounded-full border border-outline bg-transparent transition-all duration-500 ease-in-out dark:border-outlineDark">
        </div>

        <svg x-show="theme === '90s'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
          class="h-5 w-5 text-onSurface transition-all duration-500 ease-in-out dark:text-onSurfaceDark"
          style="display: none;">
          <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="text-left text-xs font-semibold capitalize text-onSurface dark:text-onSurfaceDark">
          90s
        </span>
      </div>
      <div class="bg-neutral-200 dark:bg-neutral-900 font-poppins rounded-xl flex h-full items-center gap-1 p-2">
        <div aria-hidden="true"
          class="bg-purple-500 text-white dark:bg-purple-400 dark:text-black rounded-xl flex items-center p-1.5">
        </div>
        <div aria-hidden="true"
          class="bg-sky-500 text-white dark:bg-blue-400 dark:text-black rounded-xl flex items-center p-1.5"></div>
      </div>
    </button>
    <button aria-label="Christmas" :aria-pressed="theme === 'christmas'"
      class="relative flex w-full cursor-pointer items-center justify-between gap-4 border border-outline bg-surface/50 p-2 transition hover:bg-surfaceAlt/50 dark:border-outlineDark dark:bg-surfaceDark/50 dark:hover:bg-surfaceDarkAlt/50 rounded-xl"
      :class="[
                    'rounded'
                ]" x-on:click="setTheme('christmas')" aria-pressed="false">
      <div class="flex items-center gap-2" aria-hidden="true">

        <div x-show="theme !== 'christmas'"
          class="size-5 rounded-full border border-outline bg-transparent transition-all duration-500 ease-in-out dark:border-outlineDark">
        </div>

        <svg x-show="theme === 'christmas'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
          class="h-5 w-5 text-onSurface transition-all duration-500 ease-in-out dark:text-onSurfaceDark"
          style="display: none;">
          <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="text-left text-xs font-semibold capitalize text-onSurface dark:text-onSurfaceDark">
          Christmas
        </span>
      </div>
      <div class="bg-emerald-50 dark:bg-emerald-800 font-lato rounded-md flex h-full items-center gap-1 p-2">
        <div aria-hidden="true"
          class="bg-red-600 text-white dark:bg-red-600 dark:text-white rounded-md flex items-center p-1.5"></div>
        <div aria-hidden="true"
          class="bg-emerald-700 text-white dark:bg-emerald-600 dark:text-white rounded-md flex items-center p-1.5">
        </div>
      </div>
    </button>
    <button aria-label="Prototype" :aria-pressed="theme === 'prototype'"
      class="relative flex w-full cursor-pointer items-center justify-between gap-4 border border-outline bg-surface/50 p-2 transition hover:bg-surfaceAlt/50 dark:border-outlineDark dark:bg-surfaceDark/50 dark:hover:bg-surfaceDarkAlt/50 rounded-xl"
      :class="[
                    'rounded'
                ]" x-on:click="setTheme('prototype')" aria-pressed="false">
      <div class="flex items-center gap-2" aria-hidden="true">

        <div x-show="theme !== 'prototype'"
          class="size-5 rounded-full border border-outline bg-transparent transition-all duration-500 ease-in-out dark:border-outlineDark">
        </div>

        <svg x-show="theme === 'prototype'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
          class="h-5 w-5 text-onSurface transition-all duration-500 ease-in-out dark:text-onSurfaceDark"
          style="display: none;">
          <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="text-left text-xs font-semibold capitalize text-onSurface dark:text-onSurfaceDark">
          Prototype
        </span>
      </div>
      <div class="bg-neutral-100 dark:bg-neutral-900 font-playpen-sans rounded-none flex h-full items-center gap-1 p-2">
        <div aria-hidden="true"
          class="bg-black text-white dark:bg-white dark:text-black rounded-none flex items-center p-1.5"></div>
        <div aria-hidden="true"
          class="bg-neutral-700 text-white dark:bg-neutral-300 dark:text-black rounded-none flex items-center p-1.5">
        </div>
      </div>
    </button>
    <button aria-label="News" :aria-pressed="theme === 'news'"
      class="relative flex w-full cursor-pointer items-center justify-between gap-4 border border-outline bg-surface/50 p-2 transition hover:bg-surfaceAlt/50 dark:border-outlineDark dark:bg-surfaceDark/50 dark:hover:bg-surfaceDarkAlt/50 rounded-xl"
      :class="[
                    'rounded'
                ]" x-on:click="setTheme('news')" aria-pressed="false">
      <div class="flex items-center gap-2" aria-hidden="true">

        <div x-show="theme !== 'news'"
          class="size-5 rounded-full border border-outline bg-transparent transition-all duration-500 ease-in-out dark:border-outlineDark">
        </div>

        <svg x-show="theme === 'news'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
          class="h-5 w-5 text-onSurface transition-all duration-500 ease-in-out dark:text-onSurfaceDark"
          style="display: none;">
          <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="text-left text-xs font-semibold capitalize text-onSurface dark:text-onSurfaceDark">
          News
        </span>
      </div>
      <div class="bg-zinc-100 dark:bg-zinc-800 font-inter rounded-sm flex h-full items-center gap-1 p-2">
        <div aria-hidden="true"
          class="bg-sky-700 text-white dark:bg-sky-600 dark:text-white rounded-sm flex items-center p-1.5"></div>
        <div aria-hidden="true"
          class="bg-black text-white dark:bg-white dark:text-black rounded-sm flex items-center p-1.5"></div>
      </div>
    </button>
    <button aria-label="Industrial" :aria-pressed="theme === 'industrial'"
      class="relative flex w-full cursor-pointer items-center justify-between gap-4 border border-outline bg-surface/50 p-2 transition hover:bg-surfaceAlt/50 dark:border-outlineDark dark:bg-surfaceDark/50 dark:hover:bg-surfaceDarkAlt/50 rounded-xl"
      :class="[
                    'rounded'
                ]" x-on:click="setTheme('industrial')" aria-pressed="false">
      <div class="flex items-center gap-2" aria-hidden="true">

        <div x-show="theme !== 'industrial'"
          class="size-5 rounded-full border border-outline bg-transparent transition-all duration-500 ease-in-out dark:border-outlineDark">
        </div>

        <svg x-show="theme === 'industrial'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
          class="h-5 w-5 text-onSurface transition-all duration-500 ease-in-out dark:text-onSurfaceDark"
          style="display: none;">
          <path fill-rule="evenodd"
            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="text-left text-xs font-semibold capitalize text-onSurface dark:text-onSurfaceDark">
          Industrial
        </span>
      </div>
      <div class="bg-stone-200 dark:bg-stone-900 font-poppins rounded-none flex h-full items-center gap-1 p-2">
        <div aria-hidden="true"
          class="bg-amber-500 text-black dark:bg-amber-400 dark:text-black rounded-none flex items-center p-1.5">
        </div>
        <div aria-hidden="true"
          class="bg-stone-900 text-stone-50 dark:bg-stone-700 dark:text-white rounded-none flex items-center p-1.5">
        </div>
      </div>
    </button>
  </div>
</div>
<!-- </div> -->


<!-- <div class="hide-scrollbar flex flex-col gap-4 overflow-auto text-onSurface dark:text-onSurface">
  <div class="relative flex flex-col w-full border-2" x-data="{ showThemes: false }">
    <label for="titleFontFamilySelect"
      class="mb-1 flex items-center pl-0.5 text-sm text-onSurface dark:text-onSurfaceDark">
      Theme
    </label>
    <button
      class="flex cursor-pointer items-center justify-between border-outline bg-surfaceAlt py-2 pl-2 pr-4 dark:border-outlineDark dark:bg-surfaceDarkAlt rounded-xl border-none"
      x-on:click="showThemes=!showThemes">
      <span class="text-sm capitalize text-onSurface dark:text-onSurfaceDarkStrong" x-text="
        theme === 'arctic' ? 'Arctic' :
        theme === 'minimal' ? 'Minimal' :
        theme === 'modern' ? 'Modern' :
        theme === 'high-contrast' ? 'High Contrast' :
        theme === 'neo-brutalism' || theme === 'punk' ? 'Neo Brutalism' :
        theme === 'halloween' ? 'Halloween' :
        theme === 'zombie' ? 'Zombie' :
        theme === 'pastel' ? 'Pastel' :
        theme === '90s' ? '90s' :
        theme === 'christmas' ? 'Christmas' :
        theme === 'prototype' ? 'Prototype' :
        theme === 'news' ? 'News' :
        theme === 'industrial' ? 'Industrial' : 'Unknown Theme'
      ">Minimal</span>

      <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
        class="absolute right-4 top-9 h-4 w-4 text-onSurface dark:text-onSurfaceDark" viewBox="0 0 16 16">
        <path fill-rule="evenodd"
          d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z">
        </path>
      </svg>
    </button>
    <div x-show="showThemes" @click.outside="showThemes = false" style="display: none;"
      class="absolute top-16 z-10 flex flex-col gap-1 h-72 w-full overflow-auto border-outline bg-surfaceAlt p-1 shadow-[0_3px_10px_rgb(0,0,0,0.1)] dark:border-outlineDark dark:bg-surfaceDarkAlt rounded-xl">
      <button x-on:click="setTheme('arctic')">Arctic</button>
      <button x-on:click="setTheme('minimal')">Minimal</button>
      <button x-on:click="setTheme('modern')">Modern</button>
      <button x-on:click="setTheme('high-contrast')">High Contrast</button>
      <button x-on:click="setTheme('neo-brutalism')">Neo Brutalism</button>
      <button x-on:click="setTheme('halloween')">Halloween</button>
      <button x-on:click="setTheme('zombie')">Zombie</button>
      <button x-on:click="setTheme('pastel')">Pastel</button>
      <button x-on:click="setTheme('90s')">90s</button>
      <button x-on:click="setTheme('christmas')">Christmas</button>
      <button x-on:click="setTheme('prototype')">Prototype</button>
      <button x-on:click="setTheme('news')">News</button>
      <button x-on:click="setTheme('industrial')">Industrial</button>
    </div>
  </div>
</div> -->
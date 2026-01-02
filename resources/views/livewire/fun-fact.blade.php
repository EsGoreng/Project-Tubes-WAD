<div class="max-w-7xl px-4 py-8 mx-auto text-center lg:py-16 lg:px-6">
    <figure class="max-w-screen-md mx-auto">
        <svg class="h-12 mx-auto mb-3 text-gray-400 dark:text-gray-600" viewBox="0 0 24 27" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M14.017 18L14.017 10.609C14.017 4.905 17.748 1.039 23 0L23.995 2.151C21.563 3.068 20 5.789 20 8H24V18H14.017ZM0 18V10.609C0 4.905 3.748 1.038 9 0L9.996 2.151C7.563 3.068 6 5.789 6 8H9.983L9.983 18L0 18Z"
                fill="currentColor" />
        </svg>
        
        @if($loading)
            <div class="flex items-center justify-center py-8">
                <svg class="animate-spin h-10 w-10 text-primary-600 dark:text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        @elseif($error)
            <div class="mb-6">
                <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-medium">Oops!</span> {{ $error }}
                </div>
            </div>
        @else
            <blockquote>
                <p class="text-2xl font-medium text-gray-900 dark:text-white">
                    "{{ $fact }}"
                </p>
            </blockquote>
        @endif
        
        <figcaption class="flex items-center justify-center mt-6 space-x-3">
            <div class="flex items-center divide-x-2 divide-gray-500 dark:divide-gray-700">
                <div class="pr-3 text-sm font-light text-gray-500 dark:text-gray-400">Random Fun Fact</div>
                <button 
                    wire:click="getFact" 
                    wire:loading.attr="disabled"
                    class="pl-3 text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-500 dark:hover:text-primary-400 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                    <span wire:loading.remove wire:target="getFact">Get New Fact</span>
                    <span wire:loading wire:target="getFact">Loading...</span>
                </button>
            </div>
        </figcaption>
    </figure>
</div>
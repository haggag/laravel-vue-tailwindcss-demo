
@props([
'type' => 'add',
'icons' => [
'add' => '<svg  width="16" height="16" viewBox="0 0 14 14"> <g fill="#FFF"> <polygon points="8 0 8 14 6 14 6 0"/> <polygon points="14 6 14 8 0 8 0 6"/> </g> </svg>',
'import' => '<svg  xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="10 10 14 17"><defs><filter id="a" width="121.8%" height="175%" x="-10.9%" y="-37.5%" filterUnits="objectBoundingBox"><feOffset dy="1" in="SourceAlpha" result="shadowOffsetOuter1"/><feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="3"/><feColorMatrix in="shadowBlurOuter1" result="shadowMatrixOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.3 0"/><feMerge><feMergeNode in="shadowMatrixOuter1"/><feMergeNode in="SourceGraphic"/></feMerge></filter></defs><g fill="#FFF" filter="url(#a)" transform="translate(-1 1)"><path d="M15 22h6v-6h4l-7-7-7 7h4v6zm-4 2h14v2H11v-2z"/></g></svg>',

]
])

<button type="button" class="  sm:ml-4 shadow-sm  inline-flex items-center px-2 py-2 border border-transparent text-xs leading-5  rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">

    {!! $icons[$type] !!}
    <span class="hidden md:block ml-4 font-bold">{{ $slot }}</span>
</button>

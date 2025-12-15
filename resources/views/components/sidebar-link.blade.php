@props(['route', 'icon', 'label'])

@php
    $active = request()->routeIs($route);
@endphp

<a href="{{ route($route) }}"
   class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition
   {{ $active ? 'bg-gray-500/50 text-white' : 'text-gray-400 hover:bg-gray-100 hover:text-gray-900' }}">
    <i class="{{ $icon }} w-5 mr-3"></i>
    <span>{{ $label }}</span>
</a>

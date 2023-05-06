<x-app-layout>
    <x-slot:title>{{ $checklist->name }} - Listify Now</x-slot:title>


    <div id="app" class="w-full" data-checklist="{{ $checklist->toJson() }}"></div>

    <x-slot:javascript>
        @vite(['resources/js/pages/checklist_form.js'])
    </x-slot:javascript>
</x-app-layout>

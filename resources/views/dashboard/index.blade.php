<x-app-layout>
    <x-slot:title>Dashboard - Listify Now</x-slot:title>

    <x-slot:css>
        @vite(['resources/css/datatables.css'])
    </x-slot:css>

    <section class="container columns-1 mx-auto mt-8 px-4">
        @auth
            <article class="flex p-4 bg-background-light rounded drop-shadow-xl">
                <div class="">
                    <img class="h-full" src="{{ asset(auth()->user()->avatar ?: 'images/home/image_2.svg') }}">
                </div>
                <div class="ml-8 self-center text-xl font-bold">
                    You’re logged in as {{ auth()->user()->name }}
                </div>
            </article>

            <article class="justify-center my-14 bg-background-light rounded p-4 drop-shadow-xl">
                <h3 class="font-extrabold text-xl my-1">Your checklists</h3>
                <div class="w-full border border-black"></div>
                {{--  @TODO add filtering by dates, search text input  --}}
                <table class="display" id="checklistsDatatables" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Creation date</th>
                            <th class="text-center">Last update</th>
                            <th class="text-center">Items count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($checklists as $checklist)
                            <tr class="cursor-pointer" data-id="{{ $checklist->id }}">
                                <td>{{ $checklist->name }}</td>
                                <td>{{ $checklist->created_at->toDateTimeString() }}</td>
                                <td>{{ $checklist->updated_at->toDateTimeString() }}</td>
                                <td>{{ $checklist->items->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>
        @endauth

        <article class="bg-background-light rounded p-4 drop-shadow-xl" id="localStorageChecklistsDatatableContainer" style="display: none;">
            <h3 class="font-extrabold text-xl">Checklists created on this device (anonymously)</h3>
            <div class="w-full border border-black my-1"></div>
            <table class="display" id="localStorageChecklistsDatatable"></table>
        </article>

        @guest
            <article class="my-16 flex flex-col bg-background-contrast rounded p-4 drop-shadow-xl ring ring-background-primary">
                <h3 class="font-extrabold text-xl text-center">
                    Don’t see your list? Or created a list anonymously and want to assign it to your account?
                </h3>
                <x-button type="link"
                          variant="green"
                          class="rounded-lg align-baseline text-xl mx-auto mt-4"
                          href="{{ url('/login') }}"
                >
                    <i class="fi fi-rr-sign-in-alt"></i> Login
                </x-button>
            </article>
        @endguest
    </section>

    <x-slot:javascript>
        @vite(['resources/js/pages/dashboard.js'])
    </x-slot:javascript>
</x-app-layout>

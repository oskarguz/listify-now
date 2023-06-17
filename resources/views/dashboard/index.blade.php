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
                <h3 class="font-extrabold text-xl my-1">Your lists</h3>
                <div class="w-full border border-black"></div>
                {{--  @TODO add filtering by dates, search text input  --}}
                <table class="display" id="checklistsDatatables" style="width:100%">
                    <thead>
                        <tr>
                            <th class="sm:hidden">Your lists</th>
                            <th class="hidden sm:table-cell" style="text-align: center">Name</th>
                            <th class="hidden sm:table-cell" style="text-align: center">Creation date</th>
                            <th class="hidden sm:table-cell" style="text-align: center">Visibility</th>
                            <th class="hidden sm:table-cell" style="text-align: center">Items count</th>
                            <th class="hidden sm:table-cell" style="text-align: center" data-sortable="false"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($checklists as $checklist)
                            <tr class="cursor-pointer" data-id="{{ $checklist->id }}">
                                <td class="sm:hidden">
                                    <strong>{{ $checklist->name }}</strong>
                                    @if ($checklist->is_public())
                                        <span class="text-green-700 text-sm font-semibold">
                                            (<i class="fi-bs-eye align-middle"></i> Public)
                                        </span>
                                    @else
                                        <span class="text-red-700 text-sm font-semibold">
                                            (<i class="fi-bs-eye-crossed align-middle"></i> Private)
                                        </span>
                                    @endif
                                    ,<br/>
                                    <span class="italic">Creation date:</span> {{ $checklist->created_at->toDateTimeString() }},<br/>
                                    <span class="italic">Items count:</span> {{ $checklist->items->count() }},<br/>
                                    <div class="flex w-full">
                                        <button class="bg-red-500 p-2 rounded deleteBtn">
                                            <i class="fi-br-trash align-middle"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell text-center">{{ $checklist->name }}</td>
                                <td class="hidden sm:table-cell text-center">{{ $checklist->created_at->toDateTimeString() }}</td>
                                <td class="hidden sm:table-cell text-center">
                                    @if ($checklist->is_public())
                                        <span class="text-green-700 text-sm font-semibold">
                                            <i class="fi-bs-eye align-middle"></i> Public
                                        </span>
                                    @else
                                        <span class="text-red-700 text-sm font-semibold">
                                            <i class="fi-bs-eye-crossed align-middle"></i> Private
                                        </span>
                                    @endif
                                </td>
                                <td class="hidden sm:table-cell text-center">{{ $checklist->items->count() }}</td>
                                <td class="hidden sm:table-cell text-center">
                                    <button class="bg-red-500 p-2 rounded deleteBtn">
                                        <i class="fi-br-trash align-middle"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>
        @endauth

        <article class="bg-background-light rounded p-4 drop-shadow-xl" id="localStorageChecklistsDatatableContainer" style="display: none;">
            <h3 class="font-extrabold text-xl">Lists created on this device (anonymously)</h3>
            <div class="w-full border border-black my-1"></div>
            <table class="display" id="localStorageChecklistsDatatable" style="width:100%"></table>
        </article>

        @guest
            <article class="my-16 flex flex-col bg-background-contrast rounded p-4 drop-shadow-xl ring ring-background-primary">
                <h3 class="font-extrabold text-xl text-center">
                    Don’t see your list? Or created a list anonymously and want to assign it to your account?
                </h3>
                <x-button type="link"
                          variant="green"
                          class="rounded-lg align-baseline text-xl mx-auto mt-4 py-2 px-4"
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

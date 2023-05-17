<x-app-layout>
    <x-slot:title>Home - Listify Now</x-slot:title>

    <section class="container columns-1 mx-auto mt-8 px-4">
        <div class="w-full flex h-[250px] md:h-[300px] max-w-[800px] mx-auto bg-background-contrast rounded py-4 drop-shadow-xl ring ring-background-primary">
            <article class="w-full flex flex-col justify-center px-4">
                <p class="text-center font-semibold text-lg md:text-2xl">Simplify your to-do's</p>
            </article>
            <article class="w-full flex content-center justify-center">
                <img class="h-full" src="{{ asset('images/home/image_1.svg') }}">
            </article>
        </div>

        <div class="w-full flex mt-8 h-[250px] md:h-[300px] max-w-[800px] mx-auto bg-background-contrast rounded py-4 drop-shadow-xl ring ring-background-primary">
            <article class="w-full flex content-center justify-center">
                <img class="h-full" src="{{ asset('images/home/image_2.svg') }}">
            </article>
            <article class="w-full flex flex-col justify-center px-4">
                <p class="text-center font-semibold text-lg md:text-2xl">Create shopping list in the fly</p>
            </article>
        </div>
    </section>

    <section class="container columns-1 mx-auto mt-8 px-4">
        <div class="bg-background-contrast rounded py-4 max-w-[800px] mx-auto drop-shadow-xl ring ring-background-primary">
            <div class="w-full flex h-[250px] md:h-[300px] mx-auto">
                <article class="w-full flex content-center justify-center">
                    <img class="h-full" src="{{ asset('images/home/image_3.svg') }}">
                </article>
            </div>
            <article class="flex flex-col justify-center mt-2 px-4">
                <p class="text-center font-semibold text-lg md:text-2xl">Plan your day and get things done with our easy-to-use list-making tool</p>
            </article>
        </div>
    </section>

    <section class="container columns-1 mx-auto my-8 px-8 drop-shadow-xl">
        <article class="flex flex-col justify-center gap-3 max-w-[800px] mx-auto">
            <p class="text-center font-semibold md:text-lg">Create list with form here:</p>
            <x-button href="{{ url('/checklist/create') }}" type="link" variant="secondary" class="w-2/3 mx-auto text-center rounded-lg">Create list</x-button>
            <p class="text-center font-bold md:text-lg">OR</p>
            <p class="text-center font-semibold md:text-lg">Paste your text here. Each new line generates a new checkbox on the list:</p>
            <textarea class="bg-background-light font-semibold border-0 ring ring-white hover:ring-orange-400 focus:ring focus:ring-orange-400 rounded" rows="5" placeholder="{{ "First item on the list,\nSecond item on the list,\n....\n...\n.." }}"></textarea>
        </article>
    </section>
</x-app-layout>

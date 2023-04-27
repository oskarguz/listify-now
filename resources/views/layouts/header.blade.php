<nav class="flex justify-between p-4 bg-gray-300">
    <h2 class="align-middle text-xl font-semibold leading-10">
        <a href="{{ url('/') }}">
            Listify Now
        </a>
    </h2>
    <div>
        <x-button type="link"
                  variant="green"
                  class="rounded-lg align-baseline me-3 text-xl"
                  href="{{ url('/checklist/create') }}"
        >
            <i class="fi fi-br-plus"></i> Create
        </x-button>
        <x-button type="link"
                  variant="transparent"
                  text-color="black"
                  class="!p-0 text-3xl"
                  href="{{ url('/login') }}"
        >
            <i class="fi fi-ss-user"></i>
        </x-button>
    </div>
</nav>

<nav class="flex justify-between p-4 bg-background-secondary text-white fixed w-full ring ring-background-primary drop-shadow-xl" style="z-index: 1000;">
    <h2 class="align-middle text-xl font-semibold leading-10">
        <a href="{{ url('/') }}" class=" hover:underline hover:underline-offset-8 hover:text-background-contrast">
            Listify Now
        </a>
    </h2>
    <div class="flex">
        <x-button type="link"
                  variant="transparent"
                  class="rounded-lg self-center me-3 text-sm hover:underline hover:underline-offset-8 hover:text-background-contrast"
                  href="{{ url('/checklist/create') }}"
        >
            <i class="fi fi-br-plus"></i> Create
        </x-button>
        <x-button type="link"
                  variant="transparent"
                  class="rounded-lg text-sm hover:underline hover:underline-offset-8 hover:text-background-contrast"
                  href="{{ url('/dashboard') }}"
        >
            <i class="fi fi-ss-user"></i> Profile
        </x-button>
        @auth
            <x-button type="link"
                      variant="transparent"
                      class="rounded-lg text-sm hover:underline hover:underline-offset-8 hover:text-background-contrast text-red-200"
                      href="{{ url('/logout') }}"
            >
                <i class="fi fi-ss-exit"></i> Logout
            </x-button>
        @endauth
    </div>
</nav>

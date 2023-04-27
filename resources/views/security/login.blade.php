<x-app-layout>
    <x-slot:title>Login</x-slot:title>

    <section class="self-stretch flex w-full justify-center pb-24">
        <div class="flex flex-col items-center justify-center h-full gap-6">
            <h3 class="font-bold text-3xl">Login</h3>
            <x-button class="bg-blue-600 rounded-xl w-[240px] sm:w-[320px] md:w-[440px] text-center leading-none text-xl py-2 sm:py-3 relative"
                      type="link"
                      href="{{ route('security.auth', ['driver' => \App\Enum\SocialAuthenticationDriver::Discord->value]) }}"
            >
                <i class="fi fi-brands-discord absolute left-3"></i>
                Discord
            </x-button>
            <x-button class="bg-yellow-600 hover:bg-yellow-700 rounded-xl w-[240px] sm:w-[320px] md:w-[440px] text-center leading-none text-xl py-2 sm:py-3 relative"
                      type="link"
                      href="{{ route('security.auth', ['driver' => \App\Enum\SocialAuthenticationDriver::Google->value]) }}"
            >
                <i class="fi fi-brands-google absolute left-3"></i>
                Google
            </x-button>
            <x-button class="bg-blue-400 hover:bg-blue-500 rounded-xl w-[240px] sm:w-[320px] md:w-[440px] text-center leading-none text-xl py-2 sm:py-3 relative"
                      type="link"
                      href="{{ route('security.auth', ['driver' => \App\Enum\SocialAuthenticationDriver::Twitter->value]) }}"
            >
                <i class="fi fi-brands-twitter absolute left-3"></i>
                Twitter
            </x-button>
        </div>
    </section>
</x-app-layout>

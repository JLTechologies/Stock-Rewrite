@php($contact = settings('contact'))

<footer class="bg-navy-900 text-white/70">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <div class="lg:col-span-2">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-white">
                <x-logo class="h-10 w-10" />
                <span class="text-lg font-extrabold tracking-tight">{{ settings('general.site_name') }}</span>
            </a>
            <p class="mt-6 max-w-md text-sm leading-relaxed">
                {{ __('site.footer.about', ['name' => settings('general.site_name'), 'year' => settings('general.founded')]) }}
            </p>
        </div>

        <div>
            <h2 class="eyebrow">{{ __('site.footer.navigation') }}</h2>
            <ul class="mt-5 space-y-3 text-sm">
                <li><a href="{{ route('expertises') }}" class="hover:text-accent">{{ __('site.nav.expertises') }}</a></li>
                <li><a href="{{ route('projects.index') }}" class="hover:text-accent">{{ __('site.nav.projects') }}</a></li>
                <li><a href="{{ route('posts.index') }}" class="hover:text-accent">{{ __('site.nav.news') }}</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-accent">{{ __('site.nav.contact') }}</a></li>
                <li><a href="{{ route('privacy') }}" class="hover:text-accent">{{ __('site.privacy.title') }}</a></li>
            </ul>
        </div>

        <div>
            <h2 class="eyebrow">{{ __('site.footer.contact') }}</h2>
            <ul class="mt-5 space-y-3 text-sm">
                <li class="flex gap-3">
                    <x-site-icon name="map-pin" class="mt-0.5 h-4 w-4 text-accent" />
                    <span>{{ $contact['street'] }}<br>{{ $contact['postal_code'] }} {{ translated_value($contact['city']) }}</span>
                </li>
                @if (filled($contact['phone']))
                    <li class="flex gap-3">
                        <x-site-icon name="phone" class="mt-0.5 h-4 w-4 text-accent" />
                        <a href="tel:{{ preg_replace('/[^+\d]/', '', $contact['phone']) }}" class="hover:text-accent">{{ $contact['phone'] }}</a>
                    </li>
                @endif
                @if (filled($contact['email']))
                    <li class="flex gap-3">
                        <x-site-icon name="mail" class="mt-0.5 h-4 w-4 text-accent" />
                        <a href="mailto:{{ $contact['email'] }}" class="hover:text-accent">{{ $contact['email'] }}</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 font-mono text-xs sm:flex-row sm:justify-between sm:px-6 lg:px-8">
            <span>&copy; {{ now()->year }} {{ settings('general.site_name') }}</span>
            <span class="flex gap-6">
                @if (filled($contact['vat']))
                    <span>{{ __('site.footer.vat') }} {{ $contact['vat'] }}</span>
                @endif
                <a href="{{ url('/admin') }}" class="hover:text-accent" rel="nofollow">{{ __('site.footer.login') }}</a>
            </span>
        </div>
    </div>
</footer>

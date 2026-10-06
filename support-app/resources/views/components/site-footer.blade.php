@inject('helpdesk', 'App\Support\HelpdeskSettings')

@php
    $company = helpdesk()->companyName();
    $phone = helpdesk()->get('branding.phone');
    $email = helpdesk()->get('branding.email');
    $vatNumber = helpdesk()->get('branding.vat_number');
    $street = helpdesk()->get('branding.street');
    $city = trim(helpdesk()->get('branding.postal_code').' '.helpdesk()->translated('branding.city'));
    $openingHours = helpdesk()->translated('branding.opening_hours');
    $mainSiteUrl = helpdesk()->get('branding.main_site_url');
@endphp

<footer class="bg-navy-900 text-white/70">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 py-14 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <div class="lg:col-span-2">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-white">
                <x-logo class="h-10 w-10" />
                <span class="text-lg font-extrabold tracking-tight">{{ $company }}</span>
            </a>
            <p class="mt-6 max-w-md text-sm leading-relaxed">
                {{ helpdesk()->translated('branding.about') ?? __('support.footer.about', ['company' => $company]) }}
            </p>
            @if (filled($mainSiteUrl))
                <a href="{{ $mainSiteUrl }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-white hover:text-accent">
                    {{ __('support.footer.main_site') }} <x-site-icon name="external" class="h-4 w-4" />
                </a>
            @endif
        </div>

        <div>
            <h2 class="eyebrow">{{ __('support.footer.support') }}</h2>
            <ul class="mt-5 space-y-3 text-sm">
                @auth
                    <li><a href="{{ route('tickets.index') }}" class="hover:text-accent">{{ __('support.nav.tickets') }}</a></li>
                    <li><a href="{{ route('tickets.create') }}" class="hover:text-accent">{{ __('support.nav.new_ticket') }}</a></li>
                    <li><a href="{{ route('profile.edit') }}" class="hover:text-accent">{{ __('support.nav.profile') }}</a></li>
                @else
                    <li><a href="{{ route('login') }}" class="hover:text-accent">{{ __('support.nav.login') }}</a></li>
                    @if ($helpdesk->get('allow_registration'))
                        <li><a href="{{ route('register') }}" class="hover:text-accent">{{ __('support.nav.register') }}</a></li>
                    @endif
                @endauth
                @if ($helpdesk->get('show_knowledge_base'))
                    <li><a href="{{ route('kb.index') }}" class="hover:text-accent">{{ __('support.nav.kb') }}</a></li>
                @endif
                @if (filled($openingHours))
                    <li class="flex gap-3 pt-2">
                        <x-site-icon name="clock" class="mt-0.5 h-4 w-4 text-accent" />
                        <span>{{ $openingHours }}</span>
                    </li>
                @endif
            </ul>
        </div>

        <div>
            <h2 class="eyebrow">{{ __('support.footer.contact') }}</h2>
            <ul class="mt-5 space-y-3 text-sm">
                @if (filled($street) || filled($city))
                    <li class="flex gap-3">
                        <x-site-icon name="map-pin" class="mt-0.5 h-4 w-4 text-accent" />
                        <span>{{ $street }}@if (filled($street) && filled($city))<br>@endif{{ $city }}</span>
                    </li>
                @endif
                @if (filled($phone))
                    <li class="flex gap-3">
                        <x-site-icon name="phone" class="mt-0.5 h-4 w-4 text-accent" />
                        <a href="tel:{{ preg_replace('/[^+\d]/', '', $phone) }}" class="hover:text-accent">{{ $phone }}</a>
                    </li>
                @endif
                @if (filled($email))
                    <li class="flex gap-3">
                        <x-site-icon name="mail" class="mt-0.5 h-4 w-4 text-accent" />
                        <a href="mailto:{{ $email }}" class="hover:text-accent">{{ $email }}</a>
                    </li>
                @endif
                @if (filled($vatNumber))
                    <li class="flex gap-3">
                        <x-site-icon name="receipt" class="mt-0.5 h-4 w-4 text-accent" />
                        <span>{{ __('support.footer.vat_number') }} {{ $vatNumber }}</span>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 font-mono text-xs sm:flex-row sm:justify-between sm:px-6 lg:px-8">
            <span>&copy; {{ now()->year }} {{ $company }}</span>
            <a href="{{ url('/agent') }}" class="hover:text-accent" rel="nofollow">{{ __('support.footer.staff_login') }}</a>
        </div>
    </div>
</footer>

<x-layouts.app :title="__('support.nav.profile')">
    <x-page-hero :eyebrow="__('support.nav.profile')" :title="__('support.profile.title')" />

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <x-flash class="mb-6" />

        <form method="POST" action="{{ route('profile.update') }}" class="card space-y-8 p-6 sm:p-8">
            @csrf
            @method('PUT')

            <fieldset class="grid gap-6 sm:grid-cols-2">
                <legend class="mb-4 text-lg font-bold">{{ __('support.profile.details') }}</legend>
                <x-form-field name="name" :label="__('support.fields.name')" :value="$user->name" required autocomplete="name" />
                <x-form-field name="company" :label="__('support.fields.company')" :value="$user->company" autocomplete="organization" />
                <x-form-field name="email" type="email" :label="__('support.fields.email')" :value="$user->email" required autocomplete="email" />
                <x-form-field name="phone" type="tel" :label="__('support.fields.phone')" :value="$user->phone" autocomplete="tel" />
                <x-form-field name="locale" type="select" :label="__('support.fields.locale')" :hint="__('support.profile.locale_hint')" required>
                    @foreach (config('app.locales') as $code => $language)
                        <option value="{{ $code }}" @selected(old('locale', $user->locale) === $code)>{{ $language }}</option>
                    @endforeach
                </x-form-field>
            </fieldset>

            <fieldset class="grid gap-6 border-t border-line pt-8 sm:grid-cols-2">
                <legend class="mb-4 text-lg font-bold">{{ __('support.profile.password') }}</legend>
                <x-form-field class="sm:col-span-2" name="current_password" type="password" :label="__('support.fields.current_password')" autocomplete="current-password" :hint="__('support.profile.password_hint')" />
                <x-form-field name="password" type="password" :label="__('support.fields.new_password')" autocomplete="new-password" />
                <x-form-field name="password_confirmation" type="password" :label="__('support.fields.password_confirmation')" autocomplete="new-password" />
            </fieldset>

            <div class="flex justify-end border-t border-line pt-6">
                <button type="submit" class="btn btn-primary">{{ __('support.profile.save') }}</button>
            </div>
        </form>
    </div>
</x-layouts.app>

<x-layout title="Profile">
    <x-form title="Edit your account" subtitle="Need to make a tweak?">
        <form action="/profile/edit" method="post" class="mt-10 space-y-4">
            @csrf
            @method('PATCH')

            <x-form.field name="username" label="Username" :value="$user->username" />
            <x-form.field name="email" label="Email" type="email" :value="$user->email" />
            <x-form.field name="password" label="New Password" type="password"/>
            <x-form.field name="password_confirmation" label="Confirm New Password" type="password"/>

            <button type="submit" class="btn mt-3 h-10 w-full" data-test="save-profile-button">Save</button>
        </form>
    </x-form>
</x-layout>

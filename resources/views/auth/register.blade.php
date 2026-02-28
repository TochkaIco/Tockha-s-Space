<x-layout title="Register">
    <x-form title="Register an Account" subtitle="Start tracking your activities today.">
        <form action="/register" method="post" class="mt-10 space-y-4">
            @csrf

            <x-form.field name="username" label="Username"/>
            <x-form.field name="email" label="Email" type="email"/>
            <x-form.field name="password" label="Password" type="password"/>
            <x-form.field name="password_confirmation" label="Confirm Password" type="password"/>

            <button type="submit" class="btn mt-3 h-10 w-full">Create Account</button>
        </form>
    </x-form>
</x-layout>

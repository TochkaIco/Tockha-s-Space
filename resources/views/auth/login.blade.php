<x-layout title="Login">
    <x-form title="Sign in" subtitle="">
        <form action="/login" method="post" class="mt-10 space-y-4">
            @csrf

            <x-form.field name="email" label="Email" type="email"/>
            <x-form.field name="password" label="Password" type="password"/>

            <button type="submit" class="btn mt-3 h-10 w-full">Sign In</button>
        </form>
    </x-form>
</x-layout>

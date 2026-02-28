<nav class="border-b border-border px-6">
    <div class="max-w-7xl mx-auto h-16 flex items-center justify-between">
        <div class="flex items-center justify-between gap-x-3">
            <a href="">
                <img src="/images/logo.png" alt="logo" width="50" class="rounded-2xl">
            </a>
            <h3 class="font-bold text-3xl">Tochka's Portal</h3>
        </div>

        @auth
            <form action="/logout" method="post">
                @csrf
                <button type="submit">Log Out</button>
            </form>
        @endauth

        @guest
            <div class="flex gap-x-3">
                <a href="/register" class="btn">Register</a>
                <a href="/login">Sign In</a>
            </div>
        @endguest
    </div>
</nav>

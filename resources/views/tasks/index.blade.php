<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Tasks</h1>
            <p class="text-muted-foreground text-sm mt-2">Capture your thoughts. Make a plan.</p>
        </header>

        <div>
            <a
                href="/tasks"
                class="btn {{ request()->has('status') ? 'btn-outlined' : '' }}"
            >
                All
                <span class="text-xs pl-2">{{ $statusCounts->get('all') }}</span>
            </a>

            @foreach(App\TaskStatus::cases() as $status)
                <a
                    href="/tasks?status={{ $status }}"
                    class="btn {{ request('status') === $status->value ? '' : 'btn-outlined' }}"
                >
                    {{ $status->label() }}
                    <span class="text-xs pl-2">{{ $statusCounts->get($status->value) }}</span>
                </a>
            @endforeach
        </div>

        <div class="mt-10 text-muted-foreground">
            <div class="grid md:grid-cols-2 gap-6">
                @forelse($tasks as $task)
                    <x-card href="{{ route('task.show', $task) }}">
                        <h3 class="text-foreground text-lg">{{ $task->title }}</h3>
                        <p class="mt-5 line-clamp-2">{{ $task->description }}</p>
                        <div class="mt-2">
                            <x-task.status-label status="{{ $task->status->label() }}">
                                {{ $task->status->label() }}
                            </x-task.status-label>
                        </div>
                        <div class="mt-2">Created {{ $task->created_at->diffForHumans() }}</div>
                    </x-card>
                @empty
                    <x-card>
                        <p>No tasks at this time</p>
                    </x-card>
                @endforelse
            </div>
        </div>
    </div>
</x-layout>

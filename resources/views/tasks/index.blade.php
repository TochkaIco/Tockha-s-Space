<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Your Tasks</h1>
            <p class="text-muted-foreground text-sm mt-2">Capture your thoughts. Make a plan.</p>
            <x-card
                x-data
                is="button"
                data-test="create-task-button"
                class="mt-3 cursor-pointer h-32 w-full text-left"
                @click="$dispatch('open-modal', 'create-task')"
            >
                <p>What's on your mind?</p>
            </x-card>
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
                        @if($task->image_path)
                            <div class="mb-4 -mx-4 -mt-4 rounded-t-lg overflow-hidden">
                                <img src="{{ asset('storage/' . $task->image_path) }}" alt="Image" class="w-full h-auto max-h-60 object-cover mb-2">
                            </div>
                        @endif

                        <h3 class="text-foreground text-lg">{{ $task->title }}</h3>
                        <p class="mt-5 line-clamp-2">{{ $task->description }}</p>
                        <div class="mt-2">
                            <x-task.status-label status="{{ $task->status->label() }}">
                                {{ $task->status->label() }}
                            </x-task.status-label>
                        </div>
                        <div class="mt-2 flex gap-x-3 items-center text-muted-foreground text-sm">
                            <span>Created {{ $task->created_at->diffForHumans() }}</span>
                            @if($task->created_at != $task->updated_at)
                                <span>Updated {{ $task->updated_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </x-card>
                @empty
                    <x-card>
                        <p>No tasks at this time</p>
                    </x-card>
                @endforelse
            </div>
        </div>

        <x-task.modal />
    </div>
</x-layout>

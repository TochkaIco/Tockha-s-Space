<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Your Tasks</h1>
            <p class="text-muted-foreground text-sm mt-2">Capture your thoughts. Make a plan.</p>

            <x-card
                x-data
                @click="$dispatch('open-modal', 'create-task')"
                is="button"
                class="mt-3 cursor-pointer h-32 w-full text-left">
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

        <x-modal name="create-task" title="Create Task">
            <form x-data="{ status: @js(App\TaskStatus::PENDING->value) }" action="{{ route('task.store') }}" method="post">
                @csrf
                <div class="space-y-6">
                    <x-form.field
                        label="Title"
                        name="title"
                        placeholder="Enter a title for your task"
                        autofocus
                        required
                    />

                    <div class="space-y-2">
                        <label for="status" class="label">Status</label>

                        <div class="flex gap-x-3 mt-3">
                            @foreach(App\TaskStatus::cases() as $status)
                                <button
                                    type="button"
                                    @click="status = @js($status->value)"
                                    class="btn flex-1 h-10"
                                    :class="status === @js($status->value) ? '' : 'btn-outlined' "
                                    :class="{'btn-primary': status !== @js($status->value)}"
                                >
                                    {{ $status->label() }}
                                </button>
                            @endforeach

                                <input type="hidden" name="status" :value="status" class="input">
                        </div>

                        <x-form.error name="status" />
                    </div>

                    <x-form.field
                        label="Description"
                        name="description"
                        type="textarea"
                        placeholder="Describe your task..."
                    />

                    <div class="flex justify-end gap-x-5">
                        <button type="button" @click="$dispatch('close-modal')">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </x-modal>
    </div>
</x-layout>

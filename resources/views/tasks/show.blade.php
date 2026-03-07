<x-layout title="Task Show">
    <div class="py-8 max-w-4xl mx-auto">
        <div class="flex justify-between items-center">
            <a href="/tasks" class="flex items-center gap-x-2 text-sm font-medium">
                <x-icons.arrow-back/>
                Back to Tasks
            </a>

            <div class="flex gap-x-3 items-center">
                <button class="btn btn-outlined">
                    <x-icons.external/>
                    Edit Task
                </button>
                <form action="{{ route('task.destroy', $task) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outlined text-red-500">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-6">
            <h1 class="font-bold text-4xl">{{ $task->title }}</h1>

            <div class="mt-2 flex gap-x-3 items-center">
                <x-task.status-label :status="$task->status->value">{{ $task->status->label() }}</x-task.status-label>

                <div class="flex gap-x-3 items-center text-muted-foreground text-sm">
                    <span>Created {{ $task->created_at->diffForHumans() }}</span>
                    @if($task->created_at != $task->updated_at)
                        <span>Updated {{ $task->updated_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>

            <x-card class="mt-6">
                <div class="text-foreground max-w-none cursor-pointer">{{ $task->description }}</div>
            </x-card>

            @if($task->links)
                <div>
                    <h3 class="font-bold text-xl mt-6">Links</h3>

                    <div class="mt-3 space-y-3">
                        @foreach($task->links as $link)
                            <x-card :href="$link" class="text-primary flex font-medium gap-x-3 items-center">
                                <x-icons.external/>
                                {{ $link }}
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout>

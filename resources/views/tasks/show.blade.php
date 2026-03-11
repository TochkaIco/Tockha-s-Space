<x-layout title="Task Show">
    <div class="py-8 max-w-4xl mx-auto">
        <div class="flex justify-between items-center">
            <a href="/tasks" class="flex items-center gap-x-2 text-sm font-medium">
                <x-icons.arrow-back/>
                Back to Tasks
            </a>

            <div class="flex gap-x-3 items-center">
                <button
                    x-data
                    class="btn btn-outlined"
                    data-test="edit-task-button"
                    @click="$dispatch('open-modal', 'edit-task')"
                >
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
            @if($task->image_path)
                <div class="rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $task->image_path) }}" alt="Image" class="w-full h-auto max-h-100 object-cover mb-2">
                </div>
            @endif

            <h1 class="font-bold text-4xl">{{ $task->title }}</h1>

            <div class="mt-2 flex gap-x-3 items-center">
                <x-task.status-label :status="$task->status->label()">{{ $task->status->label() }}</x-task.status-label>

                <div class="flex gap-x-3 items-center text-muted-foreground text-sm">
                    <span>Created {{ $task->created_at->diffForHumans() }}</span>
                    @if($task->created_at != $task->updated_at)
                        <span>Updated {{ $task->updated_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>

            @if($task->description)
                <x-card class="mt-6" is="div">
                    <div class="text-foreground max-w-none cursor-pointer prose prose-invert">{!! $task->formattedDescription !!}</div>
                </x-card>
            @endif

            @if($task->steps->count())
                <div>
                    <h3 class="font-bold text-xl mt-6">Actionable Steps</h3>

                    <div class="mt-3 space-y-3">
                        @foreach($task->steps as $step)
                            <x-card>
                                <form action="{{ route('step.update', $step) }}" method="post">
                                    @csrf
                                    @method('PATCH')

                                    <div class="flex items-center gap-x-3">
                                        <button type="submit" role="checkbox" class="size-5 flex items-center justify-center rounded-lg text-primary-foreground {{ $step->completed ? 'bg-primary' : 'border border-primary' }}">&check;</button>
                                        <span class="{{ $step->completed ? 'line-through text-muted-foreground' : '' }}">{{ $step->description }}</span>
                                    </div>
                                </form>
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($task->links->count())
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

        <x-task.modal :task="$task" />
    </div>
</x-layout>

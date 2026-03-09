<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Your Tasks</h1>
            <p class="text-muted-foreground text-sm mt-2">Capture your thoughts. Make a plan.</p>

            <x-card
                x-data
                @click="$dispatch('open-modal', 'create-task')"
                is="button"
                data-test="create-task-button"
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
            <form
                x-data="{
                    status: @js(App\TaskStatus::PENDING->value),
                    newLink: '',
                    links: [],
                    newStep: '',
                    steps: [],
                }"
                action="{{ route('task.store') }}"
                method="post"
                enctype="multipart/form-data"
            >
                @csrf
                <div class="space-y-6">
                    <x-form.field
                        label="Title"
                        name="title"
                        data-test="title-field"
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
                                    data-test="button-status-{{ $status->value }}"
                                    class="btn flex-1 h-10"
                                    :class="status === @js($status->value) ? 'btn-primary' : 'btn-outlined' "
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
                        data-test="description-field"
                        placeholder="Describe your task..."
                    />

                    <div class="space-y-2">
                        <label for="image" class="label">Featured Image</label>

                        <input type="file" name="image" accept="image/*">
                        <x-form.error name="image" />
                    </div>

                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Steps</legend>

                            <div class="flex gap-x-2 items-center">
                                <input
                                    x-model="newStep"
                                    type="text"
                                    id="new-step"
                                    data-test="new-step-field"
                                    placeholder="What are the steps?"
                                    class="input flex-1"
                                >

                                <button
                                    type="button"
                                    @click="steps.push(newStep.trim()); newStep='';"
                                    :disabled="newStep.trim().length === 0"
                                    class="form-muted-icon"
                                    data-test="add-step-button"
                                    aria-label="Add Stage button"
                                >
                                    <x-icons.close class="rotate-45" />
                                </button>
                            </div>

                            <template x-for="(step, index) in steps" :key="step">
                                <div class="flex gap-x-2 items-center">
                                    <input
                                        type="text"
                                        name="steps[]"
                                        x-model="step"
                                        class="input flex-1 form-muted-icon"
                                        readonly
                                    >

                                    <button
                                        type="button"
                                        @click="steps.splice(index, 1)"
                                        class="form-muted-icon"
                                        data-test="remove-step-button"
                                        aria-label="Remove link button"
                                    >
                                        <x-icons.close />
                                    </button>
                                </div>
                            </template>
                        </fieldset>
                    </div>

                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Links</legend>

                            <div class="flex gap-x-2 items-center">
                                <input
                                    x-model="newLink"
                                    type="url"
                                    id="new-link"
                                    data-test="new-link-field"
                                    placeholder="https://example.com"
                                    autocomplete="url"
                                    class="input flex-1"
                                    spellcheck="false"
                                >

                                <button
                                    type="button"
                                    @click="links.push(newLink.trim()); newLink='';"
                                    :disabled="newLink.trim().length === 0"
                                    class="form-muted-icon"
                                    data-test="add-link-button"
                                    aria-label="Add link button"
                                >
                                    <x-icons.close class="rotate-45" />
                                </button>
                            </div>

                            <template x-for="(link, index) in links" :key="link">
                                <div class="flex gap-x-2 items-center">
                                    <input
                                        type="text"
                                        name="links[]"
                                        x-model="link"
                                        class="input flex-1 form-muted-icon"
                                        readonly
                                    >

                                    <button
                                        type="button"
                                        @click="links.splice(index, 1)"
                                        class="form-muted-icon"
                                        data-test="remove-link-button"
                                        aria-label="Remove link button"
                                    >
                                        <x-icons.close />
                                    </button>
                                </div>
                            </template>
                        </fieldset>
                    </div>

                    <div class="flex justify-end gap-x-5">
                        <button type="button" @click="$dispatch('close-modal')">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </x-modal>
    </div>
</x-layout>

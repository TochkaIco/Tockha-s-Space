@props(['task' => new \App\Models\Task()])

<x-modal name="{{ $task->exists ? 'edit-task' : 'create-task' }}" title="{{ $task->exists ? 'Edit Task' : 'Create Task' }}">
    <form
        x-data="{
                    status: @js(old('status', $task->status->value)),
                    newLink: '',
                    links: @js(old('links', $task->links ?? [])),
                    newStep: '',
                    steps: @js(old('steps', $task->steps->map->only(['id', 'description', 'completed']))),
                    hasImage: false,
                }"
        action="{{ $task->exists ? route('task.update', $task) : route('task.store') }}"
        method="post"
        x-bind:enctype="hasImage ? 'multipart/form-data' : false"
    >
        @csrf
        @if($task->exists)
            @method('PATCH')
        @endif

        <div class="space-y-6">
            <x-form.field
                label="Title"
                name="title"
                data-test="title-field"
                placeholder="Enter a title for your task"
                autofocus
                required
                :value="$task->title"
            />

            <div class="space-y-2">
                <span class="label">Status</span>

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
                :value="$task->description"
            />

            <div class="space-y-2">
                <label for="image" class="label">Featured Image</label>

                @if($task->image_path)
                    <div class="space-y-2">
                        <img src="{{ asset('storage/' . $task->image_path) }}" alt="Image"
                             class="w-full h-auto max-h-60 object-cover mb-2 rounded-lg">
                        <button class="btn btn-outlined h-10 w-full" form="delete-image-form">Remove Image</button>
                    </div>
                @endif

                <input
                    type="file"
                    name="image"
                    id="image"
                    data-test="image-field"
                    accept="image/*"
                    @change="hasImage = $event.target.files.length > 0"
                >
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
                            @click="
                                steps.push({description: newStep.trim(), completed: false});
                                newStep='';
                            "
                            :disabled="newStep.trim().length === 0"
                            class="form-muted-icon"
                            data-test="add-step-button"
                            aria-label="Add step button"
                        >
                            <x-icons.close class="rotate-45" />
                        </button>
                    </div>

                    <template x-for="(step, index) in steps" :key="step.id || index">
                        <div class="flex gap-x-2 items-center">
                            <input :name="`steps[${index}][description]`" x-model="step.description" class="input" readonly>
                            <input type="hidden" :name="`steps[${index}][completed]`" x-model="step.completed ? '1' : '0'" class="input" readonly>

                            <button
                                type="button"
                                @click="steps.splice(index, 1)"
                                class="form-muted-icon"
                                data-test="remove-step-button"
                                aria-label="Remove step button"
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
                                class="input"
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
                <button type="submit" class="btn btn-primary" data-test="submit-button">{{ $task->exists ? 'Save' : 'Create' }}</button>
            </div>
        </div>
    </form>

    @if($task->image_path)
        <form action="{{ route('task.image.destroy', $task) }}" id="delete-image-form" method="post">
            @csrf
            @method('DELETE')
        </form>
    @endif
</x-modal>

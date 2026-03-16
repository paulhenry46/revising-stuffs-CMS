<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-sm breadcrumbs mb-2">
                <ul>
                    <li><a wire:navigate href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a wire:navigate href="{{ route('co-admin.index') }}">{{ __('Co-Admin Panel') }}</a></li>
                    <li>{{ __('Generate a post pack') }}</li>
                </ul>
            </div>

            <div class="bg-white dark:bg-base-100 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-base-100 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                        {{ __('Generate a post pack') }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Select and order the posts you want to include in the PDF booklet. The first page will list all credits.') }}
                    </p>
                </div>

                <div class="bg-white/25 dark:bg-base-100/25 p-6 lg:p-8">
                    <x-info-message />

                    {{-- Alpine.js component handling post selection & ordering --}}
                    <div
                        x-data="{
                            search: '',
                            selected: [],
                            get filtered() {
                                const q = this.search.toLowerCase();
                                return posts.filter(p =>
                                    p.title.toLowerCase().includes(q) ||
                                    p.author.toLowerCase().includes(q) ||
                                    p.course.toLowerCase().includes(q)
                                );
                            },
                            isSelected(id) {
                                return this.selected.some(p => p.id === id);
                            },
                            toggle(post) {
                                if (this.isSelected(post.id)) {
                                    this.selected = this.selected.filter(p => p.id !== post.id);
                                } else {
                                    this.selected.push(post);
                                }
                            },
                            moveUp(index) {
                                if (index === 0) return;
                                [this.selected[index - 1], this.selected[index]] =
                                    [this.selected[index], this.selected[index - 1]];
                                this.selected = [...this.selected];
                            },
                            moveDown(index) {
                                if (index === this.selected.length - 1) return;
                                [this.selected[index], this.selected[index + 1]] =
                                    [this.selected[index + 1], this.selected[index]];
                                this.selected = [...this.selected];
                            },
                            remove(index) {
                                this.selected.splice(index, 1);
                                this.selected = [...this.selected];
                            }
                        }"
                        x-init="posts = @js($posts->map(fn($p) => [
                            'id'     => $p->id,
                            'title'  => $p->title,
                            'author' => $p->user->name ?? '',
                            'course' => $p->course->name ?? '',
                            'level'  => $p->level->name ?? '',
                            'hasPdf' => $p->files->contains(fn($f) => str_starts_with($f->type ?? '', 'primary')),
                        ]))"
                    >
                        <form action="{{ route('co-admin.pack.generate') }}" method="POST">
                            @csrf

                            {{-- Pack title --}}
                            <div class="mb-6">
                                <label class="label" for="pack_title">
                                    <span class="label-text font-semibold">{{ __('Pack title') }}</span>
                                </label>
                                <input
                                    type="text"
                                    id="pack_title"
                                    name="pack_title"
                                    class="input input-bordered w-full max-w-md"
                                    placeholder="{{ __('e.g. Physics — Year 1 revision booklet') }}"
                                    required
                                    maxlength="200"
                                    value="{{ old('pack_title') }}"
                                />
                            </div>

                            {{-- Curriculum selector (if the co-admin manages several) --}}
                            @if($managedCurricula->count() > 1)
                            <div class="mb-6">
                                <label class="label" for="curriculum_id">
                                    <span class="label-text font-semibold">{{ __('Curriculum (for the logo)') }}</span>
                                </label>
                                <select name="curriculum_id" id="curriculum_id" class="select select-bordered w-full max-w-xs">
                                    @foreach($managedCurricula as $curriculum)
                                        <option value="{{ $curriculum->id }}" {{ old('curriculum_id') == $curriculum->id ? 'selected' : '' }}>
                                            {{ $curriculum->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                                <input type="hidden" name="curriculum_id" value="{{ $managedCurricula->first()?->id }}">
                            @endif

                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

                                {{-- Left panel: available posts --}}
                                <div>
                                    <h2 class="text-lg font-semibold mb-3">{{ __('Available posts') }}</h2>

                                    <input
                                        type="text"
                                        x-model="search"
                                        class="input input-bordered w-full mb-3"
                                        placeholder="{{ __('Search by title, author or course…') }}"
                                    />

                                    <div class="overflow-y-auto max-h-[60vh] border border-base-300 rounded-lg">
                                        @if($posts->isEmpty())
                                            <div class="p-6 text-center text-gray-500">
                                                {{ __('No published posts are available in your curricula.') }}
                                            </div>
                                        @else
                                        <table class="table table-zebra w-full">
                                            <thead class="sticky top-0 bg-base-200">
                                                <tr>
                                                    <th></th>
                                                    <th>{{ __('Title') }}</th>
                                                    <th>{{ __('Author') }}</th>
                                                    <th>{{ __('Course') }}</th>
                                                    <th class="text-center">PDF</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="post in filtered" :key="post.id">
                                                    <tr
                                                        :class="isSelected(post.id) ? 'bg-primary/10' : ''"
                                                        class="cursor-pointer"
                                                        @click="toggle(post)"
                                                    >
                                                        <td>
                                                            <input
                                                                type="checkbox"
                                                                class="checkbox checkbox-primary"
                                                                :checked="isSelected(post.id)"
                                                                @click.stop="toggle(post)"
                                                            />
                                                        </td>
                                                        <td class="font-medium" x-text="post.title"></td>
                                                        <td x-text="post.author"></td>
                                                        <td x-text="post.course"></td>
                                                        <td class="text-center">
                                                            <span x-show="post.hasPdf" class="text-success" title="{{ __('Has a primary PDF') }}">
                                                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18"><path d="m424-408-86-86q-11-11-28-11t-28 11q-11 11-11 28t11 28l114 114q12 12 28 12t28-12l226-226q11-11 11-28t-11-28q-11-11-28-11t-28 11L424-408Zm56 328q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
                                                            </span>
                                                            <span x-show="!post.hasPdf" class="text-warning" title="{{ __('No primary PDF – a fallback page will be generated') }}">
                                                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18"><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                        @endif
                                    </div>
                                </div>

                                {{-- Right panel: selected posts in order --}}
                                <div>
                                    <h2 class="text-lg font-semibold mb-3">
                                        {{ __('Selected posts') }}
                                        <span class="badge badge-primary ml-2" x-text="selected.length"></span>
                                    </h2>

                                    <div class="overflow-y-auto max-h-[60vh] border border-base-300 rounded-lg p-2 min-h-[120px]">
                                        <template x-if="selected.length === 0">
                                            <p class="text-center text-gray-500 py-8">
                                                {{ __('Click posts on the left to add them here.') }}
                                            </p>
                                        </template>

                                        <template x-for="(post, index) in selected" :key="post.id">
                                            {{-- Hidden inputs carry the ordered post IDs --}}
                                            <div class="flex items-center gap-2 p-2 border-b border-base-300 last:border-0">
                                                <input type="hidden" name="post_ids[]" :value="post.id" />

                                                <span class="text-base-content/40 w-6 text-right text-sm" x-text="index + 1 + '.'"></span>

                                                <div class="flex-1 min-w-0">
                                                    <div class="font-medium truncate" x-text="post.title"></div>
                                                    <div class="text-xs text-gray-500" x-text="post.author + ' · ' + post.course"></div>
                                                </div>

                                                <div class="flex flex-col gap-1 shrink-0">
                                                    <button
                                                        type="button"
                                                        class="btn btn-xs btn-ghost"
                                                        :disabled="index === 0"
                                                        @click="moveUp(index)"
                                                        title="{{ __('Move up') }}"
                                                    >
                                                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16"><path d="M440-160v-487L216-423l-56-57 320-320 320 320-56 57-224-224v487h-80Z"/></svg>
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-xs btn-ghost"
                                                        :disabled="index === selected.length - 1"
                                                        @click="moveDown(index)"
                                                        title="{{ __('Move down') }}"
                                                    >
                                                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16"><path d="M440-800v487L216-537l-56 57 320 320 320-320-56-57-224 224v-487h-80Z"/></svg>
                                                    </button>
                                                </div>

                                                <button
                                                    type="button"
                                                    class="btn btn-xs btn-ghost text-error"
                                                    @click="remove(index)"
                                                    title="{{ __('Remove') }}"
                                                >
                                                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="mt-4 flex justify-end">
                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                            :disabled="selected.length === 0"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="m480-320 160-160-56-56-64 64v-168h-80v168l-64-64-56 56 160 160Zm0 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg> {{ __('Generate PDF') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>{{-- end x-data --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

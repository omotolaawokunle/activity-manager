<x-app-layout>
    <x-slot name="header">
        <div class="px-8 pt-8 pb-4">
            <div class="lg:flex lg:items-center lg:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        Edit {{ $activity->title }} {{ $user ? " for {$user->name}" : "" }}
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="post" action="{{ route('activities.update', $activity) }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="title" value="Title" />
                            <x-text-input id="title" name="title" type="text" class="block w-full mt-1" :value="old('title', $activity->title)" required autofocus autocomplete="title" />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="desc" value="Description" />
                            <x-text-input id="desc" name="description" type="text" class="block w-full mt-1" :value="old('description', $activity->description)" required autocomplete="description" />
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div>
                            <x-input-label for="date" value="Activity Date" />
                            <x-text-input id="date" name="activity_date" type="date" class="block w-full mt-1" :value="old('activity_date', $activity->activity_date->format('Y-m-d'))" required autocomplete="activity_date" />
                            <x-input-error class="mt-2" :messages="$errors->get('activity_date')" />
                        </div>

                        <div>
                            <x-input-label for="img" value="Activity Image" />
                            <x-text-input id="img" name="activity_image" type="file" accept="image/*" class="block w-full mt-1" />
                            <x-input-error class="mt-2" :messages="$errors->get('activity_image')" />
                        </div>

                        @if($user)
                            <x-text-input type="hidden" name="user_id" :value="$user->id" />
                        @else
                            <x-input-label for="user" value="Create Activity For User" />
                            <select name="user_id" id="user" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-100">
                                <option @selected(old('user_id') == '') value="">All Users</option>
                                @foreach($users as $usr)
                                <option @selected(old('user_id') == $usr->id || $user ? $user->id == $usr->id : false) value="{{ $usr->id }}">{{ $usr->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                        @endif

                        <div class="flex items-center gap-4">
                            <x-primary-button>Update</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

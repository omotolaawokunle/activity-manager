<x-app-layout>
    <x-slot name="header">
        <div class="px-8 pt-8 pb-4">
            <div class="lg:flex lg:items-center lg:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        {{ $user ? "{$user->name}'s " : '' }} Activities
                    </h2>
                </div>
                <div class="flex mt-5 lg:mt-0 lg:ml-4">
                    <span class="block">
                        <a href="{{ $user ? route('activities.create')."?user_id={$user->id}" : route('activities.create') }}"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 -ml-1 text-gray-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Add New
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="py-4">
            <div class="py-2">
                <div class="flex flex-col overflow-hidden">
                    @if($activities->count() < 1) <div class="flex flex-col items-center justify-center">
                        <img src="{{ asset('images/empty.svg') }}" alt="empty state image"
                            class="object-cover object-center w-1/3 mb-3">
                        <h4 class="mb-1 text-lg font-medium text-blue-900">No activities have been added yet!</h4>
                </div>
                @else
                <div class="px-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                    <div class="inline-block min-w-full overflow-hidden align-middle">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-4 text-sm font-medium text-center text-gray-600">
                                        S/N
                                    </th>
                                    <th class="px-4 py-4 text-sm font-medium text-center text-gray-600">
                                        Title
                                    </th>
                                    <th class="px-4 py-4 text-sm font-medium text-center text-gray-600">
                                        Activity Date
                                    </th>
                                    <th class="px-4 py-4 text-sm font-medium text-center text-gray-600">
                                        Added On
                                    </th>
                                    <th class="px-4 py-4 text-sm font-medium text-center text-gray-600">
                                    </th>
                                    <th class="px-4 py-4 text-sm font-medium text-center text-gray-600">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($sn = 0)
                                @foreach($activities as $activity)
                                @php($sn++)
                                <tr
                                    class="bg-white border-b border-gray-100 shadow last:border-b-0 sm:rounded-lg hover:bg-gray-100">
                                    <td
                                        class="px-4 py-4 text-sm font-medium text-center text-gray-600 whitespace-no-wrap">
                                        {{ $sn + ((request()->get('page', 1) - 1) * 10) }}
                                    </td>
                                    <td
                                        class="flex items-center px-4 py-4 text-sm font-medium text-left text-gray-600 whitespace-no-wrap">
                                        <div class="mr-2">
                                            @if($activity->image)
                                            <img src="{{ $activity->image }}" alt="{{ $activity->title }}" class="object-cover object-center w-10 h-10 rounded-full">
                                            @else
                                            @php
                                            preg_match_all('/(?<=\b)\w/iu',$activity->title,$matches);
                                            $acronym = mb_strtoupper(implode('',$matches[0]))
                                            @endphp
                                            <img src="https://via.placeholder.com/200x200.png?text={{ $acronym }}" alt="{{ $activity->title }}" class="object-cover object-center w-10 h-10 rounded-full">
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-base font-bold text-gray-800">{{ $activity->title }}</h4>
                                            <p>{{ $activity->description }}</p>
                                        </div>
                                    </td>
                                    <td
                                        class="px-4 py-4 text-sm font-medium text-center text-gray-600 whitespace-no-wrap">
                                        {{ $activity->activity_date->format('d M Y') }}
                                    </td>
                                    <td
                                        class="px-4 py-4 text-sm font-medium text-center text-gray-600 whitespace-no-wrap">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </td>
                                    <td>
                                        <a href="{{ $user ? route('activities.edit', $activity)."?user_id={$user->id}" : route('activities.edit', $activity) }}"
                                            class="text-blue-500 hover:text-blue-600 hover:underline">Edit</a>
                                    </td>
                                    <td>
                                        <form action="{{ route('activities.destroy', $activity) }}" method="post"
                                            ref="deleteForm">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <a href="#" v-on:click="submitDelete()"
                                            class="text-red-500 hover:text-red-600 hover:underline">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-6">
                    {{ $activities->links() }}
                </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="px-8 pt-8 pb-4">
            <div class="lg:flex lg:items-center lg:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        All Users
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="py-4">
            <div class="py-2">
                <div class="flex flex-col overflow-hidden">
                    @if($users->count() < 1) <div class="flex flex-col items-center justify-center">
                        <img src="{{ asset('images/empty.svg') }}" alt="empty state image"
                            class="object-cover object-center w-1/3 mb-3">
                        <h4 class="mb-1 text-lg font-medium text-blue-900">No users have registered yet!</h4>
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
                                        Name
                                    </th>
                                    <th class="px-4 py-4 text-sm font-medium text-center text-gray-600">
                                        No. of Activities
                                    </th>
                                    <th class="px-4 py-4 text-sm font-medium text-center text-gray-600">
                                        Registered On
                                    </th>
                                    <th class="px-4 py-4 text-sm font-medium text-center text-gray-600">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($sn = 0)
                                @foreach($users as $user)
                                @php($sn++)
                                <tr
                                    class="bg-white border-b border-gray-100 shadow last:border-b-0 sm:rounded-lg hover:bg-gray-100">
                                    <td
                                        class="px-4 py-4 text-sm font-medium text-center text-gray-600 whitespace-no-wrap">
                                        {{ $sn + ((request()->get('page', 1) - 1) * 10) }}
                                    </td>
                                    <td
                                        class="px-4 py-4 text-sm font-medium text-left text-gray-600 whitespace-no-wrap">
                                            <h4 class="text-base font-bold text-gray-800">{{ $user->name }}</h4>
                                            <p>{{ $user->email }}</p>
                                    </td>
                                    <td
                                        class="px-4 py-4 text-sm font-medium text-center text-gray-600 whitespace-no-wrap">
                                        {{ $user->activities_count }}
                                    </td>
                                    <td
                                        class="px-4 py-4 text-sm font-medium text-center text-gray-600 whitespace-no-wrap">
                                        {{ $user->created_at->diffForHumans() }}
                                    </td>
                                    <td>
                                        <a href="{{ route('activities.index') }}?user_id={{ $user->id }}"
                                            class="text-blue-500 hover:text-blue-600 hover:underline">View Activities</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>

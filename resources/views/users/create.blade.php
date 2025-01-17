<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Users / Create
            </h2>
            <a href="{{ route('users.index') }}" class="bg-slate-700 txt-sm text-white px-3 py-2 rounded-md hover:bg-slate-800">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('users.store') }}" method="post">
                        @csrf
                        <div>
                            <label for="name" class="text-lg font-medium">Name</label>
                            <div class="my-3">
                                <input type="text" placeholder="Name" name="name" value="{{ old('name') }}" class="border-gray-300 shadow-sm w-1/2 rounded-md" maxlength="100">
                                @error('name')
                                    <p class="text-red-400 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-4 gap-4 mb-3">
                                {{-- @if($permissions->count() > 0)
                                    @foreach ($permissions as $permission)
                                        <div class="mt-3">
                                            <input type="checkbox" id="permission-{{ $permission->id}}" class="rounded" name="permissions[]" value="{{ $permission->name }}">
                                            <label for="permission-{{ $permission->id}}">{{ $permission->name }}</label>
                                        </div>
                                    @endforeach

                                @endif --}}
                            </div>
                        </div>
                        <button type="submit" class="bg-slate-700 txt-sm text-white px-5 py-2 rounded-md hover:bg-slate-800">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

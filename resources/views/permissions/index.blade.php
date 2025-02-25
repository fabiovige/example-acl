<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Permissions') }}
            </h2>
            @can('create permissions')
                <a href="{{ route('permissions.create') }}" class="bg-slate-700 txt-sm text-white px-3 py-2 rounded-md hover:bg-slate-800">Add Permission</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-message />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr class="border-b">
                                <th class="text-left px-6 py-2" width="60">#Id</th>
                                <th class="text-left px-6 py-2">Name</th>
                                <th class="text-left px-6 py-2" width="200">Created</th>
                                <th class="text-center px-6 py-2" width="200">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @if($permissions->isNotEmpty())
                                @foreach($permissions as $permission)
                                    <tr class="border-b">
                                        <td class="px-6 py-4 text-left">{{ $permission->id }}</td>
                                        <td class="px-6 py-4 text-left">{{ $permission->name }}</td>
                                        <td class="px-6 py-4 text-left">{{ $permission->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @can('edit permissions')
                                            <a href="{{ route('permissions.edit', $permission->id) }}" class="bg-slate-700 txt-sm text-white px-4 py-1 rounded-md hover:bg-slate-800">Edit</a>
                                            @endcan
                                            @can('delete permissions')
                                            <a href="javascript:void(0)"
                                                onclick="deletePermission({{ $permission->id }})"
                                                class="bg-red-700 txt-sm text-white px-4 py-1 rounded-md hover:bg-red-800">Delete</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $permissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="scripts">
        <script type="text/javascript">
            function deletePermission(id) {
                    if(confirm('Deseja realmente excluir essa permissão?')) {
                        $.ajax({
                            url: '{{ route("permissions.destroy") }}',
                            type: 'DELETE',
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                window.location.href = '{{ route("permissions.index") }}';
                            }
                    });
                }
            }
        </script>
    </x-slot>
</x-app-layout>

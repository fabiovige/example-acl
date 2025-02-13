<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Roles') }}
            </h2>
            @can('create roles')
                <a href="{{ route('roles.create') }}" class="bg-slate-700 txt-sm text-white px-3 py-2 rounded-md hover:bg-slate-800">Add Roles</a>
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
                                <th class="text-left px-6 py-2">Permissions</th>
                                <th class="text-left px-6 py-2" width="200">Created</th>
                                <th class="text-center px-6 py-2" width="200">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @if($roles->isNotEmpty())
                                @foreach($roles as $role)
                                    <tr class="border-b">
                                        <td class="px-6 py-4 text-left">{{ $role->id }}</td>
                                        <td class="px-6 py-4 text-left">{{ $role->name }}</td>
                                        <td class="px-6 py-4 text-left">
                                            {{ $role->permissions->pluck('name')->join(', ') }}
                                        </td>
                                        <td class="px-6 py-4 text-left">{{ $role->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @can('edit roles')
                                                <a href="{{ route('roles.edit', $role->id) }}" class="bg-slate-700 txt-sm text-white px-4 py-1 rounded-md hover:bg-slate-800">Edit</a>
                                            @endcan
                                            @can('delete roles')
                                            <a href="javascript:void(0)"
                                                onclick="deleteRole({{ $role->id }})"
                                                class="bg-red-700 txt-sm text-white px-4 py-1 rounded-md hover:bg-red-800">Delete</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="scripts">
        <script type="text/javascript">
            function deleteRole(id) {
                    if(confirm('Deseja realmente excluir esse papel?')) {
                        $.ajax({
                            url: '{{ route("roles.destroy") }}',
                            type: 'DELETE',
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                window.location.href = '{{ route("roles.index") }}';
                            }
                    });
                }
            }
        </script>
    </x-slot>
</x-app-layout>

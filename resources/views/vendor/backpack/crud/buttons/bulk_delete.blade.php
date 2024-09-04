@if ($crud->hasAccess('delete'))
    <a href="javascript:void(0)" onclick="deleteItems(this)" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Delete Selected"><i class="la la-trash"></i> Delete Selected</a>
@endif

@push('after_scripts')
    <script>
        function deleteItems(button) {
            // Get the selected item IDs
            var selectedItems = document.querySelectorAll('.crud_bulk_actions_line_checkbox:checked');
            var itemIds = Array.from(selectedItems).map(checkbox => checkbox.getAttribute('data-primary-key-value'));
            console.log(itemIds);
            if (itemIds.length === 0) {
                alert('Please select at least one item to delete.');
                return;
            }

            if (confirm('Are you sure you want to delete the selected items?')) {
                // Send a DELETE request to the server
                axios.post('{{ url($crud->route.'/bulk-delete') }}', { ids: itemIds })
                    .then(function (response) {
                        location.reload();
                    })
                    .catch(function (error) {
                        alert('An error occurred while deleting the items.');
                    });
            }
        }
    </script>
@endpush

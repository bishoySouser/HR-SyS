<script>
    function restoreEntry(button) {
      swal({
        title: "{!! trans('backpack::base.warning') !!}",
        text: "Are you sure you want to restore this item?",
        icon: "warning",
        buttons: ["{!! trans('backpack::crud.cancel') !!}", "{!! trans('backpack::crud.yes') !!}"],
        dangerMode: true,
      }).then((value) => {
        if (value) {
          $.ajax({
            url: $(button).data('route'),
            type: 'GET',
            success: function(result) {
              if (result.status === 'success') {
                new Noty({
                  type: "success",
                  text: result.message,
                }).show();
                crud.table.ajax.reload();
              } else {
                new Noty({
                  type: "error",
                  text: "Error restoring entry",
                }).show();
              }
            },
            error: function(result) {
              new Noty({
                type: "error",
                text: "Error restoring entry",
              }).show();
            }
          });
        }
      });
    }
  </script>

<a href="javascript:void(0)" onclick="restoreEntry(this)" data-route="{{ route('trashed-employee.restore', $entry->id) }}" class="btn btn-sm btn-link" data-button-type="restore">
    <i class="la la-undo"></i> Restore
</a>

@push('after_scripts')

@endpush

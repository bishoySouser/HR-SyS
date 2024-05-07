<script>
    if (typeof resetPassword != 'function') {
      $("[data-button-type=reset-password]").unbind('click');

      function resetPassword(button) {
          // ask for confirmation before deleting an item
          var entityId = $(button).data('entity-id');

          var button = $(button);
          var route = button.attr('data-route');
          var data = {id: entityId};

          $.ajax({
              url: route,
              type: 'POST',
              data: data,
              success: function(response) {
                  // Show an alert with the result
                  console.log(response.message);
                  new Noty({
                      text: response.message,
                      type: "success"
                  }).show();

                  // Hide the modal, if any
                  $('.modal').modal('hide');

                  crud.table.ajax.reload();
              },
              error: function(response) {
                  // Show an alert with the result
                  new Noty({
                      text: "There was a problem. Please try again.",
                      type: "warning"
                  }).show();
              }
          });
      }
    }
</script>

@if ($crud->hasAccess('create', $entry))
{{-- @push('before_scripts') --}}
{{-- @endpush --}}
    <a href="javascript:void(0)" onclick="resetPassword(this)" data-route="{{ route('resetPassword') }}" class="btn btn-sm btn-link" data-entity-id="{{ $entry->id }}" data-button-type="reset-password">
        <span class="ladda-label"><i class="la la-unlock-alt"></i> Reset Password</span>
    </a>
@endif





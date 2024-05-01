<script>

</script>

@if ($crud->hasAccess('create', $entry))
{{-- @push('before_scripts') --}}
{{-- @endpush --}}
    <a href="javascript:void(0)" onclick="resetPassword(this)" data-route="{{ route('resetPassword') }}" class="btn btn-sm btn-link" data-button-type="reset">
        <span class="ladda-label"><i class="la la-unlock-alt"></i> asdasd {{ $entry->id }}</span>
    </a>
@endif





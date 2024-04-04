<label for="{{ $field['name'] }}">{{ $field['label'] }}</label>

@foreach($employees as $id => $name)
    <input type="checkbox" name="employee_id[]" id="employee_{{ $id }}" value="{{ $id }}"
        @if (in_array($id, $selected)) checked @endif
    >
    <label for="employee_{{ $id }}">{{ $name }}</label>
    <br>
@endforeach

@error('employee_id')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror

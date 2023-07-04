import './bootstrap';

/**
 * Employee
 */
// CREATE
crud.field('role').onChange(function(field) {
    crud.field('manager_id').show(field.value == 0);
}).change();

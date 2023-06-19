var jobIdToDelete;

// Delete button click event
$('.delete').on('click', function() {
    jobIdToDelete = $(this).closest('tr').data('id'); // Get the job ID from the data-id attribute
});

// Confirm Delete button click event
$('#confirmDeleteBtn').on('click', function() {
    if (jobIdToDelete) {
        var row = $('tr[data-id="' + jobIdToDelete + '"]');
        
        $.ajaxSetup({
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
         });

        // AJAX request to delete the job offer
        $.ajax({
            url: '/delete/' + jobIdToDelete, // Replace with your delete route
            type: 'DELETE',
            success: function(data) {

                console.log(data);
                row.remove(); // Remove the row from the table
            },
            error: function(xhr, status, error) {
                console.log(error); // Handle any errors
            }
        });

        // Close the modal
        $('#confirmDeleteModal').modal('hide');
    }
});

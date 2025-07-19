$(document).ready(function() {
    $('#toggle-form-btn').click(function() {
        $('#discount-form').toggle();
        var formVisible = $('#discount-form').is(':visible');
    });

    $('#selected-categories').on('change', function() {
        var selectedOptions = $(this).find('option:selected');
        var dropdownBOptions = $('#default-category option');

        dropdownBOptions.each(function() {
            var optionValue = $(this).val();

            // Check if the option is still selected in dropdown A
            var optionSelected = selectedOptions.filter(function() {
                return $(this).val() === optionValue;
            }).length > 0;

            // Remove the option from dropdown B if it is no longer selected
            if (!optionSelected) {
                $(this).remove();
            }
        });

        // Add new options to dropdown B
        selectedOptions.each(function() {
            var selectedOptionValue = $(this).val();
            var selectedOptionText = $(this).text();

            // Check if the option already exists in dropdown B
            var optionExists = dropdownBOptions.filter(function() {
                return $(this).val() === selectedOptionValue;
            }).length > 0;

            // Append the option to dropdown B if it doesn't already exist
            if (!optionExists) {
                $('#default-category').append($('<option></option>').val(
                    selectedOptionValue).text(
                    selectedOptionText));
            }
        });
    });

    // Remove options from dropdown B when an option is clicked to be deselected in dropdown A
    $('#selected-categories').on('click', 'option', function() {
        var deselectedOptionValue = $(this).val();
        $('#default-category option[value="' + deselectedOptionValue + '"]').remove();
    });


});


        $(document).ready(function() {
            var tableBody = $('#image-table-body');
            $('.save_images').click(function() {
                var rows = imageTableBody.querySelectorAll('tr');
                var data = [];
                for (var i = 0; i < rows.length; i++) {
                    console.log(rows[i].querySelector('.position').textContent);
                    var caption = document.querySelector('input[name="caption[' + rows[i].querySelector(
                        '.position').textContent + ']"]');
                    if (caption) {
                        var filename = caption.getAttribute('data-filename');
                        data[i] = {
                            'filename': filename,
                            'position': rows[i].querySelector('.position').textContent,
                            'caption': caption.value
                        };
                    }
                    rows[i].querySelector('.position').textContent = i + 1;
                }
                // $('form').append('<input type="hidden" name="gallery_caption[]" id="gallery_caption" value="' +  caption.value + '">');
                // $('input[name="gallery[]"]').val(JSON.stringify(data));
            });

            $('table').on('click', '.delete-btn', function() {
                console.log('clicked');
                var saveButton =  document.getElementById('save_images');
                var updatePositions = function() {
                    var rows = imageTableBody.querySelectorAll('tr');
                    for (var i = 0; i < rows.length; i++) {
                        rows[i].querySelector('.position').textContent = i + 1;
                    }
                };
                // Remove the parent row when delete button is clicked
                $(this).closest('tr').remove();
                // Update positions
                // updatePositions();
                if (document.querySelectorAll('#image-table-body tr').length === 0) {
                    saveButton.style.display = 'none'; // Hide the save button
                }
            });
        });

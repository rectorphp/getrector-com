<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

{{-- live code highligh in demo --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.28.0/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.28.0/addon/edit/matchbrackets.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.28.0/mode/xml/xml.min.js"></script>

{{-- php --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.28.0/mode/clike/clike.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.28.0/mode/php/php.min.js"></script>

{{-- diff --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.28.0/mode/diff/diff.min.js"></script>

{{-- @see https://codemirror.net/mode/ --}}
<script type="text/javascript">
    $('.codemirror_php').each(function(index, elem){
        CodeMirror.fromTextArea(elem, {
            mode: 'application/x-httpd-php',
            indentUnit: 4,
            matchBrackets: true,
            inputStyle: 'contenteditable',
            viewportMargin: Infinity,
        });
    });

    $('.codemirror_diff').each(function(index, elem){
        CodeMirror.fromTextArea(elem, {
            mode: 'text/x-diff',
            lineSeparator: "\n",
            indentUnit: 4,
            matchBrackets: true,
            viewportMargin: Infinity,
        });
    });
</script>

{{-- demo from on click submit "in progress..." effect --}}
<script type="text/javascript">
    var formState = null;

    $('.btn-demo-submit').on('click', function (event) {
        event.preventDefault();

        if (formState !== 'submitted') {
            formState = 'submitted';

            $(this).addClass('disabled').attr('disabled', 'disabled');
            $(this).prepend('<em class="fas fa-sync-alt fa-spin"></em>&nbsp;&nbsp;&nbsp;');
            $(this).closest('form').submit();
        }
    });
</script>

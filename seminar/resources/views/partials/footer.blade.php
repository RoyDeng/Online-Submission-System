	<script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
    <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
	<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
	<script src="{{ asset('js/inspinia.js') }}"></script>
    <script src="{{ asset('js/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
	<script>
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]
            });

            $('.deadline').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format: "yyyy-mm-dd"
            });

            $('#conference-list').select2().on('change', function() {
                $('#submit-select-conference').attr("disabled", true);
                var id = $(this).val();
                if (id) {
                    $.ajax({
                        url: '{{ url('author/conference/topic_list') }}/' + id,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                            $('#topic-list').empty();
                            $.each(data, function(key, value) {
                                $('#submit-select-conference').attr("disabled", false);
                                $('#topic-list').append('<option value="'+ key +'">'+ value +'</option>');
                            });
                        }
                    });
                } else {
                    $('#topic-list').empty();
                }
            });

            $('input:radio[name="status"]').change(function(){
                if ($(this).is(':checked') && $(this).val() == 2) {
                    html = '<span id="need-revision">';
                    html += '<div class="form-group">';
                    html += '<label class="form-label" for="revision-comment">Revision Comment</label>';
                    html += '<div class="controls">';
                    html += '<textarea id="revision-comment" class="form-control" name="revision_comment" rows="10" required></textarea>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="form-group">'
                    html += '<label class="form-label" for="deadline">Deadline</label>';
                    html += '<div class="controls">';
                    html += '<input class="form-control deadline" id="deadline" name="deadline" required>'
                    html += '</div>';
                    html += '</div>';
                    html += '<span>';
                    $('#revision-area').append(html);
                } else {
                    $('#need-revision').remove();
                }
            });
        });
    </script>
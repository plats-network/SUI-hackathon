$('.job').on('click', function (e) {
    console.log('job');
    // var id = $(this).data('id'),
    //     event_id = $(this).data('detail-id'),
    //     _token = $('meta[name="csrf-token"]').attr('content');
    // $.ajax({
    //     url: '/event-job/' + id,
    //     type: 'GET',
    //     dataType: 'json',
    //     data: {
    //         _token: _token,
    //         event_id: event_id,
    //     },
    //     success: function (data) {
    //         if (data.message == 'OK') {
    //             $.notify("Success.", "success");
    //         } else {
    //             $.notify("Success", "error");
    //         }
    //     },
    //     error: function (data) {
    //         $.notify("Errors.", "error");
    //     }
    // });
})
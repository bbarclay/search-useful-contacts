jQuery( document ).ready( function( $ ) {

    // $() will work as an alias for jQuery() inside of this function

    $('#algolia-search-box').on('keyup' , 'input', function(e) { 

        e.preventDefault();
        var search = $(this).val(); 


        $.ajax({
            url : searchusefulcontacts_ajax.ajax_url,
            type : 'post',
            data : {
                action : 'query_contact',
                search : search
            },
            success : function( response ) {
            	
                //$('#search-useful-contacts').html(response);

            }
        });

        if(search.length > 0) {
            //$('#search-useful-contacts').html('');
        }
          
    });  

    function push_to_approve() {

        var processing = false; 

            $('.single-contact .btn').on('click', function(){

                 var id         = $(this).closest('.single-contact').attr('id');
                 var btn        = $(this);
                    
                 btn.prop('disabled', true);   
                 btn.after(' <i class="fa fa-spin fa-spinner"></i>');


                 $.ajax({
                    url: searchusefulcontacts_ajax.ajax_url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'approve_contact',
                        id: id,
                        security: searchusefulcontacts_ajax.security
                    },
                    success: function( data ) {

                        console.log(data.data);
                        if(data.data.status == true) {

                            $('#' + id).find('.fa').remove();

                            btn.css('background-color', '#ddd');
                            btn.text('Done');
                            btn.prop('disabled', true)


                        }
                    },
                    error: function( MLHttpRequest, textStatus, errorThrown ) {
                                alert(errorThrown);
                    }


                 });

            }); 

    }
    push_to_approve();

} );